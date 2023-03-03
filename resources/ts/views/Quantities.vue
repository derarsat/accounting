<template>
    <n-space vertical :size="12">
        <n-h1>Quantities</n-h1>
        <n-button type="primary" @click="showDialog = true;isEdit = false">
            <template #icon>
                <n-icon>
                    <grid-icon/>
                </n-icon>
            </template>
            Add Quantity
        </n-button>
        <n-data-table
            :columns="columns"
            :loading="loading"
            :data="quantities"
            :bordered="false"
        />
        <!--  Dialog  -->
        <n-modal v-model:show="showDialog">
            <n-card
                style="width: 600px"
                title="Quantity details"
                :bordered="false"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <QuantityForm :quantity="active" :is-edit="isEdit" @refresh="getQuantities()"/>
            </n-card>
        </n-modal>
    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NCard, NIcon, NModal, NSpace} from "naive-ui";
import {QuantityService} from "../services/QuantityService";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {GridOutline as GridIcon, Pencil} from "@vicons/ionicons5";
import QuantityForm from "../forms/QuantityForm.vue";

const active = ref<Quantity>({} as Quantity);
const showDialog = ref(false);
const isEdit = ref(false);
const quantityService = new QuantityService();
const quantities = ref<Quantity[]>([]);
const loading = ref<boolean>(true);
const helpers = new Helpers();
const columns: TableColumns<any> = [

    {
        title: "Name",
        align: "center",
        key: "name",
    },
    {
        title: "Number Of Pieces",
        align: "center",
        key: "number_of_pieces",
    },
    {
        title: "Last update",
        key: "updated_at",
        align: "center",
        render(row) {
            return h(
                NText,
                {
                    type: "warning",
                    bordered: false,
                },
                {
                    default: () => helpers.getTimeAgo(new Date(row.updated_at)),
                }
            );
        },
    },
    {
        title: "Action",
        key: "actions",
        render(row: Quantity) {
            return h(
                NButton,
                {
                    strong: true,
                    size: "small",
                    onClick: () => showEdit(row),
                    renderIcon: () => h(NIcon, {size: 10}, {default: () => h(Pencil)}),
                },
                {default: () => "Edit"}
            );
        },
    },
];

function showEdit(quantity: Quantity) {
    showDialog.value = true;
    isEdit.value = true;
    active.value = quantity;
}

async function getQuantities() {
    showDialog.value = false;
    active.value = undefined;
    isEdit.value = false;
    loading.value = true;
    const res = await quantityService.all().finally(() => (loading.value = false));
    quantities.value = res.data as Quantity[];
}

onMounted(async () => {
    await getQuantities();
});
</script>
