# Solid’Action

Site institutionnel et tableau d'administration d'une association de solidarité,
construit sur **Laravel 12**. Le site public reprend intégralement le thème
HTML fourni (`public/theme`, thème « LoveIcon ») converti en gabarits Blade
pilotés par la base de données ; l'administration est une interface distincte
en Tailwind CSS 4.

---

## Démarrage

```bash
composer install
npm install

cp .env.example .env          # si .env n'existe pas encore
php artisan key:generate
php artisan migrate --seed    # crée le schéma et le jeu de démonstration
php artisan storage:link      # expose les images téléversées
npm run build                 # ou "npm run dev" en développement

php artisan serve
```

Le site est alors accessible sur <http://127.0.0.1:8000> et l'administration sur
<http://127.0.0.1:8000/admin>.

### Comptes de démonstration

| Rôle | Identifiant | Mot de passe |
|---|---|---|
| Administrateur | `admin@solidaction.org` | `password` |
| Éditeur | `redaction@solidaction.org` | `password` |

L'administrateur accède à tout ; l'éditeur gère les contenus, les dons et les
inscriptions mais pas les utilisateurs ni les paramètres du site.

> Les comptes créés par l'inscription publique reçoivent le rôle `visitor` et
> n'ont **aucun** accès à l'administration.

---

## Site public

| Adresse | Contenu |
|---|---|
| `/` | Carrousel, piliers d'action, cause à la une, collectes, compteurs, mission, équipe, événements, témoignages, actualités, partenaires |
| `/a-propos` | Présentation, chiffres clés, mission, équipe, FAQ, témoignages |
| `/causes` · `/causes/{slug}` | Liste filtrable par programme et par mot-clé, fiche détaillée avec jauge de collecte et derniers dons |
| `/faire-un-don` | Formulaire de don (montant libre ou prédéfini, affectation à une cause) |
| `/don/{reference}/merci` | Confirmation avec référence et instructions de virement |
| `/evenements` · `/evenements/{slug}` | Agenda à venir / passé, fiche détaillée et inscription avec contrôle des places |
| `/actualites` · `/actualites/{slug}` | Articles en grille ou en liste, recherche, catégories, mots-clés, commentaires modérés |
| `/equipe`, `/temoignages`, `/faq` | Pages de présentation |
| `/contact` | Formulaire de contact et coordonnées |
| `/devenir-benevole` | Candidature de bénévolat |
| `/page/{slug}` | Pages institutionnelles (mentions légales, confidentialité, transparence) |

Tous les textes, visuels et listes proviennent de la base : rien n'est codé en
dur dans les gabarits.

---

## Administration (`/admin`)

**Tableau de bord** — indicateurs (total collecté et variation sur 30 jours,
donateurs uniques, don moyen, causes, abonnés), histogramme des dons encaissés
sur douze mois, classement des causes les mieux financées, répartition par
statut de paiement, derniers dons, prochains événements et bandeau « à traiter ».

**Modules de gestion**

- **Collecte** — causes (objectif, échéance, mise à la une, image) ; dons
  (filtres par statut/cause/période, changement de statut en un clic,
  saisie manuelle, export CSV)
- **Contenus** — actualités, commentaires à modérer, catégories, pages
- **Mobilisation** — événements, inscriptions, candidatures de bénévoles
- **Vitrine** — carrousel d'accueil, équipe, témoignages, FAQ, partenaires
- **Relations** — messages de contact, abonnés à la newsletter (export CSV)
- **Système** — utilisateurs et rôles, paramètres du site

Le montant collecté d'une cause est recalculé automatiquement à chaque création,
modification ou suppression de don validé — il n'est jamais saisi à la main.

---

## Repères techniques

```
app/Http/Controllers/Site/     contrôleurs du site public
app/Http/Controllers/Admin/    contrôleurs d'administration
app/Http/Middleware/           EnsureUserIsStaff, ShareSiteData
app/Support/helpers.php        setting(), media(), theme_asset(), money()
resources/views/layouts/       site.blade.php (thème) et admin.blade.php (Tailwind)
resources/views/components/    composants réutilisables : site.* et admin.*
resources/views/site/          pages publiques
resources/views/admin/         écrans d'administration
database/seeders/              jeu de démonstration complet en français
public/theme/                  thème HTML d'origine, inchangé
public/css/site.css            compléments CSS du site public
```

Deux fonctions d'assistance structurent les gabarits :

- `setting('contact_email')` lit un réglage administrable (mis en cache) ;
- `media($chemin)` résout une image, qu'elle vienne du thème (`theme/…`), du
  disque public (téléversement) ou d'une URL absolue.

Le middleware `staff` protège `/admin` ; `staff:admin` restreint en plus les
utilisateurs et les paramètres aux administrateurs.

### Images

Les contenus de démonstration pointent vers les visuels du thème. Toute image
téléversée depuis l'administration part dans `storage/app/public/uploads/…` et
est servie via le lien symbolique `public/storage`. Les visuels du thème ne sont
jamais supprimés par l'administration.

### Tests

```bash
php artisan test
```

82 tests couvrent le rendu des pages publiques, les formulaires (don, inscription
à un événement, commentaire, contact, bénévolat, newsletter), les règles
d'accès à l'administration, le rendu de chaque écran, les opérations CRUD, les
téléversements et suppressions d'images, l'export CSV et les garde-fous sur les
comptes (dernier administrateur, auto-rétrogradation, inscription publique).

---

## Mise en ligne (hébergement mutualisé IONOS)

1. **Base** — importer l'export SQL dans la base IONOS via phpMyAdmin.
2. **Fichiers** — téléverser le projet (avec `vendor/` installé en `--no-dev`,
   `public/build/` compilé et `storage/app/public/uploads/`) par SFTP.
3. **Domaine** — faire pointer le domaine sur le dossier `public/` du projet
   (le `.htaccess` racine n'est qu'un filet de sécurité).
4. **`.env`** — `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` en https,
   `SESSION_SECURE_COOKIE=true`, identifiants de la base IONOS.
5. **SSH (facultatif)** — `php artisan storage:link` puis
   `php artisan config:cache && php artisan view:cache`. Sans lien symbolique,
   les images téléversées restent servies par Laravel (`serve` du disque public).

Les dossiers `storage/` et `bootstrap/cache/` doivent être accessibles en
écriture par PHP.

## Points d'attention avant mise en production

1. **Paiement** — les dons sont enregistrés comme promesses au statut
   « en attente » ; aucun prestataire de paiement n'est branché. L'encaissement
   est confirmé manuellement depuis l'administration.
2. **E-mails** — `MAIL_MAILER=log` par défaut : aucune notification ne part
   réellement. Configurez un transport SMTP pour les confirmations de don, les
   reçus fiscaux et les réponses aux messages.
3. **Secrets** — régénérez `APP_KEY`, passez `APP_DEBUG=false` et changez les
   mots de passe de démonstration.
4. **Contenu éditorial** — les textes, coordonnées, IBAN et mentions légales du
   jeu de démonstration sont fictifs et doivent être remplacés (Paramètres du
   site et module Pages).
