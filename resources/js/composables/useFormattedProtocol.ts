export function useProtocolFormatter(protocol: string) {
    const formattedProtocol = String(protocol);

    if (formattedProtocol.length > 3) {
        return `${formattedProtocol.slice(0, 3)}.${formattedProtocol.slice(3)}`;
    }

    return formattedProtocol;
}