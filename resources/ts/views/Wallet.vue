<template>
    <n-space vertical :size="24">
        <n-h1>Wallet</n-h1>
        <WalletTop @refresh="getOperations"/>
        <n-grid cols="4" x-gap="12">
            <n-form-item-grid-item label="Date Range">
                <n-date-picker v-model:value="range" type="daterange" close-on-select/>
            </n-form-item-grid-item>

            <n-form-item-grid-item label="Source">
                <n-select v-model:value="type"
                          :options="options">
                </n-select>
            </n-form-item-grid-item>
            <n-form-item-grid-item path="branch_id" label="Branch">
                <n-select
                    filterable
                    placeholder="Please select a branch"
                    :options="branches"
                    label-field="name"
                    value-field="id"
                />
            </n-form-item-grid-item>
            <n-grid-item>
                <n-form-item path="currency_id" label="Currency">
                    <n-select
                        filterable
                        placeholder="Please select a currency"
                        :options="currencies"
                        label-field="name"
                        value-field="id"
                    />
                </n-form-item>
            </n-grid-item>
        </n-grid>
        <n-data-table
            remote
            default-expand-all
            :columns="columns"
            :loading="loading"
            :data="operations"
            :pagination="pagination"
            :bordered="false"
            @update:sorter="handleSorterChange"
        />

    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NIcon, NSpace} from "naive-ui";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import WalletTop from "../components/WalletTop.vue";
import {AccountingService} from "../services/AccountingService";
import {EyeOutline} from "@vicons/ionicons5";
import {ref} from "vue";

const branches = ref()
const currencies = ref()
const tomorrow = new Date().setDate(new Date().getDate() + 1)
const range = ref<[number, number]>([Date.now(), tomorrow])
const sortBy = ref("created_at")
const orderBy = ref("asc")
const isEdit = ref(false);
const accountingService = new AccountingService();
const operations = ref<WalletOperation[]>([]);
const loading = ref<boolean>(true);
const helpers = new Helpers();
const type = ref("*")
const options = [
    {
        "label": "Collect Payment",
        "value": "payment_in",
    },
    {
        "label": "Pay",
        "value": "payment_out",
    },
    {
        "label": "Sale",
        "value": "invoice_in",
    },
    {
        "label": "Purchase",
        "value": "invoice_out",
    },
    {
        "label": "Expense",
        "value": "expense",
    },

    {
        "label": "All",
        "value": "*",
    },
]
const columns: TableColumns<any> = [
    {
        title: "Source",
        align: "center",
        key: "type",
        render(row: WalletOperation) {
            return h(
                NText,
                {
                    type: ["expense", "payment_out"].includes(row.type) ? "warning" : "primary",
                },
                {
                    default: () => helpers.uppercase(row.type, true),
                }
            );
        },

    },
    {
        title: "Amount",
        align: "center",
        key: "amount",
        render(row: WalletOperation) {
            return h(
                NText,
                {
                    type: ["expense", "payment_out"].includes(row.type) ? "warning" : "primary",
                },
                {
                    default: () => helpers.format(row.amount) + row.currency.symbol,
                }
            );
        },
        sorter: true,
    },
    {
        title: "Rate",
        align: "center",
        key: "rate",
        render(row: WalletOperation) {
            return h(
                NText,
                {
                    type: ["expense", "payment_out"].includes(row.type) ? "warning" : "primary",
                },
                {
                    default: () => helpers.format(row.rate),
                }
            );
        },
    },
    {
        title: "Currency Was",
        align: "center",
        key: "currency_was",
        render(row: WalletOperation) {
            return h(
                NText,
                {
                    type: ["expense", "payment_out"].includes(row.type) ? "warning" : "primary",
                },
                {
                    default: () => helpers.format(row.currency_was) + row.currency.symbol,
                }
            );
        },
        sorter: true,
    },
    {
        title: "Currency Became",
        align: "center",
        key: "currency_became",
        render(row: WalletOperation) {
            return h(
                NText,
                {
                    type: ["expense", "payment_out"].includes(row.type) ? "warning" : "primary",
                },
                {
                    default: () => helpers.format(row.currency_became) + row.currency.symbol,
                }
            );
        },
        sorter: true,
    },
    {
        title: "Time",
        key: "created_at",
        align: "center",
        render(row) {
            return h(
                NText,
                {
                    type: ["expense", "payment_out"].includes(row.type) ? "warning" : "primary",
                    bordered: false,
                },
                {
                    default: () => helpers.parseDate(new Date(row.created_at)),
                }
            );
        },
    },
    {
        title: "Action",
        key: "actions",
        render(row: Product) {
            return h(
                NButton,
                {
                    quaternary: true,
                    type: "primary",
                    size: "small",
                    // onClick: () => showEdit(row),
                    renderIcon: () => h(NIcon, {size: 20}, {default: () => h(EyeOutline)}),
                },
            );
        },
    },
];
const pagination = reactive({
    page: 1,
    pageSize: 15,
    itemCount: 0,
    pageCount: 0,
    showSizePicker: true,
    pageSizes: [3, 15, 50, 100],

    onChange: (page: number) => {
        pagination.page = page
    },
    onUpdatePageSize: (pageSize: number) => {
        pagination.pageSize = pageSize
        pagination.page = 1

    }
})
watch([pagination, range, sortBy, orderBy, type], () => {
    getOperations();
});


async function getOperations() {
    loading.value = true;
    await accountingService.all(pagination.page, pagination.pageSize, sortBy.value, orderBy.value, range.value[0], range.value[1], type.value).then((res) => {
        operations.value = res.data["data"] as WalletOperation[];
        pagination.itemCount = res.data["meta"]["total"]
        pagination.pageCount = res.data["meta"]["last_page"]
    }).finally(() => (loading.value = false));

}

function handleSorterChange(sorter) {

    let order = sorter.order
    if (!order) {
        order = "desc"
    } else {
        if (order == "descend") {
            order = "desc"
        } else {
            order = "asc"
        }
    }
    orderBy.value = order
    sortBy.value = sorter.columnKey
    pagination.page = 1
    pagination.pageSize = 15
}

onMounted(async () => {
    await getOperations();
    branches.value = JSON.parse(sessionStorage.getItem("branches"))
    currencies.value = JSON.parse(sessionStorage.getItem("currencies"))

});

</script>
