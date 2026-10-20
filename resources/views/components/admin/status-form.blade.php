@props(['action', 'options' => [], 'current' => null, 'name' => 'status'])

<form action="{{ $action }}" method="POST" class="inline-flex">
    @csrf
    @method('PATCH')
    <select name="{{ $name }}" onchange="this.form.submit()"
        class="rounded-lg border border-zinc-200 bg-white py-1.5 pl-2.5 pr-7 text-xs font-medium text-zinc-700 shadow-sm focus:border-zinc-400 focus:outline-none">
        @foreach ($options as $value => $label)
            <option value="{{ $value }}" @selected($current === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <noscript><button type="submit" class="ml-1 text-xs underline">OK</button></noscript>
</form>
