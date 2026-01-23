<?php

namespace Database\Seeders;

use App\Models\DocumentTemplate;
use Illuminate\Database\Seeder;

class DocumentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'slug' => 'alienation_real_estate_notification',
                'title' => 'Notificação - Alienação Fiduciária de Imóvel',
                'description' => 'Modelo de notificação para alienação fiduciária de bem imóvel.',
            ],
            [
                'slug' => 'purchase_and_sale_notification',
                'title' => 'Notificação - Compromisso de Compra e Venda',
                'description' => 'Modelo de notificação para loteamentos e incorporações imobiliárias.',
            ],
            [
                'slug' => 'rectification_notification',
                'title' => 'Notificação - Retificação de Área',
                'description' => 'Modelo de notificação para retificação de área.',
            ],
            [
                'slug' => 'adjudication_notification',
                'title' => 'Notificação - Adjudicação Compulsória',
                'description' => 'Modelo de notificação para adjudicação compulsória extrajudicial',
            ],
            [
                'slug' => 'adverse_possession_notification_private',
                'title' => 'Notificação de Usucapião - Particulares',
                'description' => 'Modelo de notificação para proprietários e confrontantes particulares em usucapião extrajudicial.',
            ],
            [
                'slug' => 'adverse_possession_notification_public',
                'title' => 'Notificação de Usucapião - Entes Públicos',
                'description' => 'Modelo de notificação para União, Estados e Municípios em usucapião extrajudicial.',
            ],
            [
                'slug' => 'adverse_possession_edital',
                'title' => 'Edital de Notificação - Usucapião',
                'description' => 'Modelo de edital para notificação de usucapião extrajudicial.',
            ],
            [
                'slug' => 'certificate_standard',
                'title' => 'Certidão Padrão',
                'description' => 'Modelo de certidão padrão para conclusão de diligências de notificação realizadas com sucesso.',
            ],
            [
                'slug' => 'certificate_edital',
                'title' => 'Certidão Edital',
                'description' => 'Modelo de certidão para casos que exigiram publicação de edital por impossibilidade de notificação pessoal.',
            ],
            [
                'slug' => 'envelope',
                'title' => 'Envelope',
                'description' => 'Modelo de envelope para envio de notificações via carta com aviso de recebimento.',
            ],
        ];

        foreach ($templates as $template) {
            DocumentTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
