# Natureza da Notificação

O sistema utiliza uma relação polimórfica para determinar a natureza de uma notificação.
A coluna `notifiable_type` na tabela `notifications` armazena o nome do modelo associado, enquanto a interface e as exibições utilizam a tradução correspondente em português.

A tabela abaixo detalha o mapeamento entre os modelos (em inglês) e sua respectiva natureza (em português).

| English (Model Name)           | Portuguese (Nature)                        |
| ------------------------------ | ------------------------------------------ |
| `AlienationRealEstate`         | Alienação Fiduciária de Bem Imóvel         |
| `AlienationMovableProperty`    | Alienação Fiduciária de Bem Móvel          |
| `PurchaseAndSaleIncorporation` | Compromisso de Compra e Venda Incorporação |
| `PurchaseAndSaleSubdivision`   | Compromisso de Compra e Venda Loteamento   |
| `RectificationArea`            | Retificação de Área                        |
| `AdversePossession`            | Usucapião                                  |
| `Adjudication`                 | Adjudicação                                |
| `Other`                        | Diversos                                   |

Vale ressaltar que isso é uma relação polimórfica, ou seja, temos os modelos de cada natureza da notificação e no modelo de notificação nós temos o `MorphTo`, assim conseguimos alta perfomance e que, graças ao Laravel ele otimiza a busca do relacionamento polimórfico buscando primeiro pelo `notifiable_type` e depois indo direto para tabela da relação, ou seja, em apenas duas consultas ele consegue pegar todos os dados necessários da relação, otimizando principalmente o desempenho da aplicação.
