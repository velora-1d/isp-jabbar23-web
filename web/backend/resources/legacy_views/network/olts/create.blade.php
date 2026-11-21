@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-white">Add New OLT</h2>
                    <a href="{{ route('network.olts.index') }}" class="text-gray-400 hover:text-white">
                        &larr; Back
                    </a>
                </div>

                <form action="{{ route('network.olts.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">OLT Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100"
                                    placeholder="e.g. OLT-Pusat-01">
                                @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">IP Address (Management)</label>
                                <input type="text" name="ip_address" value="{{ old('ip_address') }}" 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100"
                                    placeholder="e.g. 192.168.100.1">
                                @error('ip_address') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Brand / Vendor</label>
                                <input type="text" name="brand" value="{{ old('brand') }}" 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100"
                                    placeholder="e.g. Huawei, ZTE">
                            </div>
                            
                            <!-- Server Profile -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Server Profile</label>
                                <select name="server_profile" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="zte">Real Device (ZTE)</option>
                                    <option value="huawei">Real Device (Huawei)</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">Port (Telnet)</label>
                                    <input type="number" name="port" value="{{ old('port', 23) }}" 
                                        class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300">SNMP Community</label>
                                    <input type="text" name="community" value="{{ old('community') }}" 
                                        class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100"
                                        placeholder="public">
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Username (Telnet)</label>
                                <input type="text" name="username" value="{{ old('username') }}" 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Password (Telnet)</label>
                                <input type="password" name="password" value="{{ old('password') }}" 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Type</label>
                                <select name="type" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="EPON">EPON</option>
                                    <option value="GPON">GPON</option>
                                    <option value="XGPON">XGPON</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Total PON Ports</label>
                                <select name="total_pon_ports" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="4">4 Ports</option>
                                    <option value="8">8 Ports</option>
                                    <option value="16">16 Ports</option>
                                    <option value="32">32 Ports</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Status</label>
                                <select name="status" class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                                    <option value="active">Active</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Location</label>
                                <input type="text" name="location" value="{{ old('location') }}" 
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 rounded-md text-gray-100">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold">
                            Save OLT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
