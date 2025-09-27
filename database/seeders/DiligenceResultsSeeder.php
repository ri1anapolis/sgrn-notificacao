<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiligenceResultsSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            "Devedor Presente - Notificação Realizada Com Sucesso" => [
                "devedor_presente_assinou" => "O Devedor estava Presente e Assinou a Notificação.",
                "procurador_presente_assinou" => "O Devedor Está no Local por Procurador, que Assinou a Notificação. Procuração Com Poderes Formais.",
                "recusa_assinar_fica_copia" => "Devedor se Recusa a Assinar, mas Fica com Cópia.",
                "recusa_assinar_sem_copia" => "Devedor se Recusa a Assinar e a Ficar com Cópia.",
                "recusa_assinar_extremamente_nervoso" => "Devedor se Recusa a Assinar e a Ficar com Cópia. Extremamente Nervoso.",
                "impossibilidade_fisica" => "Impossibilidade Física de Assinar: O Devedor Está Presente, Mas Não Conseguiu Assinar o Documento por Problemas Físicos. Devedor Mentalmente Bem.",
                "analfabeto" => "O Devedor Está Presente, Mas Não Conseguiu Assinar o Documento por Não Saber Assinar (Analfabeto). Devedor Mentalmente Bem.",
                "compareceu_cartorio" => "Compareceu ao Cartório e Assinou.",
                "impossibilidade_mental" => "Impossibilidade Mental de Assinar: O Devedor Está Presente, Mas Não Conseguiu Assinar o Documento por Problemas Em Sua Capacidade Civil.",
            ],
            "Devedor Ausente. Sem Qualquer Contato." => [
                "ausente_vizinhos_confirmam" => "Vizinhos Afirmam que a Parte Mora do Local, Só Não Está Presente No Local no Horário.",
                "ausente_vizinhos_desconhecem" => "Vizinhos Desconhecem o Notificado.",
                "ausente_vizinhos_nao_atendem" => "Não Foi Possível Conversar com Vizinhos para Saber o Paradeiro do Notificado. Os Vizinhos não atendem.",
                "mudou_endereco_conhecido" => "Mudou-se. Novo Endereço Conhecido: o Ocupante do Imóvel ou Vizinho Falou que o Devedor Mudou-se para Outro Endereço e Forneceu o Endereço Novo.",
                "mudou_endereco_desconhecido" => "Mudou-se. Endereço Desconhecido: o Ocupante do Imóvel ou Vizinho Falou que o Devedor Mudou-se para Outro Endereço e Não Conseguiu Identificar o Novo Endereço.",
                "profissao_dificil_localizacao" => "Profissão de Difícil Localização: O Devedor Exerce Profissão Que Viaja Demais, não sendo possível o encontrar em casa.",
                "falecimento" => "Falecimento: O Devedor é Falecido. Voltar ao Cartório para a Parte interessada refazer requerimento e indicar o inventariante e endereço para notificação.",
            ],
            "Devedor Ausente. Contato Realizado (por Parente ou Telefone)" => [
                "parente_informa_horario" => "Parente Fala dia e Horário para o Encontrar.",
                "contato_telefonico_cartorio" => "Contato Telefônico + Cartório. Foi Feito Contato Telefônico com a Parte, que Marcou de ir ao Cartório Assinar a Notificação.",
                "contato_telefonico_horario" => "Foi Feito Contato Telefônico com a Parte, que Marcou Dia e Horário para Encontrar com o Notificador.",
                "contato_telefonico_novo_endereco" => "Contato Telefônico + Novo Endereço. Em Contato Telefônico, o Notificado Solicita que Leve a Notificação Para Ele em Outro Endereço, mas, ao Chegar ao Endereço Indicado, Não Foi Possível Encontrá-lo, Não Havendo Mais Contato Telefônico.",
            ],
            "Imóvel Ocupado por Locatário ou Terceira Pessoa" => [
                "locatario_conhece" => "Locatário ou Ocupante Conhece o Devedor, Mas não Tem seu Contato.",
                "locatario_desconhece" => "Locatário ou Ocupante Desconhece o Devedor.",
            ],
            "Imóvel Desocupado" => [
                "lote_sem_construcao" => "Lote Sem Construção: O Endereço de Entrega das Notificações é um Lote sem Construção. Não",
                "imovel_abandonado" => "Imóvel Manifestamente Abandonado. Pela situação do local, manifestamente não mora ninguém faz algum tempo.",
                "imovel_em_construcao" => "Imóvel Ainda em Construção. Ninguém mora do local porque ainda está em construção.",
                "empresa_fechada" => "Empresa Fechada de Forma Permanente. O endereço foi encontrado, mas a empresa estava fechada de forma permanente. Os Vizinhos afirmam que a empresa fechou e não mais opera no endereço indicado.",
            ],
            "Imóvel Não Localizado" => [
                "fazenda_nao_localizada" => "Fazenda Não Localizada: Não foi possível Achar a Fazenda.",
                "endereco_incorreto" => "Endereço Incorreto ou Incompleto: Não foi Possível Notificar Porque o Endereço Está Incorreto ou Incompleto.",
                "endereco_inacessivel" => "Endereço Inacessível.",
            ],
            "Retificação de Área" => [
                "retificacao_invasao" => "Retificação de Área. Invasão de Área: O notificado se recusou a assinar e disse que não vai assinar porque o vizinho está invadindo a área de propriedade dela (Retificação de Área)",
                "retificacao_sem_titulo" => "Retificação de Área: Comprador Sem Título Registrado:",
            ],
        ];


        foreach ($groups as $group => $items) {
            foreach ($items as $code => $description) {
                DB::table('diligence_results')->insert([
                    'group' => $group,
                    'code' => $code,
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
