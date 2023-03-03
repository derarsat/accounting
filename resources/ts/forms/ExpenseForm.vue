<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="2">
            <!--Branch-->
            <n-grid-item>
                <n-form-item :disabled="isEdit" path="branch_id" label="Branch">
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
                        expense
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
import {ExpensesService} from "../services/ExpensesService";

const props = defineProps<{
    isEdit?: boolean;
    expense?: Expense;
}>();
const branches = ref()
const emits = defineEmits(["refresh"]);
const helpers = new Helpers();
const formRef = ref<FormInst | null>(null);
const message = useMessage();
const expensesService = new ExpensesService();
const loading = ref(false)
const formValue = ref<Expense>({
    name: null,
    id: null,
    branch: JSON.parse(sessionStorage.getItem("branches"))[0],
    branch_id: JSON.parse(sessionStorage.getItem("branches"))[0]["id"],
});
const rules = {
    name: {
        required: true,
        message: "Please input expense name",
        trigger: ["input", "blur"],
    },
    // branch_id: {
    //     required: true,
    //     message: "Please select branch",
    //     trigger: ["input", "blur"],
    // },
};
const notification = useNotification()

function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await expensesService.save(formValue.value, props.isEdit).finally(() => loading.value = false);
            if (!res.success) {
                const errorsString = helpers.generateResponseErrors(res)
                notification.error({
                    content: errorsString,
                    meta: "Unable to add expense",
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
    branches.value = JSON.parse(sessionStorage.getItem("branches"))
    if (props.isEdit) {
        formValue.value.name = props.expense?.name;
        formValue.value.id = props.expense?.id;
        formValue.value.branch = props.expense?.branch;
        formValue.value.branch_id = props.expense?.branch_id;
    }
});
</script>
