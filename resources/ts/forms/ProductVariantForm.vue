<template>
    <n-form ref="formRef" :model="formValue" :rules="rules">
        <n-grid :x-gap="24" :y-gap="4" :cols="4">
            <n-form-item-grid-item label="Trader" path="trader_id">
                <n-select
                    filterable

                    :options="traders"
                    v-model:value="formValue.trader_id"
                    label-field="name"
                    value-field="id"
                />
            </n-form-item-grid-item>

            <n-form-item-grid-item label="Quantity type" path="quantity_id">
                <n-select
                    filterable

                    :options="quantities"
                    v-model:value="formValue.quantity_id"
                    label-field="name"
                    value-field="id"
                />
            </n-form-item-grid-item>

            <n-form-item-grid-item label="Quantity" path="quantity_value">
                <n-input-number style="width: 100%" v-model:value="formValue.quantity_value"/>
            </n-form-item-grid-item>


            <n-form-item-grid-item label="Extra quantity" path="extra_quantity">
                <n-input-number style="width: 100%" v-model:value="formValue.extra_quantity">
                    <template #suffix>
                        Pieces
                    </template>
                </n-input-number>
            </n-form-item-grid-item>


            <n-form-item-grid-item label="Purchased price" path="purchased">
                <n-input-number style="width: 100%" v-model:value="formValue.purchased">
                    <template #prefix>
                        $
                    </template>
                </n-input-number>
            </n-form-item-grid-item>

            <n-form-item-grid-item label="Min price" path="min_price">
                <n-input-number style="width: 100%" v-model:value="formValue.min_price">
                    <template #prefix>
                        $
                    </template>
                </n-input-number>
            </n-form-item-grid-item>

            <n-form-item-grid-item label="Price" path="price">
                <n-input-number style="width: 100%" v-model:value="formValue.price">
                    <template #prefix>
                        $
                    </template>
                </n-input-number>
            </n-form-item-grid-item>


            <n-form-item-grid-item label="Extra Cost" path="extra_cost">
                <n-input-number style="width: 100%" v-model:value="formValue.extra_cost">
                    <template #prefix>
                        $
                    </template>
                </n-input-number>
            </n-form-item-grid-item>

            <n-form-item-grid-item path="expire" label="Expire">
                <n-date-picker
                    style="width:100%"
                    v-model:formatted-value="formValue.expire"
                    value-format="yyyy-MM-dd"
                    type="date"
                    clearable
                />
            </n-form-item-grid-item>


            <n-form-item-grid-item label="Weight value" path="weight_value">
                <n-input-number style="width: 100%" v-model:value="formValue.weight_value">
                    <template #suffix>
                        {{ activeProductWeightType }}
                    </template>
                </n-input-number>
            </n-form-item-grid-item>


            <n-form-item-grid-item label="Barcode" path="barcode">
                <n-input style="width: 100%" v-model:value="formValue.barcode"/>
            </n-form-item-grid-item>

            <n-form-item-grid-item label="Identifier" path="identifier">
                <n-input style="width: 100%" v-model:value="formValue.identifier"/>
            </n-form-item-grid-item>




            <n-form-item-grid-item label="Has tva?" path="tva">
                <n-switch v-model:value="formValue.tva">
                    <template #unchecked>
                        Doesn't has tva
                    </template>
                    <template #checked>
                        Has tva
                    </template>
                </n-switch>
            </n-form-item-grid-item>

            <n-grid-item >
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
import {CSSProperties, ref} from "vue";
import {FormInst, useMessage} from "naive-ui";
import {Helpers} from "../helpers";
import {useNotification} from 'naive-ui'
import {CategoryService} from "../services/CategoryService";
import {ProductService} from "../services/ProductService";
import {useGlobalStore} from "../store";

const quantities = ref([])
const traders = ref([])
const props = defineProps<{
    product_id: number,
    activeProductWeightType: string
}>();
const emits = defineEmits(["refresh"]);
const helpers = new Helpers();
const formRef = ref<FormInst | null>(null);
const message = useMessage();
const loading = ref(false)
const formValue = ref<ProductVariant>({
    quantity_id: null,
    trader_id: null,
    quantity_value: null,
    purchased: null,
    min_price: null,
    price: null,
    tva: null,
    extra_cost: null,
    extra_quantity: null,
    weight_value: null,
    expire: null,
    product_id: null,
    image: null,
    barcode: null,
    identifier: null,
});
const rules = {
    trader_id: {
        type: "number",
        required: true,
        message: "Please select trader",
        trigger: ["input", "blur"],
    },
    quantity_id: {
        type: "number",
        required: true,
        message: "Please select quantity",
        trigger: ["input", "blur"],
    },
    quantity_value: {
        type: "number",
        required: true,
        message: "Please select quantity value",
        trigger: ["input", "blur"],
    },

    purchased: {
        type: "number",
        required: true,
        message: "Please select purchased",
        trigger: ["input", "blur"],
    },
    min_price: {
        type: "number",
        required: true,
        message: "Please select min price",
        trigger: ["input", "blur"],
    },
    price: {
        type: "number",
        required: true,
        message: "Please select price",
        trigger: ["input", "blur"],
    },
    extra_cost: {
        type: "number",
        message: "Please select price",
        trigger: ["input", "blur"],
    },
    extra_quantity: {
        type: "number",
        required: true,
        message: "Please select extra quantity",
        trigger: ["input", "blur"],
    },
    weight_value: {
        type: "number",
        required: true,
        message: "Please select weight value",
        trigger: ["input", "blur"],
    },
    tva: {
        type: "boolean",
        required: true,
        message: "Please choose if it has tva",
        trigger: ["input", "blur"],
    },
    expire: {
        type: "date",
        required: true,
        message: "Please choose expire date",
        trigger: ["input", "blur"],
    },
    barcode: {
        type: "string",
        required: true,
        message: "Please select barcode",
        trigger: ["input", "blur"],
    },
    identifier: {
        type: "string",
        required:true,
        message: "Please select identifier",
        trigger: ["input", "blur"],
    },

};
const notification = useNotification()

function handleValidateClick(e: MouseEvent) {
    e.preventDefault();
    formRef.value?.validate(async (errors) => {
        if (!errors) {
            loading.value = true
            const res = await new ProductService().addProductVariant(formValue.value).finally(() => loading.value = false);
            await useGlobalStore().getConfig()
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
    formValue.value.product_id = props.product_id || 0;
// get categories and branches
    quantities.value = useGlobalStore().quantities
    traders.value = useGlobalStore().traders
});


const railStyle = ({
                       focused,
                       checked
                   }: {
    focused: boolean
    checked: boolean
}) => {
    const style: CSSProperties = {}
    if (checked) {
        style.background = '#d03050'
        if (focused) {
            style.boxShadow = '0 0 0 2px #d0305040'
        }
    } else {
        style.background = '#2080f0'
        if (focused) {
            style.boxShadow = '0 0 0 2px #2080f040'
        }
    }
    return style
}
</script>
