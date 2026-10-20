@extends('layouts.admin')

@section('title', 'Carrousel')
@section('subtitle', 'Diapositives de la page d\'accueil')

@section('content')

    <x-admin.page-header title="Carrousel d'accueil" :subtitle="$sliders->total().' diapositive(s)'">
        <x-admin.button icon="plus" :href="route('admin.sliders.create')">Nouvelle diapositive</x-admin.button>
    </x-admin.page-header>

    @if ($sliders->isEmpty())
        <x-admin.card :padded="false">
            <x-admin.empty title="Aucune diapositive"
                message="Sans diapositive, la page d'accueil affiche un visuel par défaut.">
                <x-admin.button icon="plus" :href="route('admin.sliders.create')">Nouvelle diapositive</x-admin.button>
            </x-admin.empty>
        </x-admin.card>
    @else
        <div class="space-y-4">
            @foreach ($sliders as $slider)
                <div class="flex flex-wrap items-center gap-5 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                    <img src="{{ media($slider->image, 'theme/assets/images/slides/slide-v1-1.jpg') }}" alt=""
                        class="h-24 w-40 shrink-0 rounded-lg object-cover">

                    <div class="min-w-0 flex-1">
                        <p class="text-xs uppercase tracking-wide text-zinc-500">{{ $slider->subtitle }}</p>
                        <p class="mt-0.5 font-medium text-zinc-900">{{ str_replace("\n", ' ', $slider->title) }}</p>
                        <p class="mt-0.5 text-sm text-zinc-500">{{ Str::limit($slider->text, 90) }}</p>
                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-zinc-500">
                            @if ($slider->button_text)
                                <span class="rounded bg-zinc-100 px-2 py-1">{{ $slider->button_text }} → {{ $slider->button_url ?: '—' }}</span>
                            @endif
                            @if ($slider->button2_text)
                                <span class="rounded bg-zinc-100 px-2 py-1">{{ $slider->button2_text }} → {{ $slider->button2_url ?: '—' }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-xs text-zinc-400">Ordre {{ $slider->sort_order }}</span>
                        <x-admin.badge :color="$slider->is_active ? 'green' : 'zinc'">
                            {{ $slider->is_active ? 'Active' : 'Masquée' }}
                        </x-admin.badge>
                        <x-admin.row-actions :edit="route('admin.sliders.edit', $slider)"
                            :destroy="route('admin.sliders.destroy', $slider)" />
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <x-admin.pagination :paginator="$sliders" label="diapositive" />

@endsection
