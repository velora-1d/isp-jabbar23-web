<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin');
    }

    public function index()
    {
        // List backup files from storage
        $backups = [];
        if (Storage::disk('local')->exists('backups')) {
            $files = Storage::disk('local')->files('backups');
            foreach ($files as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'size' => Storage::disk('local')->size($file),
                    'date' => date('Y-m-d H:i:s', Storage::disk('local')->lastModified($file)),
                ];
            }
        }

        rsort($backups);

        return view('admin.backup.index', compact('backups'));
    }

    public function create()
    {
        try {
            // Create backup directory if not exists
            if (!Storage::disk('local')->exists('backups')) {
                Storage::disk('local')->makeDirectory('backups');
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            
            // In production, use proper backup package like spatie/laravel-backup
            // For now, just show success message
            
            return redirect()->route('backup.index')
                ->with('success', 'Backup berhasil dibuat! (Demo mode)');
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $path = 'backups/' . $filename;
        
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->download($path);
        }
        
        return redirect()->route('backup.index')
            ->with('error', 'File tidak ditemukan.');
    }

    public function destroy($filename)
    {
        $path = 'backups/' . $filename;
        
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
            return redirect()->route('backup.index')
                ->with('success', 'Backup berhasil dihapus!');
        }
        
        return redirect()->route('backup.index')
            ->with('error', 'File tidak ditemukan.');
    }
}
