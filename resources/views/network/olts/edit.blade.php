@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white">Edit OLT: {{ $olt->name }}</h2>
                    <a href="{{ route('network.olts.index') }}" class="text-gray-400 hover:text-white">
                        &larr; Back
                    </a>
                </div>

                <form action="{{ route('network.olts.update', $olt) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">OLT Name</label>
                                <input type="text" name="name" value="{{ old('name', $olt->name) }}" required 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">IP Address (Management)</label>
                                <input type="text" name="ip_address" value="{{ old('ip_address', $olt->ip_address) }}" 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                @error('ip_address') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Brand / Vendor</label>
                                <input type="text" name="brand" value="{{ old('brand', $olt->brand) }}" 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Type</label>
                                <select name="type" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="EPON" {{ $olt->type == 'EPON' ? 'selected' : '' }}>EPON</option>
                                    <option value="GPON" {{ $olt->type == 'GPON' ? 'selected' : '' }}>GPON</option>
                                    <option value="XGPON" {{ $olt->type == 'XGPON' ? 'selected' : '' }}>XGPON</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Total PON Ports</label>
                                <select name="total_pon_ports" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="4" {{ $olt->total_pon_ports == 4 ? 'selected' : '' }}>4 Ports</option>
                                    <option value="8" {{ $olt->total_pon_ports == 8 ? 'selected' : '' }}>8 Ports</option>
                                    <option value="16" {{ $olt->total_pon_ports == 16 ? 'selected' : '' }}>16 Ports</option>
                                    <option value="32" {{ $olt->total_pon_ports == 32 ? 'selected' : '' }}>32 Ports</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Status</label>
                                <select name="status" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="active" {{ $olt->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ $olt->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="offline" {{ $olt->status == 'offline' ? 'selected' : '' }}>Offline</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Location / Datacenter</label>
                                <textarea name="location" rows="3" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">{{ old('location', $olt->location) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold">
                            Update OLT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
