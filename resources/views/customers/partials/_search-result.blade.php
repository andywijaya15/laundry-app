@if($customers->count() > 0)
    @foreach($customers as $customer)
        <div class="search-result px-4 py-2 hover:bg-indigo-50 cursor-pointer border-b border-gray-100 last:border-0"
             data-id="{{ $customer->id }}"
             data-name="{{ $customer->name }}"
             data-phone="{{ $customer->phone }}"
             data-address="{{ $customer->address ?? '' }}">
            <div class="font-medium text-gray-900">{{ $customer->name }}</div>
            <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
        </div>
    @endforeach
@else
    <div class="px-4 py-3 text-center text-gray-500">
        Tidak ditemukan. <a href="{{ route('customers.create') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Tambah pelanggan baru?</a>
    </div>
@endif