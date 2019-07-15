<template>
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-12 mt-5">
<!--                <ul class="list-group">-->
<!--                    <li class="list-group-item text-center text-primary"> Stock List</li>-->
<!--                    <li class="list-group-item text-center text-danger" v-if='stocksDetails.length === 0'>There are no-->
<!--                        Stock-->
<!--                        yet!-->
<!--                    </li>-->
<!--                    <li :key="stocksDetail.id" class="list-group-item" v-for="(stocksDetail, index) in stocksDetails">-->
<!--                        {{index + 1}}) {{stocksDetail.imei_number}}-->
<!--                    </li>-->
<!--                </ul>-->

                <table class="table">
                    <thead>
                    <th>IMEI</th>
                    <th>Invoice</th>
                    <th>Supplier</th>
                    <th>Product</th>
                    <th>Location</th>
                    </thead>
                    <tbody>
                    <tr :key="stocksDetail.id" v-for="(stocksDetail, index) in stocksDetails">
                        <td>{{stocksDetail.imei_number}}</td>
                        <td>{{stocksDetail.inventory_product_detail.inventory_detail.invoice_number}}</td>
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
                stocksDetails: [],
            }
        },
        created() {
            this.fetchProducts();
        },
        methods: {
            fetchProducts() {
                axios.get(window.base_url + '/api/v1/auth/getInventoryProductDetails')
                    .then(response => {
                        this.stocksDetails = response.data.data;
                        console.log(this.stocksDetails);
                    })
                    .catch((err) => console.error(err));
            },
        },
        components: {
            //
        }
    }
</script>

