<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="2">
            <!--Branch-->
            <n-grid-item>
                <n-form-item :disabled="isEdit" label="Branch" path="branch_id">
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
                <n-form-item label="Name" path="name">
                    <n-input v-model:value="formValue.name"/>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item>
                    <n-button :disabled="loading" :loading="loading" @click="handleValidateClick" type="primary">Save
                        material
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
import {MaterialService} from "../services/MaterialService";
import {useGlobalStore} from "../store";

const props = defineProps<{
    isEdit?: boolean;
    material?: Material;
}>();
const emits = defineEmits(["refresh"]);
const branches = computed(() => useGlobalStore().branches)

const helpers = new Helpers();
const formRef = ref<FormInst | null>(null);
const message = useMessage();
const materialService = new MaterialService();
const loading = ref(false)
const formValue = ref<Material>({
    name: null,
    id: null,
    branch: branches.value[0],
    branch_id: branches.value[0]["id"],
});
const rules = {
    name: {
        required: true,
        message: "Please input material name",
        trigger: ["input", "blur"],
    },
    branch_id: {
        type: "number",
        required: true,
        message: "Please select branch",
        trigger: ["change", "blur"],
    },
};
const notification = useNotification()

function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await materialService.save(formValue.value, props.isEdit).finally(() => loading.value = false);
            await useGlobalStore().getConfig()
            if (!res.success) {
                const errorsString = helpers.generateResponseErrors(res)
                notification.error({
                    content: errorsString,
                    meta: "Unable to add material",
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
        formValue.value.name = props.material?.name;
        formValue.value.id = props.material?.id;
        formValue.value.branch = props.material?.branch;
        formValue.value.branch_id = props.material?.branch_id;
    }

});
</script>
