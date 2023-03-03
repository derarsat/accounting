<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="2">
            <n-grid-item>
                <n-form-item label="Name" path="name">
                    <n-input v-model:value="formValue.name"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item label="Default Branch" path="is_default">
                    <n-switch v-model:value="formValue.is_default" size="medium"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item>
                    <n-button :disabled="loading" :loading="loading" @click="handleValidateClick" type="primary">Save
                        branch
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
import {BranchService} from "../services/BranchService";

const props = defineProps<{
    isEdit?: boolean;
    branch?: Branch;
}>();
const emits = defineEmits(["refresh"]);
const helpers = new Helpers();
const formRef = ref<FormInst | null>(null);
const message = useMessage();
const branchService = new BranchService();
const loading = ref(false)
const formValue = ref<Branch>({
    name: "",
    is_default: false,
    id: 0,
});
const rules = {
    name: {
        required: true,
        message: "Please input branch name",
        trigger: ["input", "blur"],
    },
};
const notification = useNotification()

function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await branchService.save(formValue.value, props.isEdit).finally(() => loading.value = false);
            if (!res.success) {
                const errorsString = helpers.generateResponseErrors(res)
                notification.error({
                    content: errorsString,
                    meta: "Unable to add branch",
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
        formValue.value.name = props.branch?.name || "";
        formValue.value.id = props.branch?.id || 0;
        formValue.value.is_default = props.branch?.is_default || false;
    }
});
</script>
