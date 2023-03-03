<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="2">
            <n-grid-item>
                <n-form-item label="Name" path="name">
                    <n-input v-model:value="formValue.name"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item label="Phone" path="phone">
                    <n-input v-model:value="formValue.phone"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item >
                <n-form-item label="Address" path="address">
                    <n-input v-model:value="formValue.address"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item v-if="!isEdit">
                <n-form-item label="To Collect" path="we_owe_him">
                    <n-input v-model:value="formValue.we_owe_him">
                        <template #prefix>
                            $
                        </template>
                    </n-input>
                </n-form-item>
            </n-grid-item>
            <n-grid-item v-if="!isEdit">
                <n-form-item label="To Pay" path="he_owes_us">
                    <n-input v-model:value="formValue.he_owes_us">
                        <template #prefix>
                            $
                        </template>
                    </n-input>
                </n-form-item>
            </n-grid-item>
            <n-grid-item></n-grid-item>
            <n-grid-item>
                <n-form-item>
                    <n-button :disabled="loading" :loading="loading" @click="handleValidateClick" type="primary">Save
                        trader
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
import {TraderService} from "../services/TraderService";

const props = defineProps<{
    isEdit?: boolean;
    trader?: Trader;
}>();
const emits = defineEmits(["refresh"]);
const helpers = new Helpers();
const formRef = ref<FormInst | null>(null);
const message = useMessage();
const traderService = new TraderService();
const loading = ref(false)
const formValue = ref<Trader>({
    name: "",
    phone: "",
    address: "",
    he_owes_us: 0,
    we_owe_him: 0,
    id: 0,
});
const rules = {
    name: {
        required: true,
        message: "Please input trader name",
        trigger: ["input", "blur"],
    },
    phone: {
        required: true,
        message: "Please input trader phone",
        trigger: ["input", "blur"],
    },
};
const notification = useNotification()

function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await traderService.save(formValue.value, props.isEdit).finally(() => loading.value = false);
            if (!res.success) {
                const errorsString = helpers.generateResponseErrors(res)
                notification.error({
                    content: errorsString,
                    meta: "Unable to add trader",
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
        formValue.value.name = props.trader?.name || "";
        formValue.value.id = props.trader?.id || 0;
        formValue.value.phone = props.trader?.phone || "";
        formValue.value.address = props.trader?.address || "";
    }
});
</script>
