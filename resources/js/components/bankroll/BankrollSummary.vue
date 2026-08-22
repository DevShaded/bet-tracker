<script setup lang="ts">
import { InfoIcon } from '@lucide/vue';
import { computed } from 'vue';
import { Card, CardContent, CardHeader } from '@/components/ui/card';

const props = defineProps<{
    name: string;
    currency: string;
    startingBalance: string;
    isActive: boolean;
}>();

const previewName = computed(() => {
    const trimmedName = props.name.trim();

    if (trimmedName.length > 25) {
        return `${trimmedName.slice(0, 25)}...`;
    }

    return trimmedName || 'New Bankroll';
});
const previewCurrency = computed(() => props.currency.toUpperCase() || 'USD');
const previewStartingBalance = computed(() => {
    const balance = Number(props.startingBalance);

    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: previewCurrency.value,
    }).format(Number.isFinite(balance) ? balance : 0);
});
</script>

<template>
    <aside>
        <Card class="h-full rounded-lg p-6 shadow-sm">
            <CardHeader>
                <h2 class="text-lg font-semibold lg:text-xl">
                    Bankroll Preview
                </h2>
            </CardHeader>

            <CardContent class="flex flex-1 flex-col">
                <div class="space-y-3">
                    <div class="space-y-8 border-t pt-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground"
                                >Name</span
                            >
                            <span class="text-sm font-medium text-foreground">{{
                                previewName
                            }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground"
                                >Currency</span
                            >
                            <span class="text-sm font-medium text-foreground">{{
                                previewCurrency
                            }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground"
                                >Starting Balance</span
                            >
                            <span class="text-sm font-medium text-foreground">{{
                                previewStartingBalance
                            }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground"
                                >Status</span
                            >

                            <span
                                class="rounded-full px-2.5 py-1 text-xs font-medium"
                                :class="
                                    props.isActive
                                        ? 'bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-300'
                                        : 'bg-muted text-muted-foreground'
                                "
                            >
                                {{ props.isActive ? 'Active' : 'Not active' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div
                    class="mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4 lg:mt-auto dark:border-blue-900 dark:bg-blue-950/50"
                >
                    <div class="flex items-start gap-2">
                        <InfoIcon
                            class="mt-0.5 size-4 shrink-0 text-blue-600 dark:text-blue-400"
                            aria-hidden="true"
                        />
                        <div>
                            <h3
                                class="text-sm font-semibold text-blue-900 dark:text-blue-100"
                            >
                                About bankrolls
                            </h3>
                            <p
                                class="mt-1 text-sm text-blue-700 dark:text-blue-300"
                            >
                                Use bankrolls to manage different betting
                                strategies or separate your funds for various
                                purposes.
                            </p>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>
    </aside>
</template>
