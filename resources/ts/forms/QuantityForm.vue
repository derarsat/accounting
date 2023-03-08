<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="2">
            <n-grid-item>
                <n-form-item label="Name" path="name">
                    <n-input :disabled="isEdit" v-model:value="formValue.name"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item  label="Number Of Pieces" path="pieces">
                    <n-input-number  v-model:value="formValue.number_of_pieces"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item>
                    <n-button :disabled="loading" :loading="loading" @click="handleValidateClick" type="primary">Save
                        quantity
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
import {QuantityService} from "../services/QuantityService";
import {useGlobalStore} from "../store";

const props = defineProps<{
    isEdit?: boolean;
    quantity?: Quantity;
}>();
const emits = defineEmits(["refresh"]);
const helpers = new Helpers();
const formRef = ref<FormInst | null>(null);
const message = useMessage();
const quantityService = new QuantityService();
const loading = ref(false)
const formValue = ref<Quantity>({
    name: "",
    id: null,
    number_of_pieces: 0
});
const rules = {
    name: {
        required: true,
        message: "Please input quantity name",
        trigger: ["input", "blur"],
    },
    number_of_pieces: {
        required: true,
        message: "Please input number of pieces",
        trigger: ["input", "blur"],
    },
};
const notification = useNotification()

function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await quantityService.save(formValue.value, props.isEdit).finally(() => loading.value = false);
            await useGlobalStore().getConfig()
            if (!res.success) {
                const errorsString = helpers.generateResponseErrors(res)
                notification.error({
                    content: errorsString,
                    meta: "Unable to add quantity",
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
        formValue.value.name = props.quantity?.name || "";
        formValue.value.id = props.quantity?.id || 0;
        formValue.value.number_of_pieces = props.quantity?.number_of_pieces || 0;
    }
});
</script>
