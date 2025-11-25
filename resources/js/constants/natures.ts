export interface NatureOption {
    value: string
    text: string
}

export const natures: NatureOption[] = [
    { value: 'alienacao_fiduciaria_imovel', text: 'Alienação Fiduciária de Bem Imóvel' },
    { value: 'alienacao_fiduciaria_movel', text: 'Alienação Fiduciária de Bem Móvel' },
    { value: 'compromisso_loteamento', text: 'Compromisso de Compra e Venda - Loteamento' },
    { value: 'compromisso_incorporacao', text: 'Compromisso de Compra e Venda - Incorporação' },
    { value: 'retificacao_area', text: 'Retificação de Área' },
    { value: 'adjudicacao', text: 'Adjudicação' },
    { value: 'usucapiao', text: 'Usucapião' },
    { value: 'diversos', text: 'Diversos' },
]

export const registryOptions = [
    'Cartório de Registro de Imóveis da Primeira Circunscrição de Anápolis',
    'Cartório de Registro de Imóveis da Segunda Circunscrição de Anápolis',
];