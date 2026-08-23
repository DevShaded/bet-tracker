<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import BankrollDetails from '@/components/bankroll/BankrollDetails.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import bankroll from '@/routes/bankroll';
import type { Bankroll as BankrollType } from '@/types';

type BankrollTransaction = {
    id: string;
    type: string;
    amount: number;
    description: string | null;
    occurred_at: string;
};

type BankrollWithBalance = BankrollType & {
    current_balance: number;
    transactions: BankrollTransaction[];
};

function formatCurrency(amount: number, currency: string): string {
    return new Intl.NumberFormat(undefined, {
        style: 'currency',
        currency,
    }).format(amount);
}

function formatPercentage(percentage: number): string {
    return new Intl.NumberFormat(undefined, {
        style: 'percent',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(percentage / 100);
}

function formatPerformance(
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

function formatDate(date: string): string {
    return new Intl.DateTimeFormat(undefined, {
        dateStyle: 'medium',
    }).format(new Date(date));
}

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Bankrolls',
                href: bankroll.index(),
            },
        ],
    },
});

const props = defineProps<{
    stats: {
        active_bankrolls_count: number;
        by_currency: Record<
            string,
            {
                total_bankroll: number;
                net_change: number;
                net_change_percentage: number;
                today_change: {
                    amount: number;
                    percentage: number;
                };
                exposure_at_risk: {
                    amount: number;
                    percentage: number;
                };
            }
        >;
    };
    bankrolls: BankrollWithBalance[];
}>();

const search = ref('');
const selectedBankroll = ref<BankrollWithBalance | null>(
    props.bankrolls[0] ?? null,
);

const filteredBankrolls = computed(() => {
    const query = search.value.trim().toLowerCase();

    if (!query) {
        return props.bankrolls;
    }

    return props.bankrolls.filter(
        (bankrollItem) =>
            bankrollItem.name.toLowerCase().includes(query) ||
            bankrollItem.currency.toLowerCase().includes(query),
    );
});
</script>

<template>
    <Head title="Bankrolls" />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold">Bankroll Overview</h2>
            <p class="text-sm text-muted-foreground">
                {{ stats.active_bankrolls_count }} active bankrolls
            </p>
        </div>
        <div
            v-for="(currencyStats, currency) in stats.by_currency"
            :key="currency"
            class="flex flex-col gap-4"
        >
            <h3 class="text-lg font-semibold">{{ currency }}</h3>
            <div class="grid auto-rows-min gap-4 lg:grid-cols-3">
                <Card
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <CardHeader>Total Bankroll</CardHeader>

                    <CardContent>
                        <h2 class="text-2xl">
                            {{
                                formatCurrency(
                                    currencyStats.total_bankroll,
                                    currency,
                                )
                            }}
                        </h2>
                        <p class="mt-3 text-sm text-muted-foreground">
                            {{
                                formatCurrency(
                                    currencyStats.net_change,
                                    currency,
                                )
                            }}
                            ({{
                                formatPercentage(
                                    currencyStats.net_change_percentage,
                                )
                            }})
                        </p>
                    </CardContent>
                </Card>

                <Card
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <CardHeader>Today's Change</CardHeader>

                    <CardContent>
                        <h2 class="text-2xl">
                            {{
                                formatCurrency(
                                    currencyStats.today_change.amount,
                                    currency,
                                )
                            }}
                        </h2>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{
                                formatPercentage(
                                    currencyStats.today_change.percentage,
                                )
                            }}
                            vs yesterday
                        </p>
                    </CardContent>
                </Card>

                <Card
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <CardHeader>Exposure at Risk</CardHeader>

                    <CardContent>
                        <h2 class="text-2xl">
                            {{
                                formatCurrency(
                                    currencyStats.exposure_at_risk.amount,
                                    currency,
                                )
                            }}
                        </h2>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{
                                formatPercentage(
                                    currencyStats.exposure_at_risk.percentage,
                                )
                            }}
                            of total bankroll
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>
        <div
            class="grid flex-1 gap-4"
            :class="{
                'xl:grid-cols-[minmax(0,1fr)_24rem]': selectedBankroll,
            }"
        >
            <div
                class="relative h-full overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <div class="flex items-center justify-between gap-4 border-b p-4">
                    <Input
                        v-model="search"
                        class="max-w-sm"
                        placeholder="Search bankrolls..."
                        aria-label="Search bankrolls"
                    />
                    <Button as-child class="shrink-0">
                        <Link :href="bankroll.create()">Create bankroll</Link>
                    </Button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <caption class="sr-only">
                            Current user bankrolls
                        </caption>
                        <thead class="border-b bg-muted/50 text-left">
                            <tr>
                                <th class="px-4 py-3 font-medium">Name</th>
                                <th class="px-4 py-3 font-medium">
                                    Current balance
                                </th>
                                <th class="px-4 py-3 font-medium">
                                    Starting balance
                                </th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium">Created</th>
                                <th class="px-4 py-3 text-right font-medium">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr
                                v-for="bankrollItem in filteredBankrolls"
                                :key="bankrollItem.id"
                                class="cursor-pointer transition-colors hover:bg-muted/50"
                                :class="
                                    selectedBankroll?.id === bankrollItem.id
                                        ? 'bg-muted/50'
                                        : ''
                                "
                                @click="selectedBankroll = bankrollItem"
                            >
                                <td class="px-4 py-3 font-medium">
                                    {{ bankrollItem.name }}
                                </td>
                                <td class="px-4 py-3 tabular-nums">
                                    <div>
                                        {{
                                            formatCurrency(
                                                bankrollItem.current_balance,
                                                bankrollItem.currency,
                                            )
                                        }}
                                    </div>
                                    <div
                                        class="mt-1 text-xs font-medium"
                                        :class="
                                            bankrollItem.current_balance >
                                            bankrollItem.starting_balance
                                                ? 'text-emerald-700 dark:text-emerald-400'
                                                : bankrollItem.current_balance <
                                                    bankrollItem.starting_balance
                                                  ? 'text-red-700 dark:text-red-400'
                                                  : 'text-muted-foreground'
                                        "
                                    >
                                        {{
                                            formatPerformance(
                                                bankrollItem.current_balance,
                                                bankrollItem.starting_balance,
                                                bankrollItem.currency,
                                            )
                                        }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 tabular-nums">
                                    {{
                                        formatCurrency(
                                            bankrollItem.starting_balance,
                                            bankrollItem.currency,
                                        )
                                    }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2 py-1 text-xs font-medium"
                                        :class="
                                            bankrollItem.is_active
                                                ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400'
                                                : 'bg-muted text-muted-foreground'
                                        "
                                    >
                                        {{
                                            bankrollItem.is_active
                                                ? 'Active'
                                                : 'Inactive'
                                        }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-muted-foreground">
                                    {{ formatDate(bankrollItem.created_at) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <Button
                                        as-child
                                        size="sm"
                                        variant="outline"
                                    >
                                        <Link
                                            :href="
                                                bankroll.show.url(bankrollItem)
                                            "
                                        >
                                            View
                                        </Link>
                                    </Button>
                                </td>
                            </tr>
                            <tr v-if="filteredBankrolls.length === 0">
                                <td
                                    colspan="6"
                                    class="h-24 px-4 text-center text-muted-foreground"
                                >
                                    No bankrolls match your search.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <BankrollDetails
                v-if="selectedBankroll"
                :bankroll="selectedBankroll"
                @close="selectedBankroll = null"
            />
        </div>
    </div>
</template>
