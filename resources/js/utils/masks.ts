export const registrationMask = {
    tokens: {
        'Z': { pattern: /./ }
    },
    mask: (value: string) => {
        if (value.includes('-') || value.includes(' ')) {
            return 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ';
        }

        const masks = [
            '#', '##', '###',
            '#.###', '##.###', '###.###',
            '#.###.###', '##.###.###', '###.###.###',
            '#.###.###.###'
        ];

        const cleanValue = value.replace(/\./g, "");
        if (cleanValue.length > 10) return 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ';

        return masks.find(m => m.replace(/\./g, "").length >= cleanValue.length) || masks[masks.length - 1];
    }
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
        },
        'Z': { pattern: /./ }
    },

    mask: (value: string) => {
        const hasSpace = value.includes(' ');
        const hyphenCount = (value.match(/-/g) || []).length;
        const secondHyphenIndex = value.lastIndexOf('-');

        if (hasSpace || hyphenCount > 1 || (hyphenCount === 1 && secondHyphenIndex > 2)) {
            return 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ';
        }

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