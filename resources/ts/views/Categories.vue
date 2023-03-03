<template>
    <n-space vertical :size="12">
        <n-h1>Categories</n-h1>
        <n-button type="primary" @click="showDialog = true;isEdit = false">
            <template #icon>
                <n-icon>
                    <grid-icon/>
                </n-icon>
            </template>
            Add Category
        </n-button>
        <n-data-table
            :columns="columns"
            :loading="loading"
            :data="categories"
            :bordered="false"
        />
        <!--  Dialog  -->
        <n-modal v-model:show="showDialog">
            <n-card
                style="width: 600px"
                title="Category details"
                :bordered="false"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <CategoryForm :category="active" :is-edit="isEdit" @refresh="getCategories()"/>
            </n-card>
        </n-modal>
    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NCard, NIcon, NModal, NSpace} from "naive-ui";
import {CategoryService} from "../services/CategoryService";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {GridOutline as GridIcon, Pencil} from "@vicons/ionicons5";
import CategoryForm from "../forms/CategoryForm.vue";

const active = ref<Category>({} as Category);
const showDialog = ref(false);
const isEdit = ref(false);
const categoryService = new CategoryService();
const categories = ref<Category[]>([]);
const loading = ref<boolean>(true);
const helpers = new Helpers();
const columns: TableColumns<any> = [

    {
        title: "Name",
        align: "center",
        key: "name",
    },
    {
        title: "Branch",
        key: "branch",
        align: "center",
        render(row: Category) {
            return h(
                NText,
                {},
                {
                    default: () => row.branch?.name,
                }
            );
        },
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
        render(row: Category) {
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

function showEdit(category: Category) {
    showDialog.value = true;
    isEdit.value = true;
    active.value = category;
}

async function getCategories() {
    showDialog.value = false;
    active.value = undefined;
    isEdit.value = false;
    loading.value = true;
    const res = await categoryService.all().finally(() => (loading.value = false));
    categories.value = res.data as Category[];
}

onMounted(async () => {
    await getCategories();
});
</script>
