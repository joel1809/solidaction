@if (session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        <x-admin.icon name="check" class="mt-0.5 size-5 shrink-0" />
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
        <x-admin.icon name="question" class="mt-0.5 size-5 shrink-0" />
        <p>{{ session('error') }}</p>
    </div>
@endif

@if (request()->query('envoi') === 'trop-volumineux')
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
        <x-admin.icon name="question" class="mt-0.5 size-5 shrink-0" />
        <p>
            Les fichiers envoyés étaient trop volumineux (limite totale : {{ ini_get('post_max_size') }}o par envoi) :
            rien n'a été enregistré. Ajoutez moins de photos ou vidéos à la fois, ou utilisez des liens YouTube/Vimeo.
        </p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
        <p class="font-semibold">Le formulaire comporte {{ $errors->count() }} erreur(s) :</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
