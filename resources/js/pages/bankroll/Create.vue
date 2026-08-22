<script setup lang="ts">
import { Form, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import BankrollSummary from '@/components/bankroll/BankrollSummary.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectItem,
    SelectTrigger,
    SelectValue,
    SelectContent,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { Switch } from '@/components/ui/switch';
import bankroll from '@/routes/bankroll';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Create bankroll',
                href: bankroll.create(),
            },
        ],
    },
});

const name = ref('');
const currency = ref('');
const startingBalance = ref('');
const isActive = ref(false);

const canSubmit = computed(() => {
    return (
        name.value.trim() !== '' &&
        currency.value !== '' &&
        startingBalance.value.trim() !== ''
    );
});
</script>

<template>
    <Form
        :action="bankroll.store()"
        :transform="
            (data) => ({
                ...data,
                starting_balance: Number(data.starting_balance).toFixed(2),
                is_active: data.is_active === 'on',
            })
        "
        v-slot="{ errors, processing }"
        class="flex h-full flex-col p-4"
    >
        <div
            class="flex flex-col items-start justify-between gap-4 sm:flex-row"
        >
            <div>
                <h1 class="text-lg font-medium lg:text-2xl">
                    Create New Bankroll
                </h1>
                <p class="text-sm text-muted-foreground">
                    Create a new bankroll to separate and track you betting
                    funds.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <Button variant="outline" as-child>
                    <Link :href="bankroll.index()">Cancel</Link>
                </Button>
                <Button
                    type="submit"
                    :disabled="processing || !canSubmit"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" />
                    Create new bankroll
                </Button>
            </div>
        </div>
        <div
            class="mt-10 grid min-h-0 flex-1 grid-cols-1 gap-6 lg:h-1/2 lg:flex-none lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]"
        >
            <Card class="h-full rounded-lg p-6 shadow-sm">
                <CardHeader>
                    <h2 class="text-lg font-semibold lg:text-xl">
                        Bankroll Details
                    </h2>
                </CardHeader>

                <CardContent>
                    <div class="flex flex-col gap-6">
                        <div class="grid gap-2">
                            <Label for="name">
                                Name
                                <span
                                    class="text-destructive"
                                    aria-hidden="true"
                                    >*</span
                                >
                            </Label>
                            <Input
                                id="name"
                                v-model="name"
                                type="text"
                                name="name"
                                placeholder="e.g. Main Bankroll"
                                maxlength="50"
                                required
                                autofocus
                                autocomplete="off"
                            />
                            <div
                                class="flex justify-between gap-4 text-sm text-muted-foreground"
                            >
                                <p>
                                    Give your bankroll a name to easily identify
                                    it.
                                </p>
                                <span class="shrink-0"
                                    >{{ name.length }}/50</span
                                >
                            </div>
                            <InputError :message="errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="currency">
                                Currency
                                <span
                                    class="text-destructive"
                                    aria-hidden="true"
                                    >*</span
                                >
                            </Label>
                            <Select v-model="currency" name="currency" required>
                                <SelectTrigger id="currency" class="w-full">
                                    <SelectValue
                                        placeholder="Select a currency"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="usd">USD</SelectItem>
                                    <SelectItem value="eur">EUR</SelectItem>
                                    <SelectItem value="gbp">GBP</SelectItem>
                                </SelectContent>
                            </Select>
                            <p class="text-sm text-muted-foreground">
                                Select the currency for this bankroll.
                            </p>
                            <InputError :message="errors.currency" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="starting_balance"
                                >Starting Balance
                                <span
                                    class="text-destructive"
                                    aria-hidden="true"
                                    >*</span
                                ></Label
                            >
                            <Input
                                id="starting_balance"
                                v-model="startingBalance"
                                type="text"
                                name="starting_balance"
                                placeholder="20"
                                required
                                autofocus
                                autocomplete="off"
                            />
                            <p class="text-sm text-muted-foreground">
                                Enter the starting balance for this bankroll.
                            </p>
                            <InputError :message="errors.starting_balance" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="is_active">Is active</Label>
                            <div class="flex items-center gap-2">
                                <Switch
                                    id="is_active"
                                    v-model="isActive"
                                    name="is_active"
                                />
                                <span class="text-sm font-medium">
                                    {{ isActive ? 'Active' : 'Not active' }}
                                </span>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                Set whether this bankroll is active.
                            </p>
                            <InputError :message="errors.is_active" />
                        </div>
                    </div>
                </CardContent>
            </Card>

            <BankrollSummary
                :name="name"
                :currency="currency"
                :starting-balance="startingBalance"
                :is-active="isActive"
            />
        </div>
    </Form>
</template>
