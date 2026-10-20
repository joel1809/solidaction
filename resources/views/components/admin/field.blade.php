@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'help' => null,
    'options' => null,
    'placeholder' => null,
    'rows' => 6,
    'checked' => false,
    'model' => null,
    'slugTarget' => null,
])

@php
    $id = $attributes->get('id', 'field-'.Str::slug($name));
    $current = old($name, $value ?? ($model ? data_get($model, $name) : null));
    $hasError = $errors->has($name);
    $inputClasses = 'w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm transition placeholder:text-zinc-400 focus:outline-none focus:ring-2 '
        .($hasError ? 'border-rose-300 focus:border-rose-400 focus:ring-rose-100' : 'border-zinc-200 focus:border-zinc-400 focus:ring-zinc-100');
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1.5']) }}>
    @if ($label && $type !== 'checkbox')
        <label for="{{ $id }}" class="block text-sm font-medium text-zinc-700">
            {{ $label }}
            @if ($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif

    @switch($type)
        @case('textarea')
            <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}" @if ($required) required @endif
                placeholder="{{ $placeholder }}" class="{{ $inputClasses }}">{{ $current }}</textarea>
            @break

        @case('select')
            <select name="{{ $name }}" id="{{ $id }}" @if ($required) required @endif class="{{ $inputClasses }}">
                @if ($placeholder)
                    <option value="">{{ $placeholder }}</option>
                @endif
                @foreach ($options ?? [] as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>{{ $optionLabel }}</option>
                @endforeach
            </select>
            @break

        @case('checkbox')
            <label for="{{ $id }}" class="flex cursor-pointer items-start gap-3 rounded-lg border border-zinc-200 px-3.5 py-3">
                <input type="hidden" name="{{ $name }}" value="0">
                <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="1"
                    @checked((bool) old($name, $value ?? ($model ? data_get($model, $name) : $checked)))
                    class="mt-0.5 size-4 rounded border-zinc-300 text-rose-500 focus:ring-rose-200">
                <span>
                    <span class="block text-sm font-medium text-zinc-800">{{ $label }}</span>
                    @if ($help)
                        <span class="block text-xs text-zinc-500">{{ $help }}</span>
                    @endif
                </span>
            </label>
            @break

        @default
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $current }}"
                @if ($required) required @endif placeholder="{{ $placeholder }}"
                @if ($slugTarget) data-slug-source="{{ $slugTarget }}" @endif
                {{ $attributes->except(['class', 'id']) }} class="{{ $inputClasses }}">
    @endswitch

    @if ($help && $type !== 'checkbox')
        <p class="text-xs text-zinc-500">{{ $help }}</p>
    @endif

    @error($name)
        <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror
</div>
