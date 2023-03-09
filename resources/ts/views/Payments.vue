<template>
    <n-space vertical :size="24">
        <n-h1>Payments</n-h1>
        <n-grid cols="4" x-gap="12">
            <n-form-item-grid-item label="Date Range">
                <n-date-picker clearable v-model:value="range" type="daterange" close-on-select/>
            </n-form-item-grid-item>

            <n-form-item-grid-item label="Source">
                <n-select v-model:value="type"
                          :options="options">
                </n-select>
            </n-form-item-grid-item>
        </n-grid>
        <n-data-table
            remote
            default-expand-all
            :columns="columns"
            :loading="loading"
            :data="payments"
            :pagination="pagination"
            :bordered="false"
            @update:sorter="handleSorterChange"
        />

    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NIcon, NSpace, NRow} from "naive-ui";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {AccountingService} from "../services/AccountingService";
import {EyeOutline, TrendingDownOutline, TrendingUpOutline} from "@vicons/ionicons5";
import {ref} from "vue";
import {useGlobalStore} from "../store";

const branches = computed(() => useGlobalStore().branches)
const currencies = computed(() => useGlobalStore().currencies)
const tomorrow = new Date().setDate(new Date().getDate() + 2)
const now = new Date().valueOf()
const range = ref<[number, number]>([now, tomorrow])

console.log(range.value)
const sortBy = ref("created_at")
const orderBy = ref("asc")
const isEdit = ref(false);
const accountingService = new AccountingService();
const payments = ref<Payment[]>([]);
const loading = ref<boolean>(true);
const helpers = new Helpers();
const type = ref("*")
const options = [
    {
        "label": "Payment In",
        "value": "in",
    },
    {
        "label": "Payment Out",
        "value": "out",
    },

]
const columns: TableColumns<any> = [
    {
        title: "Type",
        align: "left",
        key: "type",
        render(row: Payment) {
            return h(
                NText,
                {
                    type: row.type == "out" ? "warning" : "primary",
                },
                {
                    default: () => helpers.uppercase(row.type, true),
                }
            );
        },
    },
    {
        title: "Trader",
        align: "left",
        key: "trader_id",
        sorter: true,
        render(row: Payment) {
            return h(
                NText,
                {
                    type: row.type == "out" ? "warning" : "primary",
                },
                {
                    default: () => helpers.uppercase(row.trader.name, true),
                }
            );
        },
    },
    {
        title: "Branch",
        align: "left",
        key: "branch_id",
        sorter: true,
        render(row: Payment) {
            return h(
                NText,
                {
                    type: row.type == "out" ? "warning" : "primary",
                },
                {
                    default: () => helpers.uppercase(row.branch.name, true),
                }
            );
        },
    },
    {
        title: "Currency",
        align: "left",
        key: "currency_id",
        sorter: true,
        render(row: Payment) {
            return h(
                NText,
                {
                    type: row.type == "out" ? "warning" : "primary",
                },
                {
                    default: () => helpers.uppercase(row.currency.symbol, true),
                }
            );
        },
    },
    {
        title: "Amount",
        align: "left",
        key: "amount",
        render(row: Payment) {
            return h(
                NText,
                {
                    type: row.type == "out" ? "warning" : "primary",
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
        align: "left",
        key: "rate",
        sorter: true,
        render(row: Payment) {
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
        title: "Currency movement",
        align: "left",
        key: "currency_was",
        render(row: Payment) {
            return h(
                NSpace,
                {
                    size: 4,
                    align: "center"
                },
                {
                    default: () => [
                        h(
                            NText,
                            {
                                type: row.type == "out" ? "warning" : "primary",
                            },
                            {
                                default: () => helpers.format(row.currency_was) + row.currency.symbol,
                            }
                        ),
                        h(
                            NButton,
                            {
                                type: row.currency_was < row.currency_became ? "primary" : "warning",
                                quaternary: true,
                                size: "small",
                            },
                            {
                                default: () => h(
                                    NIcon,
                                    {
                                        size:20

                                    },
                                    {
                                        default: () => h(row.currency_was < row.currency_became ? TrendingUpOutline : TrendingDownOutline, {}, {default: () => ''})
                                    }
                                ),
                            }
                        ),
                        h(
                            NText,
                            {
                                type: row.type == "out" ? "warning" : "primary",
                            },
                            {
                                default: () => helpers.format(row.currency_became) + row.currency.symbol,
                            }
                        )
                    ]
                }
            );
        },
        sorter: true,
    },
    {
        title: "Account movement",
        align: "left",
        key: "current_account_was",
        render(row: Payment) {
            return h(
                NSpace,
                {
                    size: 4,
                    align: "center"
                },
                {
                    default: () => [
                        h(
                            NText,
                            {
                                type: row.type == "out" ? "warning" : "primary",
                            },
                            {
                                default: () => helpers.format(row.current_account_was) + row.currency.symbol,
                            }
                        ),
                        h(
                            NButton,
                            {
                                type: row.current_account_was < row.current_account_became ? "primary" : "warning",
                                quaternary: true,
                                size: "small",
                            },
                            {
                                default: () => h(
                                    NIcon,
                                    {
                                        size:20
                                    },
                                    {
                                        default: () => h(row.current_account_was < row.current_account_became ? TrendingUpOutline : TrendingDownOutline, {}, {default: () => ''})
                                    }
                                ),
                            }
                        ),
                        h(
                            NText,
                            {
                                type: row.type == "out" ? "warning" : "primary",
                            },
                            {
                                default: () => helpers.format(row.current_account_became) + row.currency.symbol,
                            }
                        )
                    ]
                }
            );
        },
        sorter: true,
    },
    {
        title: "Time",
        key: "created_at",
        align: "left",
        render(row: Payment) {
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
    getPayments();
});


async function getPayments() {
    loading.value = true;
    await accountingService.getPayments(pagination.page, pagination.pageSize, sortBy.value, orderBy.value, range.value[0], range.value[1], type.value).then((res) => {
        payments.value = res.data["data"] as Payment[];
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
    await getPayments();

});

</script>
