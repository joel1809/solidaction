@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('subtitle', 'Comptes ayant accès à l\'administration')

@section('content')

    <x-admin.page-header title="Utilisateurs" :subtitle="$users->total().' compte(s)'">
        <x-admin.button icon="plus" :href="route('admin.users.create')">Nouveau compte</x-admin.button>
    </x-admin.page-header>

    <x-admin.filters :action="route('admin.users.index')" placeholder="Nom ou e-mail…" />

    <x-admin.card :padded="false">
        <x-admin.table :head="['Utilisateur', 'Rôle', 'Articles' => 'center', 'Statut', 'Inscrit le', 'Actions' => 'right']">
            @foreach ($users as $user)
                <tr class="hover:bg-zinc-50">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            @if ($user->avatar)
                                <img src="{{ media($user->avatar) }}" alt="" class="size-10 shrink-0 rounded-full object-cover">
                            @else
                                <span class="grid size-10 shrink-0 place-items-center rounded-full bg-zinc-900 text-xs font-semibold text-white">
                                    {{ $user->initials() }}
                                </span>
                            @endif
                            <div class="min-w-0">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="block truncate font-medium text-zinc-900 hover:underline">
                                    {{ $user->name }}
                                    @if ($user->is(auth()->user()))
                                        <span class="text-xs font-normal text-zinc-500">(vous)</span>
                                    @endif
                                </a>
                                <p class="truncate text-xs text-zinc-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <x-admin.badge :color="$user->isAdmin() ? 'violet' : 'blue'">{{ $user->role_label }}</x-admin.badge>
                    </td>
                    <td class="px-5 py-3 text-center tabular-nums text-zinc-600">{{ $user->posts_count }}</td>
                    <td class="px-5 py-3">
                        <x-admin.badge :color="$user->is_active ? 'green' : 'red'">
                            {{ $user->is_active ? 'Actif' : 'Désactivé' }}
                        </x-admin.badge>
                    </td>
                    <td class="px-5 py-3 text-zinc-500">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <x-admin.row-actions :edit="route('admin.users.edit', $user)"
                            :destroy="$user->is(auth()->user()) ? null : route('admin.users.destroy', $user)"
                            confirm="Supprimer le compte de {{ $user->name }} ? Ses articles seront conservés sans auteur." />
                    </td>
                </tr>
            @endforeach
        </x-admin.table>
    </x-admin.card>

    <x-admin.pagination :paginator="$users" label="compte" />

@endsection
