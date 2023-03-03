<template>
    <n-space vertical :size="12">
        <n-h1>Branches</n-h1>
        <n-button type="primary" @click="showDialog = true;isEdit = false">
            <template #icon>
                <n-icon>
                    <grid-icon/>
                </n-icon>
            </template>
            Add Branch
        </n-button>
        <n-data-table
            :columns="columns"
            :loading="loading"
            :data="branches"
            :bordered="false"
        />
        <!--  Dialog  -->
        <n-modal v-model:show="showDialog">
            <n-card
                style="width: 600px"
                title="Branch details"
                :bordered="false"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <BranchForm :branch="active" :is-edit="isEdit" @refresh="getBranches()"/>
            </n-card>
        </n-modal>
    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NCard, NIcon, NModal, NSpace} from "naive-ui";
import {BranchService} from "../services/BranchService";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {GridOutline as GridIcon, Pencil} from "@vicons/ionicons5";
import BranchForm from "../forms/BranchForm.vue";

const active = ref<Branch>({} as Branch);
const showDialog = ref(false);
const isEdit = ref(false);
const branchService = new BranchService();
const branches = ref<Branch[]>([]);
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
        render(row: Branch) {
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

function showEdit(branch: Branch) {
    showDialog.value = true;
    isEdit.value = true;
    active.value = branch;
}

async function getBranches() {
    showDialog.value = false;
    active.value = undefined;
    isEdit.value = false;
    loading.value = true;
    const res = await branchService.all().finally(() => (loading.value = false));
    branches.value = res.data as Branch[];
}

onMounted(async () => {
    await getBranches();
});
</script>
