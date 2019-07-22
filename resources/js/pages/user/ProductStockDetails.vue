<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <h4>Product Stock Details</h4>
        </div>

        <!-- Branch Buttons -->
        <div class="row justify-content-center mt-3">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label @click.prevent="fetchProducts(0)" class="btn btn-outline-primary btn-toggle active">
                    <input autocomplete="off" checked type="radio" v-model="activeTab">
                    ALL
                </label>

                <label :key="branch.id" @click.prevent="fetchProducts(branch.id)"
                       class="btn btn-outline-primary btn-toggle"
                       v-for="(branch, index) in branches">
                    <input autocomplete="off" type="radio" v-model="activeTab">
                    {{branch.branch_name}}
                </label>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="col-12 mt-5">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Available Stock</th>
<!--                        <th>Supplier</th>-->
<!--                        <th>Product</th>-->
<!--                        <th>Located At</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <tr :key="stocksDetail.id" v-for="(stocksDetail, index) in stocksDetails">
                        <td>{{stocksDetail.brand_name}} {{stocksDetail.product_name}}</td>
                        <td>{{stocksDetail.available_stock}}</td>
<!--                        <td>{{stocksDetail.inventory_product_detail.inventory_detail.supplier_details-->
<!--                            .supplier_name}}-->
<!--                        </td>-->
<!--                        <td>{{stocksDetail.inventory_product_detail.product_details.brand_details-->
<!--                            .brand_name}} - {{stocksDetail.inventory_product_detail.product_details.product_name}}-->
<!--                        </td>-->
<!--                        <td>{{stocksDetail.branch_detail.branch_name}} - -->
<!--                            {{stocksDetail.branch_detail.branch_location}}-->
<!--                        </td>-->
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
                branches: [],
                activeTab: 0,
            }
        },
        created() {
            this.fetchBranches();
            this.fetchProducts(this.activeTab);
        },
        methods: {
            fetchBranches() {
                axios.get(window.base_url + '/api/v1/auth/fetchBranches')
                    .then(response => {
                        this.branches = response.data.data;
                        console.log(this.branches);
                    })
                    .catch((err) => console.error(err));
            },
            fetchProducts(branchId) {
                axios.get(window.base_url + '/api/v1/auth/getProductStock/' + branchId)
                    .then(response => {
                        this.stocksDetails = response.data;
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

