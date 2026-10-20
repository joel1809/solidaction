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
            ['Mon don est-il déductible des impôts ?', 'Oui. En France, votre don ouvre droit à une réduction d\'impôt de 66 % de son montant, dans la limite de 20 % de votre revenu imposable. Le reçu fiscal vous est adressé par e-mail au mois de février suivant votre don.', 'Dons'],
            ['Puis-je choisir le projet que je finance ?', 'Absolument. Au moment du don, sélectionnez la cause de votre choix : votre contribution lui sera affectée et vous recevrez son bilan de clôture. Si vous ne choisissez rien, le don alimente le fonds d\'urgence.', 'Dons'],
            ['Comment arrêter ou modifier un don mensuel ?', 'Écrivez-nous à dons@solidaction.org ou appelez le +33 1 84 80 12 34 : la modification est effective sous 72 heures, sans justification ni frais.', 'Dons'],
            ['Comment devenir bénévole ?', 'Remplissez le formulaire « Devenir bénévole » : notre coordinatrice vous contacte sous une semaine pour un entretien, puis vous participez à une journée de formation avant votre première mission.', 'Bénévolat'],
            ['Faut-il des compétences particulières ?', 'Non. Nous avons besoin de profils très variés : logistique, traduction, comptabilité, animation, santé, communication. La motivation et la régularité comptent davantage que le diplôme.', 'Bénévolat'],
            ['Peut-on partir en mission à l\'étranger ?', 'Nos missions de terrain sont réservées aux bénévoles engagés depuis au moins un an et formés à la sécurité. Nous privilégions toujours le recrutement d\'équipes locales.', 'Bénévolat'],
            ['Comment vérifier votre transparence financière ?', 'Nos comptes sont certifiés chaque année par un commissaire aux comptes indépendant et publiés intégralement sur le site. Nous répondons à toute demande de précision par écrit.', 'Association'],
            ['Dans quels pays intervenez-vous ?', 'Nous intervenons dans 14 pays, principalement en Afrique de l\'Ouest, au Sahel, en Afrique australe et à Madagascar, toujours via une association partenaire enregistrée localement.', 'Association'],
            ['Une entreprise peut-elle devenir mécène ?', 'Oui, à partir de 5 000 € par an. Nous proposons un partenariat sur mesure avec reporting dédié, implication des salariés et réduction d\'impôt de 60 % du montant versé.', 'Association'],
        ];

        foreach ($faqs as $index => [$question, $answer, $category]) {
            Faq::updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'category' => $category, 'sort_order' => $index, 'is_active' => true]
            );
        }
    }
}
