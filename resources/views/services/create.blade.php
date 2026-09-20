@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Layanan</h1>
        <a href="{{ route('services.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm mt-2 inline-block">← Kembali</a>
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

    <form method="POST" action="{{ route('services.store') }}" class="bg-white shadow rounded-lg p-6 space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   required maxlength="100">
        </div>

        <div>
            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
            <select id="unit" name="unit"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                <option value="">Pilih satuan</option>
                <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                <option value="item" {{ old('unit') === 'item' ? 'selected' : '' }}>Item</option>
            </select>
        </div>

        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
            <input type="number" id="price" name="price" value="{{ old('price') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   required min="0" step="100" max="99999999"
                   placeholder="Contoh: 5000">
            <p class="mt-1 text-sm text-gray-500">Dalam Rupiah, tanpa titik/koma</p>
        </div>

        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <span class="ml-2 text-sm text-gray-700">Aktif</span>
            </label>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('services.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan</button>
        </div>
    </form>
</div>
@endsection