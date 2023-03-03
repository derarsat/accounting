<template>
    <n-data-table :columns="columns" :data="variants">

    </n-data-table>
</template>

<script lang="ts">
import {TableColumns} from "naive-ui/es/data-table/src/interface";
import {NText} from "naive-ui";
import {Helpers} from "../helpers";

export default defineComponent({
    props: {
        variants: Array,
        weightUnit: String
    },
    setup(props) {
        const helpers = new Helpers()
        const columns: TableColumns = [
            {
                title: "Quantity",
                align: "center",
                key: "quantity",
                render(row: ProductVariant) {
                    return h(
                        NText,
                        {
                            type: "primary"
                        },
                        {
                            default: () => row?.quantity_value + " " + row?.quantity.name,
                        }
                    );
                },
            },
            {
                title: "Trader",
                align: "center",
                key: "trader",
                render(row: ProductVariant) {
                    return h(
                        NText,
                        {},
                        {
                            default: () => row.trader.name,
                        }
                    );
                },
            },
            {
                title: "Weight",
                align: "center",
                key: "weight_value",
                render(row: ProductVariant) {
                    return h(
                        NText,
                        {},
                        {
                            default: () => row.weight_value + " " + props.weightUnit,
                        }
                    );
                },
            },
            {
                title: "Purchased",
                align: "center",
                key: "purchased",
                render(row: ProductVariant) {
                    return h(
                        NText,
                        {
                            type: "primary"
                        },
                        {
                            default: () => helpers.format(row.purchased) + "$",
                        }
                    );
                },
            },
            {
                title: "Price",
                align: "center",
                key: "price",
                render(row: ProductVariant) {
                    return h(
                        NText,
                        {
                            type: "success"
                        },
                        {
                            default: () => helpers.format(row.price) + "$",
                        }
                    );
                },

            },
            {
                title: "Expire",
                align: "center",
                key: "expire",
                render(row: ProductVariant) {
                    return h(
                        NText,
                        {
                            type: "warning"

                        },
                        {
                            default: () => row.expire,
                        }
                    );
                },
            },
            {
                title: "Barcode",
                align: "center",
                key: "barcode",
                render(row: ProductVariant) {
                    return h(
                        NText,
                        {

                        },
                        {
                            default: () => row.barcode,
                        }
                    );
                },
            },
        ]
        return {
            columns
        }
    },
})


</script>
