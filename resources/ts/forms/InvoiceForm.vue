<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="4">
            <n-form-item-grid-item path="branch_id" label="Branch">
                <n-select
                    filterable
                    placeholder="Please select a branch"
                    :options="branches"
                    v-model:value="formValue.branch_id"
                    label-field="name"
                    value-field="id"
                />
            </n-form-item-grid-item>
            <n-form-item-grid-item path="trader_id" label="Trader">
                <n-select
                    filterable
                    placeholder="Please select a trader"
                    :options="traders"
                    v-model:value="formValue.trader_id"
                    label-field="name"
                    value-field="id"
                />
            </n-form-item-grid-item>
            <n-form-item-grid-item path="currency_id" label="Currency">
                <n-select
                    filterable
                    placeholder="Please select a currency"
                    :options="currencies"
                    v-model:value="formValue.currency_id"
                    label-field="name"
                    value-field="id"
                />
            </n-form-item-grid-item>
            <n-form-item-grid-item label="Type">
                <n-select v-model:value="formValue.type"
                          :options="options">
                </n-select>
            </n-form-item-grid-item>
        </n-grid>
    </n-form>
</template>
<script setup>
import {useGlobalStore} from "../store";


const rules = {}
const branches = computed(() => useGlobalStore().branches)
const currencies = computed(() => useGlobalStore().currencies)
const traders = computed(() => useGlobalStore().traders)
const formValue = {
    branch_id: branches.value[0]["id"],
    trader_id: traders.value[0]["id"],
    currency_id: currencies.value[0]["id"],
    type:"out"
}








//
const options = [
    {
        "label": "Invoice Out",
        "value": "out",
    },
    {
        "label": "Invoice In",
        "value": "in",
    },
]
</script>
