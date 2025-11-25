export const formatDateForInput = (dateString: string | null | undefined): string | null => {
    if (!dateString) return null;

    if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
        return dateString;
    }

    const datePart = dateString.split('T')[0];
    if (datePart.match(/^\d{4}-\d{2}-\d{2}$/)) {
        return datePart;
    }

    return null;
};
