<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Product') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('products.update', $product) }}" class="bg-white shadow rounded-lg p-6">
                @csrf
                @method('PUT')
                @include('products.partials.form')

                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('products.index') }}" class="px-4 py-2 border rounded-md">Cancel</a>
                    <x-primary-button>Update</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
