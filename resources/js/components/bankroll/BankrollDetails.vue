<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { X } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    formatCurrency,
    formatDate,
    formatDateTime,
    formatPerformance,
    formatTransactionAmount,
    formatTransactionType,
    isDebitTransaction
} from '@/lib/bankroll';
import type {BankrollTransaction} from '@/lib/bankroll';
import bankrollRoutes from '@/routes/bankroll';
import type { Bankroll } from '@/types';

type BankrollDetails = Bankroll & {
    current_balance: number;
    transactions: BankrollTransaction[];
};

defineProps<{
    bankroll: BankrollDetails;
}>();

defineEmits<{
    close: [];
}>();
</script>

<template>
    <Card class="h-full xl:sticky xl:top-4">
        <CardHeader class="flex flex-row items-start justify-between gap-4">
            <div class="space-y-1">
                <CardTitle>Bankroll Details</CardTitle>
                <CardDescription>{{ bankroll.name }}</CardDescription>
            </div>
            <Button
                variant="ghost"
                size="icon"
                aria-label="Close bankroll details"
                @click="$emit('close')"
            >
                <X class="size-4" />
            </Button>
        </CardHeader>

        <CardContent class="space-y-6">
            <div class="flex items-center justify-between gap-4">
                <p class="font-semibold">{{ bankroll.name }}</p>
                <span
                    class="inline-flex rounded-full px-2 py-1 text-xs font-medium"
                    :class="
                        bankroll.is_active
                            ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400'
                            : 'bg-muted text-muted-foreground'
                    "
                >
                    {{ bankroll.is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="text-muted-foreground">Current Balance</dt>
                    <dd class="font-medium tabular-nums">
                        {{
                            formatCurrency(
                                bankroll.current_balance,
                                bankroll.currency,
                            )
                        }}
                    </dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-muted-foreground">Starting Balance</dt>
                    <dd class="font-medium tabular-nums">
                        {{
                            formatCurrency(
                                bankroll.starting_balance,
                                bankroll.currency,
                            )
                        }}
                    </dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-muted-foreground">Net Change</dt>
                    <dd
                        class="font-medium tabular-nums"
                        :class="
                            bankroll.current_balance > bankroll.starting_balance
                                ? 'text-emerald-700 dark:text-emerald-400'
                                : bankroll.current_balance <
                                    bankroll.starting_balance
                                  ? 'text-red-700 dark:text-red-400'
                                  : 'text-muted-foreground'
                        "
                    >
                        {{
                            formatPerformance(
                                bankroll.current_balance,
                                bankroll.starting_balance,
                                bankroll.currency,
                            )
                        }}
                    </dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-muted-foreground">Currency</dt>
                    <dd class="font-medium">{{ bankroll.currency }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-muted-foreground">Created</dt>
                    <dd class="font-medium" data-allow-mismatch="text">
                        {{ formatDate(bankroll.created_at) }}
                    </dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-muted-foreground">Last Updated</dt>
                    <dd
                        class="text-right font-medium"
                        data-allow-mismatch="text"
                    >
                        {{ formatDateTime(bankroll.updated_at) }}
                    </dd>
                </div>
            </dl>

            <div class="space-y-4 border-t pt-5">
                <h3 class="font-semibold">Recent Transactions</h3>
                <div v-if="bankroll.transactions.length" class="space-y-4">
                    <div
                        v-for="transaction in bankroll.transactions.slice(0, 5)"
                        :key="transaction.id"
                        class="flex items-start justify-between gap-4 text-sm"
                    >
                        <div class="min-w-0">
                            <p class="truncate font-medium">
                                {{
                                    transaction.description ??
                                    formatTransactionType(transaction.type)
                                }}
                            </p>
                            <p
                                class="text-xs text-muted-foreground"
                                data-allow-mismatch="text"
                            >
                                {{ formatDateTime(transaction.occurred_at) }}
                            </p>
                        </div>
                        <span
                            class="shrink-0 font-medium tabular-nums"
                            :class="
                                isDebitTransaction(transaction.type)
                                    ? 'text-red-700 dark:text-red-400'
                                    : 'text-emerald-700 dark:text-emerald-400'
                            "
                        >
                            {{
                                formatTransactionAmount(
                                    transaction,
                                    bankroll.currency,
                                )
                            }}
                        </span>
                    </div>
                </div>
                <p v-else class="text-sm text-muted-foreground">
                    No transactions found.
                </p>
            </div>

            <Button as-child class="w-full" variant="outline">
                <Link :href="bankrollRoutes.show.url(bankroll)">
                    View Full History
                </Link>
            </Button>
        </CardContent>
    </Card>
</template>
