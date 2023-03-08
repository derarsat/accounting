<template>
    <n-layout>
        <n-space vertical :size="20">
            <n-grid :cols="6"  y-gap="10">
                <n-gi v-for="currency in currencies">
                    <n-statistic :label="currency.name" :value="getCurrencyValue(currency)"/>
                </n-gi>
            </n-grid>
            <n-space >
                <n-button type="primary" @click.prevent="showCashOut = true">
                    <template #icon>
                        <n-icon>
                            <log-out-outline/>
                        </n-icon>
                    </template>
                    Cash out
                </n-button>
                <n-button type="primary" @click.prevent="showPayment = true">
                    <template #icon>
                        <n-icon>
                            <cash-outline/>
                        </n-icon>
                    </template>
                    Add Payment
                </n-button>
                <n-button type="primary">
                    <template #icon>
                        <n-icon>
                            <swap-horizontal-outline/>
                        </n-icon>
                    </template>
                    Add Invoice
                </n-button>
            </n-space>
            <n-modal v-model:show="showCashOut">
                <n-card
                    style="width: 600px"
                    title="Cash-out details"
                    :bordered="false"
                    size="huge"
                    role="dialog"
                    aria-modal="true"
                >
                    <CashOutForm @refresh="getWalletOperations()"/>
                </n-card>
            </n-modal>

            <n-modal v-model:show="showPayment">
                <n-card
                    style="width: 600px"
                    title="New Payment"
                    :bordered="false"
                    size="huge"
                    role="dialog"
                    aria-modal="true"
                >
                    <PaymentForm @refresh="getWalletOperations()"/>
                </n-card>
            </n-modal>
        </n-space>
    </n-layout>
</template>

<script setup lang="ts">
import {LogOutOutline, SwapHorizontalOutline, CashOutline} from "@vicons/ionicons5";
import CashOutForm from "../forms/CashOutForm.vue";
import {useGlobalStore} from "../store";
import {Helpers} from "../helpers";
import PaymentForm from "../forms/PaymentForm.vue";

const showCashOut = ref(false)
const showPayment = ref(false)
const emits = defineEmits(["refresh"])
const currencies = computed(() => useGlobalStore().currencies as Currency[])
const helpers = new Helpers()

function getCurrencyValue(currency: Currency) {
    return helpers.format(currency.amount) + " " + currency.symbol
}

function getWalletOperations() {
    showCashOut.value = false;
    showPayment.value = false;
    emits("refresh")
}
</script>
