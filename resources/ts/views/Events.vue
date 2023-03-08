<template>
    <n-space vertical :size="12">
        <n-h1>System Events</n-h1>
        <n-grid cols="12">
            <n-grid-item span="3">
                <n-h3>Date Range</n-h3>
                <n-date-picker v-model:value="range" type="daterange" clearable/>
            </n-grid-item>
        </n-grid>
        <n-data-table
            :columns="columns"
            :loading="loading"
            :data="events"
            :bordered="false"
        />
    </n-space>

</template>

<script setup lang="ts">
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {Helpers} from "../helpers";
import {EventsService} from "../services/EventsService";
import {onMounted} from "vue";
import {NText} from "naive-ui";

const loading = ref<boolean>(true);
const helpers = new Helpers();
const events = ref<SystemEventInterface[]>([]);
const systemEventsService = new EventsService();
const columns: TableColumns<any> = [

    {
        title: "Time",
        align: "center",
        key: "created_at",
        render(row: SystemEventInterface) {
            return h(
                NText,
                {
                    type: "success",
                    bordered: false,
                },
                {
                    default: () => helpers.parseDate(new Date(row.created_at)),
                }
            );
        },
    },
    {
        title: "User",
        key: "user",
        align: "center",
        render(row) {
            return h(
                NText,
                {
                    type: "info",
                },
                {
                    default: () => helpers.format(row.user),
                }
            );
        },
    },
    {
        title: "Source",
        key: "model",
        align: "center",
        render(row:SystemEventInterface) {
            return h(
                NText,
                {
                    type: "warning",
                },
                {
                    default: () => helpers.uppercase(row.model),
                }
            );
        },
        filterOptions: [
            {
                label: 'Currency',
                value: 'currency'
            },
            {
                label: 'Product',
                value: 'product'
            },
            {
                label: 'Supplier',
                value: 'supplier'
            }
        ],
        filter(value, row) {
            return row
        }
    },
    {
        title: "Description",
        key: "info",
        align: "center",
    },


];
const range = ref<[number, number]>([Date.now(), Date.now()])


async function getSystemEvents() {
    loading.value = true;
    const res = await systemEventsService.list().finally(() => (loading.value = false));
    events.value = res.data as SystemEventInterface[];
}

onMounted(() => getSystemEvents())
</script>
