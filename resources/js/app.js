/**
 * Interactions de l'interface d'administration.
 * Volontairement sans dépendance : tout repose sur des attributs data-*.
 */

document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initDropdowns();
    initConfirmForms();
    initImagePreviews();
    initSlugs();
    initBulkToggles();
    initChartTooltips();
});

/** Ouverture / fermeture de la barre latérale en mobile. */
function initSidebar() {
    const sidebar = document.getElementById('admin-sidebar');
    const backdrop = document.getElementById('admin-backdrop');

    if (!sidebar) {
        return;
    }

    const setOpen = (open) => {
        sidebar.classList.toggle('-translate-x-full', !open);
        backdrop?.classList.toggle('hidden', !open);
    };

    document.querySelectorAll('[data-sidebar-open]').forEach((button) => {
        button.addEventListener('click', () => setOpen(true));
    });

    document.querySelectorAll('[data-sidebar-close]').forEach((button) => {
        button.addEventListener('click', () => setOpen(false));
    });

    backdrop?.addEventListener('click', () => setOpen(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });
}

/** Menus déroulants (profil, actions de ligne). */
function initDropdowns() {
    const closeAll = (except = null) => {
        document.querySelectorAll('[data-dropdown-menu]').forEach((menu) => {
            if (menu !== except) {
                menu.classList.add('hidden');
            }
        });
    };

    document.querySelectorAll('[data-dropdown]').forEach((wrapper) => {
        const trigger = wrapper.querySelector('[data-dropdown-trigger]');
        const menu = wrapper.querySelector('[data-dropdown-menu]');

        if (!trigger || !menu) {
            return;
        }

        trigger.addEventListener('click', (event) => {
            event.stopPropagation();
            const willOpen = menu.classList.contains('hidden');
            closeAll(menu);
            menu.classList.toggle('hidden', !willOpen);
        });
    });

    document.addEventListener('click', () => closeAll());
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAll();
        }
    });
}

/** Confirmation avant les suppressions. */
function initConfirmForms() {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
}

/** Aperçu immédiat des images choisies. */
function initImagePreviews() {
    document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
        input.addEventListener('change', () => {
            const target = document.querySelector(input.dataset.preview);
            const file = input.files?.[0];

            if (!target || !file) {
                return;
            }

            target.src = URL.createObjectURL(file);
            target.classList.remove('hidden');
        });
    });
}

/** Génération automatique du slug depuis le titre (création uniquement). */
function initSlugs() {
    document.querySelectorAll('[data-slug-source]').forEach((source) => {
        const target = document.querySelector(source.dataset.slugSource);

        if (!target || target.value !== '') {
            return;
        }

        source.addEventListener('input', () => {
            if (target.dataset.touched === 'true') {
                return;
            }

            target.value = source.value
                .normalize('NFD')
                .replace(/[̀-ͯ]/g, '')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        });

        target.addEventListener('input', () => {
            target.dataset.touched = 'true';
        });
    });
}

/** Infobulle de survol des graphiques (une seule pour toute la page). */
function initChartTooltips() {
    const marks = document.querySelectorAll('[data-tip]');

    if (marks.length === 0) {
        return;
    }

    const tooltip = document.createElement('div');
    tooltip.className =
        'pointer-events-none fixed z-50 hidden rounded-lg bg-zinc-900 px-2.5 py-1.5 text-xs font-medium text-white shadow-lg';
    document.body.appendChild(tooltip);

    const place = (event) => {
        const offset = 14;
        const { width, height } = tooltip.getBoundingClientRect();
        const left = Math.min(Math.max(8, event.clientX - width / 2), window.innerWidth - width - 8);
        const top = event.clientY - height - offset;

        tooltip.style.left = `${left}px`;
        tooltip.style.top = `${top < 8 ? event.clientY + offset : top}px`;
    };

    marks.forEach((mark) => {
        mark.addEventListener('mouseenter', (event) => {
            tooltip.textContent = mark.dataset.tip;
            tooltip.classList.remove('hidden');
            place(event);
        });

        mark.addEventListener('mousemove', place);

        mark.addEventListener('mouseleave', () => {
            tooltip.classList.add('hidden');
        });
    });
}

/** Cases à cocher « tout sélectionner » dans les tableaux. */
function initBulkToggles() {
    document.querySelectorAll('[data-check-all]').forEach((master) => {
        const boxes = document.querySelectorAll(master.dataset.checkAll);

        master.addEventListener('change', () => {
            boxes.forEach((box) => {
                box.checked = master.checked;
            });
        });
    });
}
