<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="2">
            <n-grid-item>
                <n-form-item label="Name" path="name">
                    <n-input v-model:value="formValue.name"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item label="Symbol" path="symbol">
                    <n-input v-model:value="formValue.symbol"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item label="Rate" path="rate">
                    <n-input-number
                        :parse="helpers.parse"
                        :format="helpers.format"
                        style="width: 100%"
                        v-model:value="formValue.rate"
                    />
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item label="Amount" path="amount">
                    <n-input-number
                        :parse="helpers.parse"
                        :format="helpers.format"
                        style="width: 100%"
                        v-model:value="formValue.amount"
                    />
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item>
                    <n-button :disabled="loading" :loading="loading" @click="handleValidateClick" type="primary">Save
                        currency
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
import {CurrencyService} from "../services/CurrencyService";
import {useNotification} from 'naive-ui'

const props = defineProps<{
    isEdit?: boolean;
    currency?: Currency;
}>();
const emits = defineEmits(["refresh"]);
const helpers = new Helpers();
const formRef = ref<FormInst | null>(null);
const message = useMessage();
const currencyService = new CurrencyService();
const loading = ref(false)
const formValue = ref<Currency>({
    name: "",
    symbol: "",
    rate: 0,
    amount: 0,
    id: 0,
});
const rules = {
    name: {
        required: true,
        message: "Please input currency name",
        trigger: ["input", "blur"],
    },
    // rate: {
    //   required: true,
    //   message: "Please input currency rate",
    //   trigger: ["input", "blur"],
    // },
    // amount: {
    //   required: true,
    //   message: "Please input currency amount",
    //   trigger: ["input", "blur"],
    // },
    symbol: {
        required: true,
        message: "Please input currency symbol",
        trigger: ["input", "blur"],
    },
};
const notification = useNotification()

function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await currencyService.save(formValue.value, props.isEdit).finally(() => loading.value = false);
            if (!res.success) {
                const errorsString = helpers.generateResponseErrors(res)
                notification.error({
                    content: errorsString,
                    meta: "Unable to add currency",
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

onMounted(() => {
    if (props.isEdit) {
        formValue.value.amount = props.currency?.amount || 0;
        formValue.value.name = props.currency?.name || "";
        formValue.value.rate = props.currency?.rate || 0;
        formValue.value.symbol = props.currency?.symbol || "";
        formValue.value.id = props.currency?.id || 0;

    }
});
</script>
