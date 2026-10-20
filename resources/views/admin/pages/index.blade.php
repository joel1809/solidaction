@extends('layouts.admin')

@section('title', 'Pages')
@section('subtitle', 'Mentions légales, confidentialité, transparence…')

@section('content')

    <x-admin.page-header title="Pages institutionnelles" :subtitle="$pages->total().' page(s)'">
        <x-admin.button icon="plus" :href="route('admin.pages.create')">Nouvelle page</x-admin.button>
    </x-admin.page-header>

    <x-admin.card :padded="false">
        @if ($pages->isEmpty())
            <x-admin.empty title="Aucune page" message="Créez vos mentions légales et votre politique de confidentialité.">
                <x-admin.button icon="plus" :href="route('admin.pages.create')">Nouvelle page</x-admin.button>
            </x-admin.empty>
        @else
            <x-admin.table :head="['Page', 'Adresse', 'Statut', 'Modifiée le', 'Actions' => 'right']">
                @foreach ($pages as $page)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.pages.edit', $page) }}"
                                class="font-medium text-zinc-900 hover:underline">{{ $page->title }}</a>
                            <p class="text-xs text-zinc-500">{{ Str::limit($page->meta_description, 80) }}</p>
                        </td>
                        <td class="px-5 py-3 font-mono text-xs text-zinc-600">/page/{{ $page->slug }}</td>
                        <td class="px-5 py-3">
                            <x-admin.badge :color="$page->is_active ? 'green' : 'zinc'">
                                {{ $page->is_active ? 'En ligne' : 'Hors ligne' }}
                            </x-admin.badge>
                        </td>
                        <td class="px-5 py-3 text-zinc-500">{{ $page->updated_at->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <x-admin.row-actions :preview="$page->is_active ? route('pages.show', $page) : null"
                                :edit="route('admin.pages.edit', $page)"
                                :destroy="route('admin.pages.destroy', $page)"
                                confirm="Supprimer la page « {{ $page->title }} » ?" />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$pages" label="page" />

@endsection
