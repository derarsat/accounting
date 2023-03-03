<template>
    <n-space vertical :size="12">
        <n-h1>Materials</n-h1>
        <n-button type="primary" @click="showDialog = true;isEdit = false">
            <template #icon>
                <n-icon>
                    <grid-icon/>
                </n-icon>
            </template>
            Add Material
        </n-button>
        <n-data-table
            :columns="columns"
            :loading="loading"
            :data="materials"
            :bordered="false"
        />
        <!--  Dialog  -->
        <n-modal v-model:show="showDialog">
            <n-card
                style="width: 600px"
                title="Material details"
                :bordered="false"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <MaterialForm :material="active" :is-edit="isEdit" @refresh="getMaterials()"/>
            </n-card>
        </n-modal>
    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NCard, NIcon, NModal, NSpace} from "naive-ui";
import {MaterialService} from "../services/MaterialService";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {GridOutline as GridIcon, Pencil} from "@vicons/ionicons5";
import MaterialForm from "../forms/MaterialForm.vue";

const active = ref<Material>({} as Material);
const showDialog = ref(false);
const isEdit = ref(false);
const materialService = new MaterialService();
const materials = ref<Material[]>([]);
const loading = ref<boolean>(true);
const helpers = new Helpers();
const columns: TableColumns<any> = [

    {
        title: "Name",
        align: "center",
        key: "name",
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
        render(row: Material) {
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

function showEdit(material: Material) {
    showDialog.value = true;
    isEdit.value = true;
    active.value = material;
}

async function getMaterials() {
    showDialog.value = false;
    active.value = undefined;
    isEdit.value = false;
    loading.value = true;
    const res = await materialService.all().finally(() => (loading.value = false));
    materials.value = res.data as Material[];
}

onMounted(async () => {
    await getMaterials();
});
</script>
