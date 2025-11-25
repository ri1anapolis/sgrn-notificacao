export const registrationMask = {
    mask: [
        '#', '##', '###',
        '#.###', '##.###', '###.###',
        '#.###.###', '##.###.###', '###.###.###',
        '#.###.###.###'
    ]
};

export const ordinalMask = {
    mask: '####################',

    postProcess: (val: string) => {
        if (!val) return '';
        return `${val}º`;
    }
};


export const actMask = {
    tokens: {
        'S': {
            pattern: /[a-zA-Z]/,
            transform: (v: string) => v.toLocaleUpperCase()
        }
    },

    mask: (value: string) => {
        const numberOfLetters = value.replace(/[^a-zA-Z]/g, "").length;

        if (numberOfLetters >= 2) {
            return 'SS-####';
        }
        return 'S-####';
    }
}

export const currencyMask = {
    number: {
        locale: 'pt-BR',
        fraction: 2,
    },

    postProcess: (val: string) => {
        if (!val) return '';
        return `R$ ${val}`;
    }
};