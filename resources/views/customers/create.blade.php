@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Pelanggan</h1>
        <a href="{{ route('customers.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm mt-2 inline-block">← Kembali</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 px-4 py-3 rounded-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customers.store') }}" class="bg-white shadow rounded-lg p-6 space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   required maxlength="255">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. HP <span class="text-red-500">*</span></label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   required maxlength="20" placeholder="08xxxxxxxxxx">
            <p class="mt-1 text-sm text-gray-500">Contoh: 08123456789 atau +628123456789</p>
        </div>

        <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
            <textarea id="address" name="address" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                      maxlength="500">{{ old('address') }}</textarea>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('customers.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan</button>
        </div>
    </form>
</div>
@endsection