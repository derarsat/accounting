<template>
    <n-space vertical :size="12">
        <n-h1>Currencies</n-h1>
        <n-button type="primary" @click="showDialog = true;isEdit = false">
            <template #icon>
                <n-icon>
                    <cash-icon/>
                </n-icon>
            </template>
            Add Currency
        </n-button>
        <n-data-table
            :columns="columns"
            :loading="loading"
            :data="currencies"
            :bordered="false"
        />
        <!--  Dialog  -->
        <n-modal v-model:show="showDialog">
            <n-card
                style="width: 600px"
                title="Currency details"
                :bordered="false"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <CurrencyForm :currency="active" :is-edit="isEdit" @refresh="getCurrencies()"/>
            </n-card>
        </n-modal>
    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NCard, NIcon, NModal, NSpace} from "naive-ui";
import {CurrencyService} from "../services/CurrencyService";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {CashOutline as CashIcon, Pencil} from "@vicons/ionicons5";
import CurrencyForm from "../forms/CurrencyForm.vue";

const active = ref<Currency>({} as Currency);
const showDialog = ref(false);
const isEdit = ref(false);
const currencyService = new CurrencyService();
const currencies = ref<Currency[]>([]);
const loading = ref<boolean>(true);
const helpers = new Helpers();
const columns: TableColumns<any> = [

    {
        title: "Name",
        align: "center",
        key: "name",
    },
    {
        title: "Symbol",
        key: "symbol",
        align: "center",
    },
    {
        title: "Rate",
        key: "rate",
        align: "center",
        render(row) {
            return h(
                NText,
                {
                    type: "default",
                },
                {
                    default: () => helpers.format(row.rate),
                }
            );
        },
    },
    {
        title: "Amount",
        key: "amount",
        align: "center",
        render(row) {
            return h(
                NText,
                {
                    type: "primary",
                },
                {
                    default: () => helpers.format(row.amount),
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
        render(row: Currency) {
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

function showEdit(currency: Currency) {
    showDialog.value = true;
    isEdit.value = true;
    active.value = currency;
}

async function getCurrencies() {
    showDialog.value = false;
    active.value = undefined;
    isEdit.value = false;
    loading.value = true;
    const res = await currencyService.all().finally(() => (loading.value = false));
    currencies.value = res.data as Currency[];
}

onMounted(async () => {
    await getCurrencies();
});
</script>
