<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Product Details') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $product->title }}</h3>
                    <p class="text-sm text-gray-500">By {{ $product->user->name }}</p>
                </div>

                <div class="prose max-w-none text-gray-800">{!! $product->description !!}</div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="font-medium text-gray-900">${{ number_format((float) $product->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date Available</p>
                        <p class="font-medium text-gray-900">{{ $product->date_available->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('products.index') }}" class="text-indigo-600">Back to products</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
