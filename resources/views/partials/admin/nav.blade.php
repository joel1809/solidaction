@php
    use App\Models\Comment;
    use App\Models\ContactMessage;
    use App\Models\Donation;
    use App\Models\Volunteer;

    $pendingDonations = Donation::where('status', 'pending')->count();
    $pendingComments = Comment::where('status', 'pending')->count();
    $pendingVolunteers = Volunteer::where('status', 'pending')->count();
    $newMessages = ContactMessage::unread()->count();

    $groups = [
        'Pilotage' => [
            ['label' => 'Tableau de bord', 'route' => 'admin.dashboard', 'icon' => 'grid'],
        ],
        'Collecte' => [
            ['label' => 'Causes', 'route' => 'admin.causes.index', 'icon' => 'heart'],
            ['label' => 'Dons', 'route' => 'admin.donations.index', 'icon' => 'banknotes', 'badge' => $pendingDonations],
        ],
        'Contenus' => [
            ['label' => 'Actualités', 'route' => 'admin.posts.index', 'icon' => 'news'],
            ['label' => 'Commentaires', 'route' => 'admin.comments.index', 'icon' => 'chat', 'badge' => $pendingComments],
            ['label' => 'Catégories', 'route' => 'admin.categories.index', 'icon' => 'tag'],
            ['label' => 'Pages', 'route' => 'admin.pages.index', 'icon' => 'document'],
        ],
        'Mobilisation' => [
            ['label' => 'Événements', 'route' => 'admin.events.index', 'icon' => 'calendar'],
            ['label' => 'Inscriptions', 'route' => 'admin.registrations.index', 'icon' => 'ticket'],
            ['label' => 'Bénévoles', 'route' => 'admin.volunteers.index', 'icon' => 'users', 'badge' => $pendingVolunteers],
        ],
        'Vitrine' => [
            ['label' => 'Carrousel', 'route' => 'admin.sliders.index', 'icon' => 'photo'],
            ['label' => 'Équipe', 'route' => 'admin.team.index', 'icon' => 'users'],
            ['label' => 'Témoignages', 'route' => 'admin.testimonials.index', 'icon' => 'quote'],
            ['label' => 'FAQ', 'route' => 'admin.faqs.index', 'icon' => 'question'],
            ['label' => 'Partenaires', 'route' => 'admin.partners.index', 'icon' => 'handshake'],
        ],
        'Relations' => [
            ['label' => 'Messages', 'route' => 'admin.messages.index', 'icon' => 'mail', 'badge' => $newMessages],
            ['label' => 'Abonnés', 'route' => 'admin.subscribers.index', 'icon' => 'inbox'],
        ],
    ];

    if (auth()->user()->isAdmin()) {
        $groups['Système'] = [
            ['label' => 'Utilisateurs', 'route' => 'admin.users.index', 'icon' => 'shield'],
            ['label' => 'Paramètres', 'route' => 'admin.settings.edit', 'icon' => 'cog'],
        ];
    }
@endphp

<nav class="px-3 py-5">
    @foreach ($groups as $group => $items)
        <p class="px-3 pb-2 pt-4 text-[11px] font-semibold uppercase tracking-wider text-white/55">{{ $group }}</p>
        <ul class="space-y-1">
            @foreach ($items as $item)
                @php
                    $base = Str::beforeLast($item['route'], '.');
                    $active = request()->routeIs($item['route']) || request()->routeIs($base.'.*');
                @endphp
                <li>
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition {{ $active ? 'bg-brand-yellow text-zinc-900' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <x-admin.icon :name="$item['icon']" class="size-[18px] shrink-0" />
                        <span class="flex-1 truncate">{{ $item['label'] }}</span>
                        @if (! empty($item['badge']))
                            <span class="rounded-full {{ $active ? 'bg-brand-green text-white' : 'bg-brand-yellow text-zinc-900' }} px-2 py-0.5 text-[11px] font-semibold">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    @endforeach
</nav>
