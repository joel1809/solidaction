@props(['edit' => null, 'destroy' => null, 'view' => null, 'preview' => null, 'confirm' => 'Confirmez-vous la suppression ? Cette action est définitive.'])

<div class="flex items-center justify-end gap-1">
    @if ($preview)
        <a href="{{ $preview }}" target="_blank" rel="noopener" title="Voir sur le site"
            class="grid size-8 place-items-center rounded-lg text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-700">
            <x-admin.icon name="eye" class="size-4" />
        </a>
    @endif

    @if ($view)
        <a href="{{ $view }}" title="Détails"
            class="grid size-8 place-items-center rounded-lg text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-700">
            <x-admin.icon name="eye" class="size-4" />
        </a>
    @endif

    @if ($edit)
        <a href="{{ $edit }}" title="Modifier"
            class="grid size-8 place-items-center rounded-lg text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-700">
            <x-admin.icon name="pencil" class="size-4" />
        </a>
    @endif

    @if ($destroy)
        <x-admin.delete-form :action="$destroy" :confirm="$confirm" compact />
    @endif
</div>
