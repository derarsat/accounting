<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="2">
            <n-grid-item>
                <n-form-item path="branch_id" label="Branch">
                    <n-select
                        filterable
                        placeholder="Please select a branch"
                        :options="branches"
                        v-model:value="formValue.branch_id"
                        label-field="name"
                        value-field="id"
                        @update-value="handleBranchChanged"
                    />
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item path="model_id" label="Expense">
                    <n-select
                        filterable
                        placeholder="Please select a branch"
                        :options="expensesTemp"
                        v-model:value="formValue.model_id"
                        label-field="name"
                        value-field="id"
                    />
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item path="currency_id" label="Currency">
                    <n-select
                        filterable
                        placeholder="Please select a branch"
                        :options="currencies"
                        v-model:value="formValue.currency_id"
                        label-field="name"
                        value-field="id"
                    />
                </n-form-item>
            </n-grid-item>

            <n-grid-item>
                <n-form-item label="Amount" path="amount">
                    <n-input-number
                        :parse="helpers.parse"
                        :format="helpers.format"
                        style="width: 100%" v-model:value="formValue.amount"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item label="Rate" path="rate">
                    <n-input-number
                        :disabled="formValue.currency_id === 1"
                        :parse="helpers.parse"
                        :format="helpers.format"
                        style="width: 100%" v-model:value="formValue.rate"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item span="2">
                <n-form-item label="Description" path="description">
                    <n-input type="textarea" v-model:value="formValue.description"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item>
                    <n-button :disabled="loading" :loading="loading" @click="handleValidateClick" type="primary">Save

                    </n-button>
                </n-form-item>
            </n-grid-item>
        </n-grid>
    </n-form>
</template>

<script setup lang="ts">
import {Helpers} from "../helpers";
import {AccountingService} from "../services/AccountingService"

const accountingService = new AccountingService()
const loading = ref(false)
const notification = useNotification()
const message = useMessage();

enum WalletOperationType {
    PaymentIn = "payment_in",
    PaymentOut = "payment_out",
    InvoiceIn = "invoice_in",
    InvoiceOut = "invoice_out",
    Expense = "expense",
}

const helpers = new Helpers();
import {ref} from "vue";
import {FormInst, FormRules, useMessage, useNotification} from "naive-ui";
import {useGlobalStore} from "../store";

const branches = computed(() => useGlobalStore().branches)
const currencies = computed(() => useGlobalStore().currencies)
const expenses = computed<Expense[]>(() => useGlobalStore().expenses as Expense[])
const expensesTemp = ref(expenses.value.filter((expense) => expense.branch_id == branches.value[0].id))

const emits = defineEmits(["refresh"]);

const rules: FormRules = {
    description: {
        type: "string",
        required: true,
        message: "Please input description",
        trigger: ["input", "blur"],
    },
    amount: {
        type: "number",
        required: true,
        min: 1,
        message: "Please input amount",
        trigger: ["input", "blur"],
    },
    rate: {
        type: "number",
        required: true,
        min: 1,
        message: "Please input rate",
        trigger: ["input", "blur"],
    },
    currency_id: {
        type: "number",
        required: true,
        message: "Please select currency",
        trigger: ["change", "blur"],
    },
    branch_id: {
        type: "number",
        required: true,
        message: "Please select branch",
        trigger: ["change", "blur"],
    },
    model_id: {
        type: "number",
        required: true,
        message: "Please select expense",
        trigger: ["change", "blur"],
    },
}
const formValue = ref<WalletOperation>({
    branch_id: useGlobalStore().branches[0]["id"],
    currency_id: useGlobalStore().branches[0]["id"],
    type: WalletOperationType.Expense,
    amount: 0,
    rate: 1,
    model_id: null,
    currency: currencies.value.filter((currency) => currency.symbol === "$")[0],
    description: ""
});

const formRef = ref<FormInst | null>(null);

async function handleBranchChanged(v) {
    const activeBranch = formValue.value.branch_id
    await nextTick(() => {
        expensesTemp.value = expenses.value.filter((expense) => expense.branch_id == v)
        formValue.value.model_id = expenses.value[0].id
    })
}


function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await accountingService.add(formValue.value).finally(() => loading.value = false);
            await useGlobalStore().getConfig()
            if (!res.success) {
                const errorsString = helpers.generateResponseErrors(res)
                notification.error({
                    content: errorsString,
                    meta: "Unable to add cash out",
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

</script>

<style scoped>

</style>
