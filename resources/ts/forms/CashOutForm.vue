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
                        @change="handleBranchChanged()"
                    />
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item path="model_id" label="Expense">
                    <n-select
                        filterable
                        placeholder="Please select a branch"
                        :options="expenses"
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

const branches = ref<Branch[]>()
const currencies = ref<Currency[]>()
const expenses = ref<Expense[]>()
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
    branch_id: JSON.parse(sessionStorage.getItem("branches"))[0]["id"],
    currency_id: JSON.parse(sessionStorage.getItem("currencies"))[0]["id"],
    type: WalletOperationType.Expense,
    amount: 0,
    rate: 0,
    model_id: null,
    description: ""
});

const formRef = ref<FormInst | null>(null);
onMounted(() => {
    branches.value = JSON.parse(sessionStorage.getItem("branches"))
    handleBranchChanged()
    currencies.value = JSON.parse(sessionStorage.getItem("currencies"))
})

function handleBranchChanged() {
    const activeBranch = formValue.value.branch_id
    const expensesTemp: Expense[] = JSON.parse(sessionStorage.getItem("expenses"))
    expenses.value = expensesTemp.filter((expense) => expense.branch_id == activeBranch)
    formValue.value.model_id = expenses.value[0].id
}


function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await accountingService.add(formValue.value).finally(() => loading.value = false);
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
