@props(['title' => 'Aucun élément', 'message' => null])

<div class="px-6 py-16 text-center">
    <div class="mx-auto grid size-12 place-items-center rounded-full bg-zinc-100 text-zinc-400">
        <x-admin.icon name="inbox" class="size-6" />
    </div>
    <h3 class="mt-4 text-sm font-semibold text-zinc-900">{{ $title }}</h3>
    @if ($message)
        <p class="mx-auto mt-1 max-w-md text-sm text-zinc-500">{{ $message }}</p>
    @endif
    @if (trim($slot) !== '')
        <div class="mt-5 flex justify-center gap-2">{{ $slot }}</div>
    @endif
</div>
