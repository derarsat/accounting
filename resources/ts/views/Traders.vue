<template>
    <n-space vertical :size="24">
        <n-h1>Traders</n-h1>
        <n-grid :cols="6">
            <n-gi>
                <n-statistic label="Count" :value="getStatistic(OperationType.Count)"/>
            </n-gi>
            <n-gi>
                <n-statistic label="To Pay" :value="getStatistic(OperationType.ToPay)"/>
            </n-gi>
            <n-gi>
                <n-statistic label="To Collect" :value="getStatistic(OperationType.Collect)"/>
            </n-gi>
            <n-gi>
                <n-statistic label="Sold" :value="getStatistic(OperationType.Sold)"/>
            </n-gi>
            <n-gi>
                <n-statistic label="Purchased" :value="getStatistic(OperationType.Purchased)"/>
            </n-gi>
            <n-gi>
                <n-statistic label="Earned" :value="getStatistic(OperationType.Earned)"/>
            </n-gi>
        </n-grid>
        <n-space>
            <n-button type="primary" @click="showDialog = true;isEdit = false">
                <template #icon>
                    <n-icon>
                        <grid-icon/>
                    </n-icon>
                </template>
                Add Trader
            </n-button>
            <n-input style="width: 300px" clearable @keyup="searchTrader" v-model:value="search" type="text"
                     placeholder="Search for trader by name or phone">
                <template #prefix>
                    <n-icon :component="Search"/>
                </template>
            </n-input>
        </n-space>

        <n-data-table
            :columns="columns"
            :loading="loading"
            :data="traders"
            :bordered="false"
        />
        <!--  Dialog  -->
        <n-modal v-model:show="showDialog">
            <n-card
                style="width: 600px"
                title="Trader details"
                :bordered="false"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <TraderForm :trader="active" :is-edit="isEdit" @refresh="getTraders()"/>
            </n-card>
        </n-modal>
    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NCard, NIcon, NModal, NSpace} from "naive-ui";
import {TraderService} from "../services/TraderService";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {GridOutline as GridIcon, Pencil, Search} from "@vicons/ionicons5";
import TraderForm from "../forms/TraderForm.vue";

const active = ref<Trader>({} as Trader);
const showDialog = ref(false);
const isEdit = ref(false);
const traderService = new TraderService();
const traders = ref<Trader[]>([]);
const tempTraders = ref<Trader[]>([]);
const loading = ref<boolean>(true);
const helpers = new Helpers();
const search = ref()
const columns: TableColumns<any> = [

    {
        title: "Name",
        align: "center",
        key: "name",
    },
    {
        title: "Phone",
        align: "center",
        key: "phone",
    },

    {
        title: "To Pay",
        align: "center",
        key: "he_owes_us",
        sorter: (row1, row2) => row1.he_owes_us - row2.he_owes_us,

        render(row: Trader) {
            return h(
                NText,
                {},
                {
                    default: () => helpers.format(row.he_owes_us) + "$",
                }
            );
        },
    },
    {
        title: "To Collect",
        align: "center",
        key: "we_owe_him",
        sorter: (row1, row2) => row1.we_owe_him - row2.we_owe_him,
        render(row: Trader) {
            return h(
                NText,
                {
                    type: "error"
                },
                {
                    default: () => helpers.format(row.we_owe_him) + "$",
                }
            );
        },
    },
    {
        title: "Sold",
        align: "center",
        key: "we_sold_him",
        sorter: (row1, row2) => row1.we_sold_him - row2.we_sold_him,

        render(row: Trader) {
            return h(
                NText,
                {},
                {
                    default: () => helpers.format(row.we_sold_him) + "$",
                }
            );
        },
    },
    {
        title: "Purchased",
        align: "center",
        key: "he_sold_us",
        sorter: (row1, row2) => row1.he_sold_us - row2.he_sold_us,

        render(row: Trader) {
            return h(
                NText,
                {},
                {
                    default: () => helpers.format(row.he_sold_us) + "$",
                }
            );
        },
    },
    {
        title: "Earned",
        align: "center",
        key: "we_earned_from_him",
        sorter: (row1, row2) => row1.we_earned_from_him - row2.we_earned_from_him,

        render(row) {
            return h(
                NText,
                {
                    type: "primary",
                },
                {
                    default: () => helpers.format(row.we_earned_from_him) + "$",
                }
            );
        },
    },
    {
        title: "Action",
        key: "actions",
        render(row: Trader) {
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

function showEdit(trader: Trader) {
    showDialog.value = true;
    isEdit.value = true;
    active.value = trader;
}

async function getTraders() {
    showDialog.value = false;
    active.value = undefined;
    isEdit.value = false;
    loading.value = true;
    const res = await traderService.all().finally(() => (loading.value = false));
    traders.value = res.data as Trader[];
    tempTraders.value = res.data as Trader[];
}

onMounted(async () => {
    await getTraders();
});


function getStatistic(type: OperationType) {
    let result
    if (type === OperationType.Count) {
        result = traders.value.length
    }

    if (type === OperationType.ToPay) {
        result = new Helpers().format(traders.value.reduce((n, {he_owes_us}) => n + he_owes_us, 0)) + "$"
    }

    if (type === OperationType.Earned) {
        result = new Helpers().format(traders.value.reduce((n, {we_earned_from_him}) => n + we_earned_from_him, 0)) + "$"
    }
    if (type === OperationType.Purchased) {
        result = new Helpers().format(traders.value.reduce((n, {he_sold_us}) => n + he_sold_us, 0)) + "$"
    }
    if (type === OperationType.Sold) {
        result = new Helpers().format(traders.value.reduce((n, {we_sold_him}) => n + we_sold_him, 0)) + "$"
    }
    if (type === OperationType.Collect) {
        result = new Helpers().format(traders.value.reduce((n, {we_owe_him}) => n + we_owe_him, 0)) + "$"
    }
    return result

}


enum OperationType {
    ToPay = "toPay",
    Count = "count",
    Sold = "sold",
    Purchased = "purchased",
    Earned = "earned",
    Collect = "collect"

}

watch(search, () => searchTrader())

function searchTrader() {
    const res = tempTraders.value.filter((trader: Trader) => {
        return trader.name.indexOf(search.value) > -1 || trader.phone.indexOf(search.value) > -1
    })
    if (res) {
        traders.value = res
    } else {
        traders.value = tempTraders.value
    }
}
</script>
