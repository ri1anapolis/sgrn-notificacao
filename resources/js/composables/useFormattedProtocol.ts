export function useProtocolFormatter(protocol: string) {
    const formattedProtocol = String(protocol);

    return formattedProtocol.replace(/\B(?=(\d{3})+(?!\d))/g, ".");;
}