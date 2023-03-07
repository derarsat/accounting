<template>
    <n-layout has-sider v-if="!loading">
        <n-layout-sider
            bordered
            show-trigger
            collapse-mode="width"
            :collapsed-width="64"
            :width="240"
            :native-scrollbar="false"
            style="min-height: 100vh"
        >
            <n-menu
                :collapsed-width="64"
                :collapsed-icon-size="22"
                :options="menuOptions"
            />
        </n-layout-sider>
        <n-layout-content content-style="padding:20px 40px">
            <router-view/>
        </n-layout-content>
    </n-layout>
</template>

<script lang="ts" setup>
import http from "../services/http";

import {h, ref, Component, onMounted} from "vue";
import {NIcon} from "naive-ui";
import {
    Cash,
    Pulse,
    Shuffle,
    GridOutline,
    PeopleOutline,
    FileTrayOutline,
    SettingsOutline,
    StorefrontOutline,
    LibraryOutline, CubeOutline, BagOutline, BalloonOutline, WalletOutline, ColorFilterOutline
} from "@vicons/ionicons5";
import {RouterLink} from "vue-router";
import {getUrl} from "../services/Urls";

const loading = ref(true)

function renderIcon(icon: Component) {
    return () => h(NIcon, null, {default: () => h(icon)});
}

function renderLabel(link: NavLinkProps) {
    return () =>
        h(
            RouterLink,
            {
                to: {
                    name: link.title,
                },
            },
            {default: () => link.title}
        );
}

const menuOptions = [
    {
        label: renderLabel({title: "Dashboard"}),
        key: "dashboard",
        icon: renderIcon(Pulse),
    },
    {
        label: "Catalog",
        key: "catalog",
        icon: renderIcon(StorefrontOutline),
        children: [
            {
                label: renderLabel({title: "Categories"}),
                key: "categories",
                icon: renderIcon(GridOutline),
            },
            {
                label: renderLabel({title: "Materials"}),
                key: "materials",
                icon: renderIcon(ColorFilterOutline),
            },
            {
                label: renderLabel({title: "Products"}),
                key: "products",
                icon: renderIcon(WalletOutline),
            },
        ]
    },
    // Settings
    {
        label: "System",
        key: "system",
        icon: renderIcon(SettingsOutline),
        children: [
            {
                label: renderLabel({title: "Branches"}),
                key: "branches",
                icon: renderIcon(FileTrayOutline),
            },
            {
                label: renderLabel({title: "Events"}),
                key: "events",
                icon: renderIcon(Shuffle),
            },
            {
                label: renderLabel({title: "Quantities"}),
                key: "quantities",
                icon: renderIcon(CubeOutline),
            },
        ]
    },
    {
        label: "Accounting",
        key: "accounting",
        icon: renderIcon(LibraryOutline),
        children: [
            {
                label: renderLabel({title: "Traders"}),
                key: "traders",
                icon: renderIcon(PeopleOutline),
            },
            {
                label: renderLabel({title: "Currencies"}),
                key: "currencies",
                icon: renderIcon(Cash),
            },
            {
                label: renderLabel({title: "Expenses"}),
                key: "expenses",
                icon: renderIcon(BalloonOutline),
            },
            {
                label: renderLabel({title: "Wallet"}),
                key: "wallet",
                icon: renderIcon(BagOutline),
            },
        ]
    },

];

async function getConfig() {
    await http.get(getUrl("config")).then(r => {
        sessionStorage.setItem("branches", JSON.stringify(r.data.branches))
        sessionStorage.setItem("categories", JSON.stringify(r.data.categories))
        sessionStorage.setItem("quantities", JSON.stringify(r.data.quantities))
        sessionStorage.setItem("expenses", JSON.stringify(r.data.expenses))
        sessionStorage.setItem("currencies", JSON.stringify(r.data.currencies))
        sessionStorage.setItem("traders", JSON.stringify(r.data.traders))
        loading.value = false
    })
}

onMounted(() => getConfig());
</script>
