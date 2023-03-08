<template>
    <n-space vertical :size="12">
        <n-h1>Expenses</n-h1>
        <n-button type="primary" @click="showDialog = true;isEdit = false">
            <template #icon>
                <n-icon>
                    <grid-icon/>
                </n-icon>
            </template>
            Add Expense
        </n-button>
        <n-data-table
            :columns="columns"
            :loading="loading"
            :data="expenses"
            :bordered="false"
        />
        <!--  Dialog  -->
        <n-modal v-model:show="showDialog">
            <n-card
                style="width: 600px"
                title="Expense details"
                :bordered="false"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <ExpenseForm :expense="active" :is-edit="isEdit" @refresh="getExpenses()"/>
            </n-card>
        </n-modal>
    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NCard, NIcon, NModal, NSpace} from "naive-ui";
import {ExpensesService} from "../services/ExpensesService";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {GridOutline as GridIcon, Pencil} from "@vicons/ionicons5";
import ExpenseForm from "../forms/ExpenseForm.vue";

const active = ref<Expense>({} as Expense);
const showDialog = ref(false);
const isEdit = ref(false);
const expenseService = new ExpensesService();
const expenses = ref<Expense[]>([]);
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
        align: "center",
        key: "branch",
        render(row: Expense) {
            return h(
                NText,
                {},
                {
                    default: () => row.branch.name,
                }
            );
        },
    },
    {
        title: "Total Cached Out",
        align: "center",
        key: "total",
        sorter: (row1, row2) => row1.total - row2.total,
        render(row: Expense) {
            return h(
                NText,
                {},
                {
                    default: () => row.total && helpers.format(row.total) + "$",
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
        render(row: Expense) {
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

function showEdit(expense: Expense) {
    showDialog.value = true;
    isEdit.value = true;
    active.value = expense;
}

async function getExpenses() {
    showDialog.value = false;
    active.value = undefined;
    isEdit.value = false;
    loading.value = true;
    const res = await expenseService.all().finally(() => (loading.value = false));
    expenses.value = res.data as Expense[];
}

onMounted(async () => {
    await getExpenses();
});
</script>
