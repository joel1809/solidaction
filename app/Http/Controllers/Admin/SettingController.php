<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use HandlesUploads;

    /**
     * Every editable setting, grouped the way the form is laid out.
     *
     * @var array<string, array<string, array{label: string, type: string, help?: string}>>
     */
    private const SCHEMA = [
        'general' => [
            'site_name' => ['label' => 'Nom du site', 'type' => 'text'],
            'site_tagline' => ['label' => 'Accroche', 'type' => 'text', 'help' => 'Affichée dans le titre des onglets.'],
            'site_description' => ['label' => 'Description (SEO)', 'type' => 'textarea'],
            'currency_symbol' => ['label' => 'Symbole monétaire', 'type' => 'text'],
            'stat_beneficiaries' => ['label' => 'Bénéficiaires accompagnés', 'type' => 'number', 'help' => 'Chiffre affiché dans les compteurs.'],
        ],
        'contact' => [
            'contact_email' => ['label' => 'E-mail principal', 'type' => 'email'],
            'contact_email_secondary' => ['label' => 'E-mail dons', 'type' => 'email'],
            'contact_phone' => ['label' => 'Téléphone accueil', 'type' => 'text'],
            'contact_phone_alt' => ['label' => 'Téléphone accueil (2e ligne)', 'type' => 'text', 'help' => 'Facultatif : affiché sous le numéro d\'accueil sur la page Contact.'],
            'contact_address' => ['label' => 'Adresse postale', 'type' => 'textarea'],
            'contact_hours' => ['label' => 'Horaires', 'type' => 'text'],
            'contact_map' => ['label' => 'URL de la carte intégrée', 'type' => 'text', 'help' => 'Lien « embed » OpenStreetMap ou Google Maps.'],
        ],
        'social' => [
            'social_facebook' => ['label' => 'Facebook', 'type' => 'text'],
            'social_twitter' => ['label' => 'Twitter / X', 'type' => 'text'],
            'social_instagram' => ['label' => 'Instagram', 'type' => 'text'],
            'social_linkedin' => ['label' => 'LinkedIn', 'type' => 'text'],
            'social_youtube' => ['label' => 'YouTube', 'type' => 'text'],
        ],
        'content' => [
            'about_title' => ['label' => 'Titre « Qui sommes-nous »', 'type' => 'text'],
            'about_text' => ['label' => 'Texte de présentation', 'type' => 'textarea'],
            'about_short' => ['label' => 'Présentation courte (panneau latéral)', 'type' => 'textarea'],
            'mission_title' => ['label' => 'Titre de la mission', 'type' => 'text'],
            'mission_text' => ['label' => 'Texte de la mission', 'type' => 'textarea'],
            'footer_text' => ['label' => 'Texte du pied de page', 'type' => 'textarea'],
            'intro_video' => ['label' => 'Vidéo de présentation', 'type' => 'text', 'help' => 'Lien YouTube ou Vimeo.'],
        ],
        'donation' => [
            'donation_amounts' => ['label' => 'Montants proposés', 'type' => 'text', 'help' => 'Séparés par des virgules, ex. : 2000,5000,10000,25000,50000'],
            'donation_orange_money' => ['label' => 'Numéro Orange Money', 'type' => 'text', 'help' => 'Affiché sur la page de don et dans les instructions de paiement.'],
            'donation_mtn_momo' => ['label' => 'Numéro MTN MoMo', 'type' => 'text'],
            'donation_moov_money' => ['label' => 'Numéro Moov Money', 'type' => 'text'],
            'donation_wave' => ['label' => 'Numéro Wave', 'type' => 'text'],
            'donation_bank_holder' => ['label' => 'Titulaire du compte', 'type' => 'text'],
            'donation_bank_name' => ['label' => 'Banque', 'type' => 'text'],
            'donation_iban' => ['label' => 'Numéro de compte (RIB / IBAN)', 'type' => 'text'],
            'donation_bic' => ['label' => 'BIC', 'type' => 'text', 'help' => 'Facultatif : laissé vide, il n\'est pas affiché.'],
            'donation_tax_notice' => ['label' => 'Mention « Reçu de don »', 'type' => 'textarea'],
        ],
    ];

    /** Logos gérés comme des fichiers. */
    private const LOGOS = [
        'site_logo' => 'Logo principal (en-tête)',
        'site_logo_sticky' => 'Logo compact (en-tête fixe)',
        'site_logo_mobile' => 'Logo du menu mobile',
        'site_logo_footer' => 'Logo du pied de page',
    ];

    /** Image affichée lors du partage d'une page sur les réseaux sociaux. */
    private const SHARE_IMAGE = 'og_image';

    public function edit()
    {
        return view('admin.settings.edit', [
            'schema' => self::SCHEMA,
            'logos' => self::LOGOS,
            'shareImage' => self::SHARE_IMAGE,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];

        foreach (self::SCHEMA as $fields) {
            foreach ($fields as $key => $field) {
                $rules[$key] = match ($field['type']) {
                    'email' => ['nullable', 'email', 'max:160'],
                    'number' => ['nullable', 'numeric'],
                    'textarea' => ['nullable', 'string', 'max:4000'],
                    default => ['nullable', 'string', 'max:500'],
                };
            }
        }

        foreach (array_keys(self::LOGOS) as $key) {
            $rules[$key] = ['nullable', 'image', 'max:2048'];
        }

        $rules[self::SHARE_IMAGE] = ['nullable', 'image', 'max:4096'];

        $data = $request->validate($rules);

        foreach (self::SCHEMA as $group => $fields) {
            foreach ($fields as $key => $field) {
                Setting::put($key, $data[$key] ?? null, $group, $field['type']);
            }
        }

        foreach (array_keys(self::LOGOS) as $key) {
            $path = $this->uploadedImage($request, $key, 'branding', Setting::read($key));
            Setting::put($key, $path, 'general', 'image');
        }

        $path = $this->uploadedImage($request, self::SHARE_IMAGE, 'branding', Setting::read(self::SHARE_IMAGE));
        Setting::put(self::SHARE_IMAGE, $path, 'general', 'image');

        return back()->with('success', 'Les paramètres du site ont été enregistrés.');
    }
}
