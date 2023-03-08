<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="4">
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
            <!--Category-->
            <n-grid-item>
                <n-form-item label="Category" path="category_id">
                    <n-select
                        filterable
                        placeholder="Please select a category"
                        :options="categories"
                        v-model:value="formValue.category_id"
                        label-field="name"
                        value-field="id"
                    />
                </n-form-item>
            </n-grid-item>
            <!--Name-->
            <n-grid-item>
                <n-form-item label="Name " path="name">
                    <n-input placeholder="Please select product name" style="width: 100%"
                             v-model:value="formValue.name"/>
                </n-form-item>
            </n-grid-item>
            <!--Location-->
            <n-grid-item>
                <n-form-item label="Location " path="location">
                    <n-input placeholder="Please select product location" style="width: 100%"
                             v-model:value="formValue.location"/>
                </n-form-item>
            </n-grid-item>
            <!--Material-->
            <n-grid-item>
                <n-form-item label="Material" path="material_id">
                    <n-select
                        filterable
                        placeholder="Please select a material"
                        :options="materials"
                        v-model:value="formValue.material_id"
                        label-field="name"
                        value-field="id"
                    />
                </n-form-item>
            </n-grid-item>
            <!--Weight-->
            <n-grid-item>
                <n-form-item label="Weight Unit" path="weight">
                    <n-select
                        filterable
                        placeholder="Please select a weight unit"
                        :options="[{label:'Liter',value:'Liter'},{label:'KG',value:'KG'}]"
                        v-model:value="formValue.weight"
                    />
                </n-form-item>
            </n-grid-item>
            <!--Alert if lower than-->
            <n-grid-item>
                <n-form-item label="Alert if lower than" path="alert_if_lower_than">
                    <n-input-number placeholder="Please enter when to alert" style="width: 100%"
                                    v-model:value="formValue.alert_when_remaining">
                        <template #suffix>
                            pieces
                        </template>
                    </n-input-number>
                </n-form-item>
            </n-grid-item>
            <n-grid-item>
                <n-form-item>
                    <n-button :disabled="loading" :loading="loading" @click="handleValidateClick" type="primary">Save
                        product
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
import {CategoryService} from "../services/CategoryService";
import {ProductService} from "../services/ProductService";
import {useGlobalStore} from "../store";

const categories = ref([])
const branches = ref([])
const materials = ref([])


const props = defineProps<{
    isEdit?: boolean;
    product?: Product;
}>();
const emits = defineEmits(["refresh"]);
const helpers = new Helpers();
const formRef = ref<FormInst | null>(null);
const message = useMessage();
const loading = ref(false)
const formValue = ref<Product>({
    id: null,
    category_id: null,
    branch_id: null,
    name: null,
    material_id: null,
    location: null,
    weight: null,
    alert_when_remaining: 10

});
const rules = {
    name: {
        required: true,
        message: "Please input product name",
        trigger: ["input", "blur"],
    },
    location: {
        required: true,
        message: "Please input product location",
        trigger: ["input", "blur"],
    },
    material_id: {
        type: "number",
        required: true,
        message: "Please select material",
        trigger: ["input", "blur"],
    },
    category_id: {
        type: "number",
        required: true,
        message: "Please select category",
        trigger: ["input", "blur"],
    },
    branch_id: {
        type: "number",
        required: true,
        message: "Please select branch",
        trigger: ["input", "blur"],
    },
    weight: {
        required: true,
        message: "Please select weight",
        trigger: ["input", "blur"],
    },

    alert_when_remaining: {
        required: true,
        message: "Please select when to alert ",
        trigger: ["input", "blur"],
    },

};
const notification = useNotification()

function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await new ProductService().save(formValue.value, props.isEdit).finally(() => loading.value = false);
            if (!res.success) {
                const errorsString = helpers.generateResponseErrors(res)
                notification.error({
                    content: errorsString,
                    meta: "Unable to add product",
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
        formValue.value.name = props.product?.name || "";
        formValue.value.weight = props.product?.weight;
        formValue.value.category_id = props.product?.category_id;
        formValue.value.branch_id = props.product?.branch_id;
        formValue.value.material = props.product?.material;
        formValue.value.location = props.product?.location;
        formValue.value.id = props.product?.id || 0;
    }
// get categories and branches
    categories.value = useGlobalStore().categories
    branches.value = useGlobalStore().branches
    materials.value = useGlobalStore()._materials
    formValue.value.category_id = categories.value[0].id
    formValue.value.branch_id = branches.value[0].id
});
</script>
