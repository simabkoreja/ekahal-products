<div class="space-y-6">
    <div>
        <x-input-label for="title" :value="__('Title')" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $product->title ?? '')" required maxlength="180" />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description (Rich Text HTML allowed)')" />
        <textarea id="description" name="description" rows="8" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description', $product->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="price" :value="__('Price')" />
        <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price', isset($product) ? number_format((float) $product->price, 2, '.', '') : '')" required />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="date_available" :value="__('Date Available')" />
        <x-text-input id="date_available" name="date_available" type="date" class="mt-1 block w-full" :value="old('date_available', isset($product) ? $product->date_available->format('Y-m-d') : '')" required />
        <x-input-error :messages="$errors->get('date_available')" class="mt-2" />
    </div>
</div>
