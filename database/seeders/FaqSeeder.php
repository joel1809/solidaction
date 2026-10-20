<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['Comment mon don est-il utilisé ?', '85 % des sommes collectées financent directement les projets de terrain, 10 % le suivi et l\'évaluation indépendante, et 5 % les frais administratifs. La répartition exacte est publiée chaque année dans notre rapport d\'activité certifié.', 'Dons'],
            ['Puis-je donner par Mobile Money ?', 'Oui. Vous pouvez donner par Orange Money, MTN MoMo, Moov Money ou Wave, par carte bancaire ou par virement. Le numéro Mobile Money de l\'ONG est indiqué sur la page « Faire un don ».', 'Dons'],
            ['Vais-je recevoir un reçu pour mon don ?', 'Oui. Un reçu nominatif vous est envoyé par e-mail dès que votre don est validé. Les entreprises mécènes reçoivent en plus une attestation annuelle récapitulant leurs versements.', 'Dons'],
            ['Puis-je choisir le projet que je finance ?', 'Absolument. Au moment du don, sélectionnez la cause de votre choix : votre contribution lui sera affectée et vous recevrez son bilan de clôture. Si vous ne choisissez rien, le don alimente le fonds d\'urgence.', 'Dons'],
            ['Comment arrêter ou modifier un don mensuel ?', 'Écrivez-nous à dons@solidaction.org ou appelez le +225 27 22 41 18 30 : la modification est effective sous 72 heures, sans justification ni frais.', 'Dons'],
            ['Comment devenir bénévole ?', 'Remplissez le formulaire « Devenir bénévole » : notre coordinatrice vous contacte sous une semaine pour un entretien, puis vous participez à une journée de formation avant votre première mission.', 'Bénévolat'],
            ['Faut-il des compétences particulières ?', 'Non. Nous avons besoin de profils très variés : logistique, traduction, comptabilité, animation, santé, communication. La motivation et la régularité comptent davantage que le diplôme.', 'Bénévolat'],
            ['Peut-on partir en mission dans les régions ?', 'Nos missions de terrain hors d\'Abidjan sont réservées aux bénévoles engagés depuis au moins un an et formés à la sécurité. Nous privilégions toujours le recrutement d\'équipes issues des communautés concernées.', 'Bénévolat'],
            ['Comment vérifier votre transparence financière ?', 'Nos comptes sont certifiés chaque année par un commissaire aux comptes indépendant et publiés intégralement sur le site. Nous répondons à toute demande de précision par écrit.', 'Association'],
            ['Dans quelles régions intervenez-vous ?', 'Nous intervenons dans 14 régions de Côte d\'Ivoire, du district d\'Abidjan au Bounkani en passant par le Tonkpi, la Bagoué et le Gbêkê, toujours avec une association communautaire partenaire.', 'Association'],
            ['Une entreprise peut-elle devenir mécène ?', 'Oui, à partir de 3 000 000 FCFA par an. Nous proposons un partenariat sur mesure avec reporting dédié, implication des salariés et attestation annuelle de versements.', 'Association'],
        ];

        foreach ($faqs as $index => [$question, $answer, $category]) {
            Faq::updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'category' => $category, 'sort_order' => $index, 'is_active' => true]
            );
        }
    }
}
