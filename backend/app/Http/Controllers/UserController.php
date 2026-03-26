<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HasFilters;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('permission:view users')->only(['index']);
        $this->middleware('permission:create users')->only(['create', 'store']);
        // edit/update/destroy pending implementation
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil semua karyawan internal (termasuk teknisi/noc)
        // Kita whitelist role yang dianggap sebagai pegawai internal
        $internalRoles = ['super-admin', 'admin', 'sales-cs', 'finance', 'noc', 'warehouse', 'hrd', 'technician'];

        $query = User::with('roles')->whereHas('roles', function ($q) use ($internalRoles) {
            $q->whereIn('name', $internalRoles);
        });

        // Apply global filters (search)
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'created_at',
            'searchColumns' => ['name', 'email']
        ]);

        // Apply role filter
        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total' => User::whereHas('roles', fn($q) => $q->whereIn('name', $internalRoles))->count(),
            'active' => User::whereHas('roles', fn($q) => $q->whereIn('name', $internalRoles))->where('is_active', true)->count(),
        ];

        // Filter options
        $roles = \Spatie\Permission\Models\Role::whereIn('name', $internalRoles)->pluck('name', 'name')->toArray();

        return view('users.index', compact('users', 'stats', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua role internal
        $internalRoles = ['super-admin', 'admin', 'sales', 'finance', 'noc', 'warehouse', 'hrd'];
        $roles = \Spatie\Permission\Models\Role::whereIn('name', $internalRoles)->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            // Employee fields (nullable)
            'nik' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'position' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'join_date' => 'nullable|date',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            // Employee fields
            'nik' => $request->nik,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'position' => $request->position,
            'department' => $request->department,
            'join_date' => $request->join_date,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('roles')->findOrFail($id);
        $internalRoles = ['super-admin', 'admin', 'sales', 'finance', 'noc', 'warehouse', 'hrd', 'technician'];
        $roles = \Spatie\Permission\Models\Role::whereIn('name', $internalRoles)->get();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
            // Employee fields (nullable)
            'nik' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'position' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'join_date' => 'nullable|date',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            // Employee fields
            'nik' => $request->nik,
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'position' => $request->position,
            'department' => $request->department,
            'join_date' => $request->join_date,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        // Sync role
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
