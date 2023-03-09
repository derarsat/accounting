<template>
    <n-layout has-sider>
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
            <n-spin size="large" :show="useGlobalStore().loading">
                <template #icon>
                    <n-icon>
                        <Reload />
                    </n-icon>
                </template>
                <router-view/>
            </n-spin>
        </n-layout-content>
    </n-layout>
</template>

<script lang="ts" setup>

import {useGlobalStore} from "../store";

const store = useGlobalStore()
import {h, ref, Component, onMounted} from "vue";
import {NIcon} from "naive-ui";
import {
    Cash,
    Pulse,
    Reload,
    Shuffle,
    GridOutline,
    PeopleOutline,
    FileTrayOutline,
    SettingsOutline,
    StorefrontOutline,
    ReceiptOutline,
    LibraryOutline, CubeOutline, BagOutline, BalloonOutline, WalletOutline, ColorFilterOutline
} from "@vicons/ionicons5";
import {RouterLink} from "vue-router";


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
            {
                label: renderLabel({title: "Currencies"}),
                key: "currencies",
                icon: renderIcon(Cash),
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
                label: renderLabel({title: "Expenses"}),
                key: "expenses",
                icon: renderIcon(BalloonOutline),
            },
            {
                label: renderLabel({title: "Wallet"}),
                key: "wallet",
                icon: renderIcon(BagOutline),
            },
            {
                label: renderLabel({title: "Payments"}),
                key: "payments",
                icon: renderIcon(ReceiptOutline),
            },
        ]
    },

];

onMounted(() => store.getConfig());
</script>
