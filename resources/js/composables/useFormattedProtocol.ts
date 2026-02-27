export function useProtocolFormatter(protocol: string) {
    const str = String(protocol);
    const match = str.match(/^([a-zA-Z]*)(\d+)$/);
    if (match) {
        const [, prefix, digits] = match;
        return prefix + digits.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    return str;
}