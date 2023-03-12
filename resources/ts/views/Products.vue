<template>
    <n-space vertical :size="12">
        <n-h1>Products</n-h1>
        <n-button type="primary" @click="showDialog = true;isEdit = false">
            <template #icon>
                <n-icon>
                    <grid-icon/>
                </n-icon>
            </template>
            Add Product
        </n-button>
        <n-data-table
            remote
            default-expand-all

            :columns="columns"
            :loading="loading"
            :data="products"
            :pagination="pagination"
            :bordered="false"
            @update:sorter="handleSorterChange"
        />
        <!--  Dialog  -->
        <n-modal v-model:show="showDialog">
            <n-card
                style="width: 1200px"
                title="Product details"
                :bordered="false"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <ProductForm :product="active" :is-edit="isEdit" @refresh="getProducts()"/>
            </n-card>
        </n-modal>


        <!--  Dialog  -->
        <n-modal v-model:show="showAddVariant">
            <n-card
                style="width: 1200px"
                title="Product variant details"
                :bordered="false"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <ProductVariantForm @refresh="getProducts()" :active-product-weight-type="activeProductWeightType"
                                    :product_id="activeProductIdToAddVariant"/>
            </n-card>
        </n-modal>


    </n-space>
</template>
<script setup lang="ts">
import {NH1, NDataTable, NText, NButton, NCard, NIcon, NModal, NSpace, NImage, NRow, NFormItemRow, NH3} from "naive-ui";
import {ProductService} from "../services/ProductService";
import {Helpers} from "../helpers";
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {GridOutline as GridIcon, Pencil} from "@vicons/ionicons5";
import ProductForm from "../forms/ProductForm.vue";
import ListOfVariants from "../components/ListOfVariants.vue";
import ProductVariantForm from "../forms/ProductVariantForm.vue";


const active = ref<Product>({} as Product);
const sortBy = ref("id")
const orderBy = ref("desc")
const showDialog = ref(false);
const showAddVariant = ref(false);
const isEdit = ref(false);
const activeProductIdToAddVariant = ref<number>();
const activeProductWeightType = ref<string>();
const productService = new ProductService();
const products = ref<Product[]>([]);
const loading = ref<boolean>(true);
const helpers = new Helpers();
const columns: TableColumns<any> = [
    {
        type: 'expand',
        expandable: (rowData: Product) => rowData.variants.length > 0,
        renderExpand: (rowData: Product) => {
            return h(
                "div",
                {},
                {
                    default: () => [
                        h(
                            NH3,
                            {},
                            {default: () => "Variations"}
                        ),
                        h(
                            ListOfVariants,
                            {variants: rowData.variants, weightUnit: rowData.weight},
                            {default: () => ''}
                        )
                    ]
                }
            )
        }
    },
    {
        title: "ID",
        align: "center",
        key: "id",
        sorter: true,

    },
    {
        title: "Name",
        align: "center",
        key: "name",
        sorter: true,
    },
    {
        title: "Branch",
        align: "center",
        key: "branch_id",
        render(row: Product) {
            return h(
                NText,
                {
                    type: "primary"
                },
                {
                    default: () => row.branch?.name,
                }
            );
        },
        sorter: true,
    },
    {
        title: "Alert when remaining ",
        align: "center",
        key: "alert_when_remaining",
        sorter: true,
    },
    {
        title: "Quantity (Pieces)",
        align: "center",
        key: "total_earnings",
        render(row: Product) {
            return h(
                NText,
                {
                    type: "primary"
                },
                {
                    default: () => helpers.getPiecesQuantity(row.variants),
                }
            );
        },
        sorter: true,
    },
    {
        title: "Total Earnings",
        align: "center",
        key: "total_earnings",
        render(row: Product) {
            return h(
                NText,
                {
                    type: "primary"
                },
                {
                    default: () => helpers.format(row.total_earnings) + "$",
                }
            );
        },
        sorter: true,
    },
    {
        title: "Total Pieces Sold",
        align: "center",
        key: "total_pieces_sold",
        render(row: Product) {
            return h(
                NText,
                {
                    type: "primary"
                },
                {
                    default: () => helpers.format(row.total_pieces_sold) + " Pieces",
                }
            );
        },
        sorter: true,
    },
    {
        title: "Action",
        key: "actions",
        render(row: Product) {
            return h(
                NSpace,
                {
                    size: 4
                },
                {
                    default: () => [
                        h(NButton, {type: "primary", onClick: () => showEdit(row)}, {default: () => "Edit"}),
                        row.variants.length == 0 ? h(
                            NButton,
                            {
                                type: "info",
                                onClick: () => {
                                    showAddVariant.value = true;
                                    activeProductIdToAddVariant.value = row.id
                                    activeProductWeightType.value = row.weight
                                }
                            },
                            {
                                default: () => "Add Variants"
                            }) : h("div", {}, {default: () => ""})
                    ]
                }
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
    pageSizes: [15, 50, 100],

    onChange: (page: number) => {
        pagination.page = page
    },
    onUpdatePageSize: (pageSize: number) => {
        pagination.pageSize = pageSize
        pagination.page = 1

    }
})

function showEdit(product: Product) {
    active.value = product;
    isEdit.value = true;
    showDialog.value = true;
}

async function getProducts() {
    showDialog.value = false;
    showAddVariant.value = false
    active.value = undefined;
    isEdit.value = false;
    loading.value = true;
    await productService.all(pagination.page, pagination.pageSize, sortBy.value, orderBy.value).then((res) => {
        products.value = res.data["data"] as Product[];
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
    await getProducts();
});


watch([pagination, sortBy, orderBy], () => {
    getProducts();
});

</script>
