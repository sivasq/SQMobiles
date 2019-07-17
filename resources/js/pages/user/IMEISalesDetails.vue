<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <h4>IMEI Number Based Sales Details</h4>
            <div class="col-12 mt-5">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>IMEI</th>
                        <th>Purchase Invoice</th>
                        <th>Sales Invoice</th>
                        <th>Sales Date</th>
                        <th>Supplier</th>
                        <th>Product</th>
                        <th>Located At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr :key="stocksDetail.id" v-for="(stocksDetail, index) in stockSalesDetails">
                        <td>{{stocksDetail.imei_number}}</td>
                        <td>{{stocksDetail.inventory_product_detail.inventory_detail.invoice_number}}</td>
                        <td>{{stocksDetail.sales_invoice}}
                        </td>
                        <td>{{stocksDetail.sales_at}}
                        </td>
                        <td>{{stocksDetail.inventory_product_detail.inventory_detail.supplier_details
                            .supplier_name}}
                        </td>
                        <td>{{stocksDetail.inventory_product_detail.product_details.brand_details
                            .brand_name}} - {{stocksDetail.inventory_product_detail.product_details.product_name}}
                        </td>
                        <td>{{stocksDetail.branch_detail.branch_name}} -
                            {{stocksDetail.branch_detail.branch_location}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                stockSalesDetails: [],
            }
        },
        created() {
            this.fetchProducts();
        },
        methods: {
            fetchProducts() {
                axios.get(window.base_url + '/api/v1/auth/getImeiBasedSalesDetails')
                    .then(response => {
                        this.stockSalesDetails = response.data.data;
                        console.log(this.stockSalesDetails);
                    })
                    .catch((err) => console.error(err));
            },
        },
        components: {
            //
        }
    }
</script>

