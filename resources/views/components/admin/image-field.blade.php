@props([
    'name' => 'image',
    'label' => 'Image',
    'current' => null,
    'help' => 'JPG, PNG ou WebP — 4 Mo maximum.',
])

@php
    $id = 'field-'.Str::slug($name);
    $preview = $current ? media($current) : null;
@endphp

<div class="space-y-2">
    <label for="{{ $id }}" class="block text-sm font-medium text-zinc-700">{{ $label }}</label>

    <div class="flex items-start gap-4">
        <div class="grid size-24 shrink-0 place-items-center overflow-hidden rounded-lg border border-zinc-200 bg-zinc-50">
            <img id="{{ $id }}-preview" src="{{ $preview }}" alt=""
                class="size-full object-cover {{ $preview ? '' : 'hidden' }}">
            @unless ($preview)
                <x-admin.icon name="photo" class="size-7 text-zinc-300" />
            @endunless
        </div>

        <div class="min-w-0 flex-1 space-y-2">
            <input type="file" name="{{ $name }}" id="{{ $id }}" accept="image/*"
                data-preview="#{{ $id }}-preview"
                class="block w-full cursor-pointer rounded-lg border border-zinc-200 bg-white text-sm text-zinc-600 shadow-sm file:mr-3 file:cursor-pointer file:border-0 file:bg-zinc-100 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">

            <p class="text-xs text-zinc-500">{{ $help }}</p>

            @if ($current)
                <label class="inline-flex items-center gap-2 text-xs text-zinc-600">
                    <input type="checkbox" name="remove_{{ $name }}" value="1"
                        class="size-3.5 rounded border-zinc-300 text-rose-500 focus:ring-rose-200">
                    Retirer l'image actuelle
                </label>
            @endif

            @error($name)
                <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
