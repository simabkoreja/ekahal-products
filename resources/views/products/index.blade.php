<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Products') }}</h2>
            <a href="{{ route('products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Create Product</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
            @endif

            <form method="GET" action="{{ route('products.index') }}" class="bg-white p-4 shadow rounded-lg">
                <x-input-label for="search" :value="__('Search by keyword')" />
                <div class="mt-2 flex gap-2">
                    <x-text-input id="search" name="search" type="text" class="block w-full" :value="$search" placeholder="Search title or description" />
                    <x-primary-button>Search</x-primary-button>
                </div>
            </form>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Available</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $product->title }}</td>
                                <td class="px-6 py-4 text-gray-700">${{ number_format((float) $product->price, 2) }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $product->date_available->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a class="text-indigo-600" href="{{ route('products.show', $product) }}">View</a>
                                    <a class="text-yellow-600" href="{{ route('products.edit', $product) }}">Edit</a>
                                    <form class="inline" method="POST" action="{{ route('products.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" onclick="return confirm('Delete this product?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-4 text-center text-gray-500" colspan="4">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
