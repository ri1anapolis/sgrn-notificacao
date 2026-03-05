export const toCurrencyDisplay = (value: unknown): string | null => {
    if (value === null || value === undefined || value === '') return null;

    if (typeof value === 'string') {
        if (value.includes('R$') || value.includes(',')) return value;
        const num = parseFloat(value);
        if (isNaN(num)) return value;
        value = num;
    }

    if (typeof value === 'number') {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        }).format(value);
    }

    return null;
};

export const registrationMask = {
    tokens: {
        Z: { pattern: /./ },
    },
    mask: (value: string) => {
        if (value.includes('-') || value.includes(' ')) {
            return 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ';
        }

        const masks = ['#', '##', '###', '#.###', '##.###', '###.###', '#.###.###', '##.###.###', '###.###.###', '#.###.###.###'];

        const cleanValue = value.replace(/\./g, '');
        if (cleanValue.length > 10) return 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ';

        return masks.find((m) => m.replace(/\./g, '').length >= cleanValue.length) || masks[masks.length - 1];
    },
};

export const ordinalMask = {
    mask: '####################',

    postProcess: (val: string) => {
        if (!val) return '';
        return `${val}º`;
    },
};

export const actMask = {
    tokens: {
        S: {
            pattern: /[a-zA-Z]/,
            transform: (v: string) => v.toLocaleUpperCase(),
        },
        Z: { pattern: /./ },
    },

    mask: (value: string) => {
        const hasSpace = value.includes(' ');
        const hyphenCount = (value.match(/-/g) || []).length;
        const secondHyphenIndex = value.lastIndexOf('-');

        if (hasSpace || hyphenCount > 1 || (hyphenCount === 1 && secondHyphenIndex > 2)) {
            return 'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ';
        }

        const numberOfLetters = value.replace(/[^a-zA-Z]/g, '').length;

        if (numberOfLetters >= 2) {
            return 'SS-####';
        }
        return 'S-####';
    },
};

export const currencyMask = {
    tokens: {
        Z: { pattern: /./ },
    },

    preProcess: (val: string) => {
        if (!val) return '';

        // Handle comma as a shortcut: if typed, move everything to the left of the decimal
        if (val.endsWith(',')) {
            const onlyNumbers = val.replace(/\D/g, '');
            return onlyNumbers.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ',00';
        }

        const onlyNumbers = val.replace(/\D/g, '');

        if (!onlyNumbers) return '';

        const padded = onlyNumbers.padStart(3, '0');
        const intPart = padded.slice(0, -2).replace(/^0+/, '') || '0';
        const decPart = padded.slice(-2);

        return intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ',' + decPart;
    },

    mask: (val: string) => {
        return 'Z'.repeat(Math.max(val.length, 1));
    },

    postProcess: (val: string) => {
        if (!val) return '';
        if (val.startsWith('R$')) return val;
        return `R$ ${val}`;
    },
};
