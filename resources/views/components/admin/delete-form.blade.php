@props(['action', 'label' => 'Supprimer', 'confirm' => 'Confirmez-vous la suppression ? Cette action est définitive.', 'compact' => false])

<form action="{{ $action }}" method="POST" data-confirm="{{ $confirm }}" class="inline">
    @csrf
    @method('DELETE')
    @if ($compact)
        <button type="submit" title="{{ $label }}"
            class="grid size-8 place-items-center rounded-lg text-zinc-400 transition hover:bg-rose-50 hover:text-rose-600">
            <x-admin.icon name="trash" class="size-4" />
        </button>
    @else
        <x-admin.button type="submit" variant="danger" icon="trash">{{ $label }}</x-admin.button>
    @endif
</form>
