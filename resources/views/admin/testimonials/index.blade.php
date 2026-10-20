@extends('layouts.admin')

@section('title', 'Témoignages')
@section('subtitle', 'Paroles de donateurs, bénévoles et partenaires')

@section('content')

    <x-admin.page-header title="Témoignages" :subtitle="$testimonials->total().' témoignage(s)'">
        <x-admin.button icon="plus" :href="route('admin.testimonials.create')">Nouveau témoignage</x-admin.button>
    </x-admin.page-header>

    <x-admin.card :padded="false">
        @if ($testimonials->isEmpty())
            <x-admin.empty title="Aucun témoignage" message="Les témoignages renforcent la confiance des visiteurs.">
                <x-admin.button icon="plus" :href="route('admin.testimonials.create')">Nouveau témoignage</x-admin.button>
            </x-admin.empty>
        @else
            <x-admin.table :head="['Auteur', 'Témoignage', 'Ordre' => 'center', 'Statut', 'Actions' => 'right']">
                @foreach ($testimonials as $testimonial)
                    <tr class="hover:bg-zinc-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ media($testimonial->photo, 'theme/assets/images/testimonial/testimonial-v1-1.png') }}"
                                    alt="" class="size-10 shrink-0 rounded-full object-cover">
                                <div class="min-w-0">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                        class="block truncate font-medium text-zinc-900 hover:underline">{{ $testimonial->name }}</a>
                                    <p class="truncate text-xs text-zinc-500">{{ $testimonial->location }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-zinc-800">{{ $testimonial->title }}</p>
                            <p class="mt-0.5 text-xs text-zinc-500">{{ Str::limit($testimonial->content, 110) }}</p>
                        </td>
                        <td class="px-5 py-3 text-center tabular-nums text-zinc-500">{{ $testimonial->sort_order }}</td>
                        <td class="px-5 py-3">
                            <x-admin.badge :color="$testimonial->is_active ? 'green' : 'zinc'">
                                {{ $testimonial->is_active ? 'Publié' : 'Masqué' }}
                            </x-admin.badge>
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.row-actions :edit="route('admin.testimonials.edit', $testimonial)"
                                :destroy="route('admin.testimonials.destroy', $testimonial)" />
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-admin.card>

    <x-admin.pagination :paginator="$testimonials" label="témoignage" />

@endsection
