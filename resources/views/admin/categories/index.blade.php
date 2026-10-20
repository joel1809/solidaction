@extends('layouts.admin')

@section('title', 'Catégories')
@section('subtitle', 'Programmes des causes et rubriques des actualités')

@section('content')

    <x-admin.page-header title="Catégories" :subtitle="$categories->total().' catégorie(s)'">
        <x-admin.button icon="plus" :href="route('admin.categories.create')">Nouvelle catégorie</x-admin.button>
    </x-admin.page-header>

    <x-admin.filters :action="route('admin.categories.index')" :search="false" :selects="[
        'type' => ['label' => 'Type', 'options' => ['cause' => 'Programmes (causes)', 'post' => 'Rubriques (actualités)']],
    ]" />

    <x-admin.card :padded="false">
        @if ($categories->isEmpty())
            <x-admin.empty title="Aucune catégorie" message="Créez des programmes pour organiser vos causes.">
                <x-admin.button icon="plus" :href="route('admin.categories.create')">Nouvelle catégorie</x-admin.button>
            </x-admin.empty>
        @else
            <x-admin.table :head="['Nom', 'Type', 'Contenus' => 'center', 'Ordre' => 'center', 'Statut', 'Actions' => 'right']">
                @foreach ($categories as $category)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                                class="font-medium text-zinc-900 hover:underline">{{ $category->name }}</a>
                            <p class="text-xs text-zinc-500">/{{ $category->slug }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.badge :color="$category->type === 'cause' ? 'violet' : 'blue'">
                                {{ $category->type === 'cause' ? 'Programme' : 'Rubrique' }}
                            </x-admin.badge>
                        </td>
                        <td class="px-5 py-3 text-center tabular-nums text-zinc-600">
                            {{ $category->type === 'cause' ? $category->causes_count : $category->posts_count }}
                        </td>
                        <td class="px-5 py-3 text-center tabular-nums text-zinc-500">{{ $category->sort_order }}</td>
                        <td class="px-5 py-3">
                            <x-admin.badge :color="$category->is_active ? 'green' : 'zinc'">
                                {{ $category->is_active ? 'Active' : 'Masquée' }}
                            </x-admin.badge>
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.row-actions :edit="route('admin.categories.edit', $category)"
                                :destroy="route('admin.categories.destroy', $category)"
                                confirm="Supprimer « {{ $category->name }} » ? Les contenus associés deviendront non classés." />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$categories" label="catégorie" />

@endsection
