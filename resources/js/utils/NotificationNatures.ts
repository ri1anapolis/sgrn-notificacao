export const NATURE_TO_TYPE_MAP: { [key: string]: string } = {
    alienacao_fiduciaria_imovel: 'App\\Models\\AlienationRealEstate',
    alienacao_fiduciaria_movel: 'App\\Models\\AlienationMovableProperty',
    compromisso_loteamento: 'App\\Models\\PurchaseAndSaleSubdivision',
    compromisso_incorporacao: 'App\\Models\\PurchaseAndSaleIncorporation',
    retificacao_area: 'App\\Models\\RetificationArea',
    adjudicacao: 'App\\Models\\Adjudication',
    usucapiao: 'App\\Models\\AdversePossession',
    diversos: 'App\\Models\\Other',
};

export const TYPE_TO_NATURE_MAP: { [key: string]: string } = Object.entries(NATURE_TO_TYPE_MAP).reduce(
    (acc, [nature, type]) => {
        acc[type] = nature;
        return acc;
    },
    {} as { [key: string]: string },
);

export const NATURE_LABELS: Record<string, App.Enums.NotificationNature> = {
    alienacao_fiduciaria_imovel: 'Alienação Fiduciária de Bem Imóvel',
    alienacao_fiduciaria_movel: 'Alienação Fiduciária de Bem Móvel',
    compromisso_loteamento: 'Compromisso de Compra e Venda Loteamento',
    compromisso_incorporacao: 'Compromisso de Compra e Venda Incorporação',
    retificacao_area: 'Retificação de Área',
    adjudicacao: 'Adjudicação',
    usucapiao: 'Usucapião',
    diversos: 'Diversos',
};

export const getNatureLabelFromType = (type: string | null | undefined): string => {
    if (!type) return 'Não Informado';

    let natureKey = TYPE_TO_NATURE_MAP[type];

    if (!natureKey && NATURE_LABELS[type]) {
        natureKey = type;
    }

    if (natureKey && NATURE_LABELS[natureKey]) {
        return NATURE_LABELS[natureKey];
    }

    return type;
};