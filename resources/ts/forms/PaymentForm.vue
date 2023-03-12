<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="2">
            <!--Branch-->
            <n-grid-item>
                <n-form-item path="branch_id" label="Branch">
                    <n-select
                        filterable
                        placeholder="Please select a branch"
                        :options="branches"
                        v-model:value="formValue.branch_id"
                        label-field="name"
                        value-field="id"
                    />
                </n-form-item>
            </n-grid-item>

            <n-grid-item>
                <n-form-item path="trader_id" label="Trader">
                    <n-select
                        filterable
                        placeholder="Please select a trader"
                        :options="traders"
                        v-model:value="formValue.trader_id"
                        label-field="name"
                        value-field="id"
                    />
                </n-form-item>
            </n-grid-item>
            <n-form-item-grid-item label="Type">
                <n-select v-model:value="formValue.type"
                          :options="options">
                </n-select>
            </n-form-item-grid-item>

            <n-grid-item>
                <n-form-item path="currency_id" label="Currency">
                    <n-select
                        filterable
                        placeholder="Please select a currency"
                        :options="currencies"
                        v-model:value="formValue.currency_id"
                        label-field="name"
                        value-field="id"
                    />
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item label="Rate" path="rate">
                    <n-input-number style="width:100%" v-model:value="formValue.rate"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item label="Amount" path="amount">
                    <n-input-number style="width:100%" v-model:value="formValue.amount"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item>
                    <n-button :disabled="loading" :loading="loading" @click="handleValidateClick" type="primary">Save
                        payment
                    </n-button>
                </n-form-item>
            </n-grid-item>
        </n-grid>
    </n-form>
</template>

<script lang="ts" setup>
import {ref} from "vue";
import {FormInst, useMessage} from "naive-ui";
import {Helpers} from "../helpers";
import {useNotification} from 'naive-ui'
import {useGlobalStore} from "../store";
import {AccountingService} from "../services/AccountingService";
enum PaymentAndInvoiceType {
    in = "in",
    out = "out",
}
const branches = computed(() => useGlobalStore().branches)
const currencies = computed(() => useGlobalStore().currencies)
const traders = computed(() => useGlobalStore().traders)
const emits = defineEmits(["refresh"]);
const helpers = new Helpers();
const formRef = ref<FormInst | null>(null);
const message = useMessage();
const loading = ref(false)
const formValue = ref<Payment>({
    id: null,
    currency_id: currencies.value[0]["id"],
    trader_id: branches.value[0]["id"],
    rate: 1,
    amount: 0,
    type: PaymentAndInvoiceType.in,
});
const rules = {
    branch_id: {
        type: "number",
        required: true,
        message: "Please select branch",
        trigger: ["input", "blur"],
    },
    trader_id: {
        type: "number",
        required: true,
        message: "Please select trader",
        trigger: ["input", "blur"],
    },
    currency_id: {
        type: "number",
        required: true,
        message: "Please select currency",
        trigger: ["input", "blur"],
    },

    amount: {
        type: "number",
        required: true,
        message: "Please select amount",
        trigger: ["input", "blur"],
    },

    rate: {
        type: "number",
        required: true,
        message: "Please select rate",
        trigger: ["input", "blur"],
    },
};
const notification = useNotification()

function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await new AccountingService().addPayment(formValue.value,).finally(() => loading.value = false);
            await useGlobalStore().getConfig()
            if (!res.success) {
                const errorsString = helpers.generateResponseErrors(res)
                notification.error({
                    content: errorsString,
                    meta: "Unable to add payment",
                    duration: 3000,
                });
                return
            }
            notification.success({
                content: res.data["message"] as string,
                meta: "Operation successes",
                duration: 3000,
            });
            emits("refresh");
        } else {
            message.error("Please make sure the data you entered is valid", {
                closable: true,
            });
        }
    });
}


const options = [
    {
        "label": "Payment Out",
        "value": "out",
    },
    {
        "label": "Payment In",
        "value": "in",
    },
]


</script>
