# Mapeamento de Variáveis do Sistema

Este documento descreve todas as variáveis disponíveis para uso nos templates de documentos (Word), seguindo o formato: `${variável}: descrição -> 'exemplo de texto real'`.

---

## 1. Certidão (Certificate)

As certidões são geradas após a conclusão das diligências.

### 1.1 Variáveis Comuns (BaseCertificate)
Estas variáveis estão disponíveis tanto na Certidão Padrão quanto na de Edital.

**Dados do Protocolo e Datas:**
- `${protocol}`: Número do protocolo formatado -> '123.456'
- `${date_short}`: Data atual no formato DD/MM/AAAA -> '05/03/2026'
- `${date}`: Data atual por extenso -> '05 de março de 2026'

**Dados da Natureza e Imóvel:**
- `${nature}`: Nome da natureza do processo -> 'Alienação Fiduciária de Bem Imóvel'
- `${property_notified}`: Endereço do imóvel objeto da notificação -> 'RUA EXEMPLO, 123, BAIRRO, CIDADE/UF'
- `${property_registry}`: Número da matrícula ou registro -> '123.45'
- `${registry_place}`: Cartório de Registro onde o imóvel está registrado -> 'Cartório de Registro de Imóveis da Comarca de...'
- `${contract_number}`: Número do contrato associado -> '123456'
- `${act_registry}`: Ato de registro ou averbação do contrato -> 'R-01'

**Dados Financeiros:**
- `${value_debt}`: Valor total da dívida formatado -> 'R$ 10.000,00'
- `${debt_date}`: Data da posição da dívida -> '01/01/2026'
- `${creditor}`: Nome do credor (em maiúsculas) -> 'BANCO EXEMPLO'
- `${cnpj_number}`: CNPJ do credor -> '00.000.000/0001-00'

**Dados das Pessoas Notificadas:**
- `${people_list}`: Lista formatada dos nomes e CPFs das pessoas notificadas -> 'JOÃO DA SILVA, CPF n˚ 123.456.789-00 e MARIA DA SILVA, CPF n˚ 987.654.321-00'
- `${list_number_notified_people}`: Lista de números de telefone -> '(11) 99999-9999, (11) 88888-8888'
- `${list_email_notified_people}`: Lista de e-mails -> 'joao@email.com, maria@email.com'

**Concordância e Gênero (Exemplos Reais):**
- `${vocative}`: Vocativo adequado -> 'os senhores', 'as senhoras', 'o senhor' ou 'a senhora'
- `${verb_notify}`: Concordância do verbo purgar -> 'de os notificados purgarem', 'de o notificado purgar' ou 'de a notificada purgar'
- `${verb_debtors}`: Concordância para o estado dos devedores -> 'os devedores estão', 'as devedoras estão', 'o devedor está' ou 'a devedora está'
- `${verb_debtor_article}`: Artigo e substantivo para devedores -> 'os devedores', 'as devedoras', 'o devedor' ou 'a devedora'

**Histórico de Diligências:**
- `${visits_list}`: Relatório completo de visitas -> 'Em primeira visita ao RUA EXEMPLO, 123, realizada em 05/03/2026 às 10:00 [Resultado da Diligência] (Observação: ...)'

### 1.2 Certidões de Compromisso de Compra e Venda
#### 1.2.1 Compromisso de Compra e Venda (Incorporação)
- `${vocative}`: Artigo e vocativo adequado -> 'o senhor', 'a senhora', 'os senhores' ou 'as senhoras'
- `${verb_notify}`: Verbo purgar conjugado -> 'purgar' ou 'purgarem'
- `${verb_debtors}`: Artigo e substantivo para devedores -> 'o devedor', 'a devedora', 'os devedores' ou 'as devedoras'
- `${verb_to_be}`: Verbo ser/estar conjugado -> 'está' ou 'estão'
- `${debtor_article}`: Artigo preposicionado para devedores -> 'do devedor', 'da devedora', 'dos devedores' ou 'das devedoras'
- `${intimated_article}`: Artigo e substantivo para intimados -> 'o intimado', 'a intimada', 'os intimados' ou 'as intimadas'
- `${verb_intimate}`: Frase completa de intimação -> 'de o intimado purgar', 'de a intimada purgar', 'de os intimados purgarem' ou 'de as intimadas purgarem'

#### 1.2.3 Compromisso de Compra e Venda (Loteamento)
- `${debtor_article}`: Artigo preposicionado para devedores -> 'do devedor', 'da devedora', 'dos devedores' ou 'das devedoras'

---

### 1.3 Certidão Edital
Além das variáveis de **1.1**, inclui:

- `${digital_contacts_result}`: Resultado das tentativas de contato digital -> 'Tentou-se contato com JOÃO DA SILVA via WhatsApp e obteve o resultado: "Mensagem lida"'
- `${period_email}`: Período entre e-mail e edital -> '5 dias' ou '___ dias'
- `${edital_edition_1}`: Edição da primeira publicação -> '1234'
- `${edital_num_1}`: Número do primeiro edital -> '45/2026'
- `${edital_date_1}`: Data da primeira publicação -> '05/03/2026'
- `${publication_2_text}`: Texto completo da segunda publicação -> 'A segunda publicação se deu na Edição nº 1235, edital n˚ 46/2026, em 06/03/2026.'
- `${publication_3_text}`: Texto completo da terceira publicação -> 'A terceira publicação se deu na Edição nº 1236, edital n˚ 47/2026, em 07/03/2026.'

---

### 1.4 Certidões Específicas por Natureza

#### 1.4.1 Adjudicação
- `${article_required}`: Artigo adaptado para requeridos -> 'a requerida', 'o requerido', 'as requeridas' ou 'os requeridos'
- `${notified_article}`: Verbo notificar com pronome -> 'se notificar' ou 'se notificarem'
- `${verb_manifest2}`: Verbo manifestar conjugado -> 'manifeste' ou 'manifestem'
- `${verb_attend}`: Verbo comparecer conjugado -> 'compareça' ou 'compareçam'

#### 1.4.2 Bem Móvel
- `${vocative_article}`: Artigo adaptado para notificados -> 'o notificado', 'a notificada', 'os notificados' ou 'as notificadas'

#### 1.4.3 Retificação de Área (Certificate)
Utiliza as variáveis base em **1.1**.

---

## 2. Envelope
Utilizado para a impressão de envelopes de envio. Contém um bloco replicável para cada destinatário.

**Bloco Replicável: `BLOCK_ENVELOPE`**
- `${office#}`: Número da serventia -> '123'
- `${year#}`: Ano atual -> '2026'
- `${protocol#}`: Número do protocolo formatado -> '123.456'
- `${nature#}`: Natureza da notificação -> 'Alienação Fiduciária de Bem Imóvel'
- `${clause#}`: Cláusula contratual de procuração -> '10.1'
- `${count_people#}`: Total de pessoas na notificação -> '2'
- `${current_page#}`: Índice da pessoa atual -> '1'
- `${address_notification#}`: Endereço do imóvel objeto da notificação -> '[IDENTIFICAÇÃO DO IMÓVEL]'
- `${people_list#}`: Lista de todos os nomes das pessoas -> 'JOÃO DA SILVA\n   MARIA DA SILVA'
- `${address_person#}`: Endereço completo da pessoa específica -> 'RUA EXEMPLO, 123, BAIRRO, CIDADE/UF'

---

## 3. Notificação

### 3.1 Variáveis Principais (Padrão para a maioria das Natures)
- `${protocol}`: Número do protocolo formatado -> '123.456'
- `${date}`: Data atual por extenso com ponto final -> '05 de março de 2026.'
- `${greeting}`: Saudação inicial -> 'Prezados Senhores,', 'Prezadas Senhoras,', 'Prezado Senhor,' ou 'Prezada Senhora,'
- `${vocative}`: Vocativo curto -> 'Senhores,', 'Senhoras,', 'Senhor,' ou 'Senhora,'
- `${verb_intimate}`: Verbo intimar conjugado -> 'intimar-lhes', 'intimá-lo' ou 'intimá-la'
- `${verb_comply}`: Verbo cumprir conjugado -> 'cumpram' ou 'cumpra'
- `${verb_manifest}`: Verbo manifestar conjugado -> 'manifestem' ou 'manifeste'
- `${pronoun_treatment}`: Pronome de tratamento -> 'Vossas Senhorias' ou 'Vossa Senhoria'
- `${verb_proceed}`: Verbo proceder/efetuar conjugado -> 'efetuarem' ou 'efetuar'
- `${verb_notified_passive}`: Frase de cientificação passiva -> 'ficam Vossas Senhorias cientificadas', 'fica Vossa Senhoria cientificado' ou 'fica Vossa Senhoria cientificada'
- `${edital_vocative}`: Vocativo para fins de edital -> 'os senhores', 'as senhoras', 'o senhor' ou 'a senhora'
- `${verb_go}`: Verbo dirigir-se conjugado -> 'se dirijam' ou 'se dirija'

### 3.2 Por Natureza

#### 3.2.1 Alienação Fiduciária de Bem Imóvel
- `${nature}`: Nome da natureza -> 'Alienação Fiduciária de Bem Imóvel'
- `${creditor}`: Nome do credor -> 'BANCO EXEMPLO'
- `${cnpj_number}`: CNPJ do credor -> '00.000.000/0001-00'
- `${office}`: Número da serventia -> '123'
- `${contract_number}`: Número do contrato -> '12345/2024'
- `${contract_date}`: Data do contrato -> '01/01/2024'
- `${act}`: Ato de registro do contrato -> 'R-1/123.456'
- `${registration_number}`: Matrícula do imóvel -> '123.45'
- `${full_address}`: Endereço completo do imóvel -> 'RUA EXEMPLO, 123, BAIRRO, CIDADE/UF'
- `${default_period}`: Período de inadimplência -> '3 parcelas (01/2024 a 03/2024)'
- `${debt_position_date}`: Data do cálculo da dívida -> '01/03/2026'
- `${total_amount_debt}`: Valor total da dívida -> 'R$ 10.000,00'
- `${emoluments_intimation}`: Valor dos emolumentos -> 'R$ 200,00'
- `${contractual_clause}`: Cláusula de procuração no contrato -> '10.1'
- `${section_title}`: Título da seção de assinaturas -> 'OUTORGA DE PROCURAÇÕES - Cláusula 10.1'
- `${guest_request_phrase}`: Descrição dos convidados -> 'dos senhores abaixo nomeados', 'das senhoras abaixo nomeadas', 'do senhor abaixo nomeado' ou 'da senhora abaixo nomeada'
- `${text_list_edital}`: Lista de nomes para edital -> 'JOÃO DA SILVA, CPF nº 123.456.789-00 e MARIA DA SILVA, CPF nº 987.654.321-00'

**Blocos Replicáveis:**
- `BLOCK_PEOPLE`: bloco de pessoas -> `${line_qualification#}`: Qualificação completa -> 'JOÃO DA SILVA, CPF nº 123.456.789-00'
- `BLOCK_GUESTS`: bloco de convidados -> `${name_guest#}`: Nome do convidado -> 'JOÃO DA SILVA'
- `BLOCK_SIGNATURE`: bloco de assinaturas -> `${signature_content#}`: Conteúdo dinâmico da assinatura -> '___________________________________________________em data de _____/_____/_____. JOÃO DA SILVA, CPF nº 123.456.789-00, por si e por MARIA DA SILVA, CPF nº 987.654.321-00, conforme procuração expressamente outorgada na Cláusula 10.1 do contrato ora executado.'

#### 3.2.2 Compromisso de Compra e Venda (Incorporação e Loteamento)
- `${year}`: Ano atual -> '2026'
- `${property_purchase_and_sale}`: Descrição do imóvel -> '[DESCRIÇÃO DO IMÓVEL]'
- `${total_amount_debt_written}`: Valor da dívida por extenso -> 'dez mil reais'
- `${emoluments_intimation_written}`: Valor dos emolumentos por extenso -> 'duzentos reais'
- `${genitive_treatment}`: Tratamento genitivo dos notificados -> 'dos Senhores abaixo nomeados', 'das Senhoras abaixo nomeadas', 'do Senhor abaixo nomeado' ou 'da Senhora abaixo nomeada'
- `${debtor_should}`: Concordância do dever do devedor -> 'os devedores deverão', 'as devedoras deverão', 'o devedor deverá' ou 'a devedora deverá'

**Blocos Replicáveis:**
- `BLOCK_ADDRESSES`: bloco de endereços -> `${line_address#}`: Endereço formatado -> 'RUA EXEMPLO, 123, BAIRRO, CIDADE/UF,'
- `BLOCK_SIGNATURE`: bloco de assinaturas -> `${signature_name#}`: Nome na assinatura -> 'JOÃO DA SILVA'

#### 3.2.3 Retificação de Área
- `${rectifying_property}`: Identificação do imóvel a retificar -> '[IDENTIFICAÇÃO DO IMÓVEL]'
- `${vocative_article}`: Artigo de tratamento -> 'os Senhores', 'as Senhoras', 'o Senhor' ou 'a Senhora'
- `${manifest_verb}`: Verbo manifestar conjugado -> 'manifestem' ou 'manifeste'
- `${verb_notified_phrase}`: Frase de notificação direta -> 'Ficam Vossas Senhorias NOTIFICADOS', 'Ficam Vossas Senhorias NOTIFICADAS', 'Fica Vossa Senhoria NOTIFICADO' ou 'Fica Vossa Senhoria NOTIFICADA'
- `${text_list_edital}`: Lista qualificada para edital -> 'JOÃO DA SILVA, inscrito no CPF nº 123.456.789-00 e MARIA DA SILVA, inscrita no CPF nº 987.654.321-00'

#### 3.2.4 Usucapião (Adverse Possession)
A natureza de Usucapião possui três tipos de documentos específicos:

**A. Notificação de Particulares:**
- `${vocative}`: Vocativo curto -> 'Senhores,', 'Senhoras,', 'Senhor,' ou 'Senhora,'
- `${verb_manifest}`: Verbo manifestar -> 'manifestem' ou 'manifeste'
- `${pronoun_treatment}`: Tratamento -> 'Vossas Senhorias' ou 'Vossa Senhoria'
- `${notifiable_people}`: Lista qualificada prefixada -> 'os senhores JOÃO DA SILVA, CPF nº 123.456.789-00 e MARIA DA SILVA, CPF nº 987.654.321-00'
- `${registry_number}`: Número da matrícula -> '123.45'

**B. Notificação de Entes Públicos:**
- `${vocative}`: Vocativo fixo -> 'Senhor,'
- `${pronoum_treatment}`: Tratamento fixo -> 'Vossa Senhoria'
- `${registry_number}`: Matrícula -> '123.45'

**C. Edital de Notificação (Exclusivo):**
- `${date}`: Data por extenso -> '05 de março de 2026'
- `${notifiable_people}`: Lista qualificada simples -> 'JOÃO DA SILVA, CPF nº 123.456.789-00 e MARIA DA SILVA, CPF nº 987.654.321-00'
- `${adverse_property}`: Identificação do imóvel -> '[DESCRIÇÃO DO IMÓVEL OBJETO DO USUCAPIÃO]'
- `${registry_number}`: Matrícula -> '123.45'

#### 3.2.5 Adjudicação
- `${adjudicated_property}`: Identificação do imóvel -> '[IDENTIFICAÇÃO DO IMÓVEL]'
- `${vocative}`: Vocativo -> 'Senhores' ou 'Senhora'
- `${verb_notify}`: Concordância do tratamento -> 'os senhores', 'as senhoras', 'o senhor' ou 'a senhora'
- `${pronoun_treatment}`: Tratamento -> 'Vossas Senhorias' ou 'Vossa Senhoria'

#### 3.2.6 Alienação Fiduciária de Bem Móvel
- `${verb_notification}`: Verbo notificar com pronome -> 'notificar-lhe' ou 'notificar-lhes'
- `${interest_should}`: Concordância da obrigação do interessado -> 'o interessado deverá', 'a interessada deverá', 'os interessados deverão' ou 'as interessadas deverão'
- `${verb_aware}`: Verbo tomar ciência -> 'tome' ou 'tomem'
- `${registry_place}`: Local de registro do contrato -> '2º Cartório de Registro de Títulos e Documentos...'
- `${contract_object}`: Descrição do bem móvel -> '[DESCRIÇÃO DO VEÍCULO/MÁQUINA]'
- `${contract_date}`: Data do contrato -> '01/01/2026'
- `${contract_number}`: Número do contrato -> '12345/2026'
- `${creditor}`: Nome do credor -> 'BANCO EXEMPLO'
- `${cnpj_number}`: CNPJ do credor -> '00.000.000/0001-00'
- `${office}`: Número da serventia -> '123'
- `${total_amount_debt}`: Valor total da dívida -> 'R$ 10.000,00'
- `${debt_position_date}`: Data do cálculo da dívida -> '01/01/2026'
- `${default_period}`: Período de inadimplência -> '01/2026 a 03/2026'
- `${contractual_clause}`: Cláusula de procuração -> '10.1'
