export type BankrollTransaction = {
    id: string;
    type: string;
    amount: number;
    description: string | null;
    occurred_at: string;
};

export function formatCurrency(amount: number, currency: string): string {
    return new Intl.NumberFormat(undefined, {
        style: 'currency',
        currency,
    }).format(amount);
}

export function formatPerformance(
    currentBalance: number,
    startingBalance: number,
    currency: string,
): string {
    const change = currentBalance - startingBalance;
    const percentage = startingBalance === 0 ? 0 : change / startingBalance;
    const amount = new Intl.NumberFormat(undefined, {
        style: 'currency',
        currency,
        signDisplay: 'exceptZero',
    }).format(change);
    const formattedPercentage = new Intl.NumberFormat(undefined, {
        style: 'percent',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        signDisplay: 'exceptZero',
    }).format(percentage);

    return `${amount} (${formattedPercentage})`;
}

export function formatDate(date: string): string {
    return new Intl.DateTimeFormat(undefined, {
        dateStyle: 'medium',
    }).format(new Date(date));
}

export function formatDateTime(date: string): string {
    return new Intl.DateTimeFormat(undefined, {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(date));
}

export function formatTransactionType(type: string): string {
    return type
        .split('_')
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

export function isDebitTransaction(type: string): boolean {
    return ['withdrawal', 'stake', 'adjustment_debit'].includes(type);
}

export function formatTransactionAmount(
    transaction: BankrollTransaction,
    currency: string,
): string {
    const amount = isDebitTransaction(transaction.type)
        ? -Number(transaction.amount)
        : Number(transaction.amount);

    return new Intl.NumberFormat(undefined, {
        style: 'currency',
        currency,
        signDisplay: 'exceptZero',
    }).format(amount);
}
