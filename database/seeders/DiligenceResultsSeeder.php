<?php

namespace Database\Seeders;

use App\Models\DiligenceResult;
use Illuminate\Database\Seeder;

class DiligenceResultsSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'Devedor Presente - Notificação Realizada Com Sucesso' => [
                'devedor_presente_assinou' => '[Devedor Presente e Assinou], encontramos o devedor. Ele recebeu a notificação e lançou sua assinatura em termo próprio, cuja cópia permanece nesta Serventia.',
                'procurador_presente_assinou' => '[Procurador Assinou. Procuração Com Poderes Formais], encontramos o procurador do(s) devedores, senhor [Fulano de Tal]. Ele recebeu, em nome dos devedores, a notificação e lançou sua assinatura em termo próprio de ciência, cuja cópia permanece nesta Serventia. Nos foi apresentada procuração lavrada no [identificação do tabelionato, livro, folha e data da procuração], em que o(s) devedor(es) lhe passam poder de representação.',
                'recusa_assinar_fica_copia' => '[Recusa a Assinar, mas Fica com Cópia], encontramos o(s) devedores. Ele(s) receberam a notificação, mas se recusaram a assinar o termo próprio de ciência. Foram esclarecidos de que a falta de assinatura não impede a concretização da notificação e que teriam o prazo legal para purgar a mora, independentemente da assinatura ou não do termo de ciência.',
                'recusa_assinar_sem_copia' => '[Recusa a Assinar e NÃO fica com Cópia], encontramos o(s) devedores. Ele(s) se recusaram a receber a notificação e a assinar o termo próprio de ciência. Foram esclarecidos de que a falta de assinatura não impede a concretização da notificação e que teriam o prazo legal para purgar a mora, independentemente da assinatura ou não do termo de ciência. Foi esclarecido ainda que as partes poderiam comparecer ao Cartório de Registro de Imóveis caso desejassem tomar conhecimento total dos termos da notificação.',
                'recusa_assinar_extremamente_nervoso' => 'Devedor se Recusa a Assinar e a Ficar com Cópia. Extremamente Nervoso.',
                'impossibilidade_fisica' => '[Impossibilidade Física de Assinar. Mentalmente Bem], encontramos o devedor. A parte estava impossibilitada fisicamente de assinar o termo de ciência (Mão Quebrada), mas manifestou sua ciência de forma verbal e o cartório ora certifica o ocorrido.',
                'analfabeto' => '[Devedor Presente. Analfabeto], encontramos o devedor. A parte estava impossibilitada de assinar por não saber fazê-lo (analfabeto). A parte manifestou sua ciência de forma verbal e foi orientado a, em caso de dúvidas, comparecer ao Cartório para esclarecimentos.',
                'compareceu_cartorio' => '[Compareceu ao Cartório e Assinou], compareceu a está Serventia em (data) às (horas), leu a intimação e lançou sua assinatura no termo de ciência, cuja cópia fica arquivada.',
                'impossibilidade_mental' => '[Impossibilidade Mental de Assinar], encontramos o devedor. A parte estava impossibilitado de assinar por não estar em plena capacidade mental para o fazer (estava totalmente embriagado, por exemplo). Assim, deixamos de o notificar para evitar prejuízos para a parte.',
            ],
            'Devedor Ausente. Sem Qualquer Contato.' => [
                'ausente_vizinhos_confirmam' => '[Mora no Local, mas não está], não logramos êxito em encontrar o(s) interessado(s). Fomos informados pelo vizinho que o notificado mora no local, só não se encontrava no local no momento.',
                'ausente_vizinhos_desconhecem' => '[Vizinhos Desconhecem o Notificado], não logramos êxito em encontrar o(s) interessado(s). Ao colher informações, os vizinhos informaram que desconhecem os devedores.',
                'ausente_vizinhos_nao_atendem' => '[Vizinhos não atendem], não logramos êxito em encontrar o(s) interessado(s). Não foi possível colher maiores informações com os vizinhos, já que não atenderam ao chamado.',
                'mudou_endereco_conhecido' => '[Mudou-se. Novo Endereço Conhecido], não logramos êxito em encontrar o interessado. Ao buscarmos informações, os vizinhos informaram que o Devedor se mudou do imóvel. O senhor (Nome do Locatário), vizinho, nos informou que os devedores residem em (Endereço). Nos foi fornecido o contato telefônico (62) 99999-9999).',
                'mudou_endereco_desconhecido' => '[Mudou-se. Endereço Desconhecido], não logramos êxito em encontrar o interessado. Ao buscarmos informações, os vizinhos informaram que o Devedor se mudou do imóvel. Não souberam falar o novo endereço nem tinham contato telefônico das partes.',
                'profissao_dificil_localizacao' => '[Profissão de Difícil Localização], não logramos êxito em encontrar o interessado. As pessoas que atenderam o notificador (vinculo de parentesco com o devedor) afirmaram que o Devedor exerce a profissão de (profissão), sendo difícil determinar quando estará no local para ser intimado. Quando perguntados sobre a possibilidade de nos fornecer o contato telefônico do Devedor, os moradores da casa se negaram a fazer.',
                'falecimento' => '[O Devedor é Falecido], não logramos êxito em encontrar o interessado devido ao seu falecimento. Perguntado sobre a existência de eventual inventariante, as pessoas não souberam indicar. Em face do exposto, demos por encerrado o procedimento de notificação até que o requerente indique o inventariante e seu endereço para novas intimações.',
            ],
            'Devedor Ausente. Contato Realizado (por Parente ou Telefone)' => [
                'parente_informa_horario' => 'Parente Fala dia e Horário para o Encontrar.',
                'contato_telefonico_cartorio' => 'Contato Telefônico + Cartório. Foi Feito Contato Telefônico com a Parte, que Marcou de ir ao Cartório Assinar a Notificação.',
                'contato_telefonico_horario' => 'Foi Feito Contato Telefônico com a Parte, que Marcou Dia e Horário para Encontrar com o Notificador.',
                'contato_telefonico_novo_endereco' => 'Contato Telefônico + Novo Endereço. Em Contato Telefônico, o Notificado Solicita que Leve a Notificação Para Ele em Outro Endereço, mas, ao Chegar ao Endereço Indicado, Não Foi Possível Encontrá-lo, Não Havendo Mais Contato Telefônico.',
            ],
            'Imóvel Ocupado por Locatário ou Terceira Pessoa' => [
                'locatario_conhece' => '[Locatário Conhece o Devedor, Não Conhece Novo Endereço], fomos recebidos por (Nome do Locatário), locatário do imóvel. Ele nos informou que os devedores não residem no local e que não possui o endereço deles.',
                'locatario_desconhece' => '[Locatário ou Ocupante Desconhece o Devedor], fomos recebidos por (Nome do Locatário), locatário do imóvel. Ele nos informou que não conhece o notificado.',
            ],
            'Imóvel Desocupado' => [
                'lote_sem_construcao' => 'Lote Sem Construção: O Endereço de Entrega das Notificações é um Lote sem Construção.',
                'imovel_abandonado' => 'Imóvel Manifestamente Abandonado. Pela situação do local, manifestamente não mora ninguém faz algum tempo.',
                'imovel_em_construcao' => 'Imóvel Ainda em Construção. Ninguém mora do local porque ainda está em construção.',
                'empresa_fechada' => 'Empresa Fechada de Forma Permanente. O endereço foi encontrado, mas a empresa estava fechada de forma permanente. Os Vizinhos afirmam que a empresa fechou e não mais opera no endereço indicado.',
            ],
            'Imóvel Não Localizado' => [
                'fazenda_nao_localizada' => '[Fazenda Não Localizada], não logramos êxito em encontrar o interessado. Os dados fornecidos do imóvel rural não foram suficientes para localizar a sede da gleba rural tratada, tornando a intimação impossível de ser feita.',
                'endereco_incorreto' => '[Endereço Incorreto ou Incompleto], não logramos êxito em encontrar o interessado. O endereço fornecido foi insuficiente para localizar o imóvel no mundo real, torando impossível a localização do Devedor.',
                'endereco_inacessivel' => '[Endereço Inacessível], não conseguimos acessar o local. A localização é conhecida, mas o acesso ao local estava impossibilitado devido a (descrever a impossibilidade).',
            ],
            'Retificação de Área' => [
                'retificacao_invasao' => 'Retificação de Área. Invasão de Área: O notificado se recusou a assinar e disse que não vai assinar porque o vizinho está invadindo a área de propriedade dela (Retificação de Área)',
                'retificacao_sem_titulo' => '[Comprador Sem Título Registrado], não logramos êxito em encontrar o confrontante. No local, o senhor (Fulano de Tal) indicou ser o atual proprietário do imóvel. Afirmou que apenas não registrou seu título. Mostrou ao notificado o contrato que justifica sua posse sobre o imóvel. Deixamos cópia dos documentos com ele e afirmamos que ele poderia comparecer ao Cartório e impugnar, caso julgue ter algum interesse sendo desrespeitado. Ao ser indagado sobre o endereço do proprietário tabular, afirmou não saber.',
            ],
            'Outros' => [
                'outros_placeholder' => 'Adicione opções personalizadas neste grupo conforme necessário.',
            ],
        ];

        $groupOrder = 0;
        foreach ($groups as $group => $items) {
            $itemOrder = 0;
            foreach ($items as $code => $description) {
                DiligenceResult::updateOrCreate(
                    ['code' => $code],
                    [
                        'group' => $group,
                        'description' => $description,
                        'original_description' => $description,
                        'order' => $groupOrder * 100 + $itemOrder,
                        'active' => true,
                        'is_custom' => false,
                    ]
                );
                $itemOrder++;
            }
            $groupOrder++;
        }
    }
}
