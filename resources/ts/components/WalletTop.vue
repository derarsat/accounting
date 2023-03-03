<template>
    <n-layout>
        <n-space>
            <n-button type="primary" @click.prevent="showCashOut = true">
                <template #icon>
                    <n-icon>
                        <log-out-outline/>
                    </n-icon>
                </template>
                Cash out
            </n-button>
            <n-button type="primary">
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
    </n-layout>
</template>

<script setup lang="ts">
import {LogOutOutline, SwapHorizontalOutline, CashOutline} from "@vicons/ionicons5";
import CashOutForm from "../forms/CashOutForm.vue";

const showCashOut = ref(false)
const emits = defineEmits()


function getWalletOperations() {
    showCashOut.value = false;
    // loading.value = true;

}
</script>
