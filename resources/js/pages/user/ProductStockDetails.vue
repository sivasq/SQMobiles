<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <h4>Product Stock Details</h4>
        </div>

        <!-- Branch Buttons -->
        <div class="row justify-content-center mt-3">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label @click.prevent="fetchProducts(0,activeBrand)" class="btn btn-outline-primary btn-toggle active">
                    <input autocomplete="off" checked type="radio" v-model="activeTab">
                    ALL
                </label>

                <label :key="branch.id" @click.prevent="fetchProducts(branch.id, activeBrand)"
                       class="btn btn-outline-primary btn-toggle"
                       v-for="(branch, index) in branches">
                    <input autocomplete="off" type="radio" v-model="activeTab">
                    {{branch.branch_name}}
                </label>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <div class="form-group">
                <label for="brand_id">Brand Name</label>

                <select @change="onBrandChange($event)" class="form-control" id="brand_id" v-model="activeBrand">
                    <option selected value="0">All</option>
                    <option :key="brand.id" :value="brand.id" class="list-group-item"
                            v-for="(brand, index) in brands">{{brand.brand_name}}
                    </option>
                </select>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <button @click.prevent="exportData" class="btn btn-sm btn-outline-secondary">Export As Excel</button>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Available Stock</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr :key="stocksDetail.id" v-for="(stocksDetail, index) in stocksDetails"
                        :style="[stocksDetail.available_stock == 0 ? {'background': '#ec1e1e4f'} : {'background': ''}]">
                        <td>{{stocksDetail.brand_name}} {{stocksDetail.product_name}}</td>
                        <td>{{stocksDetail.available_stock}}</td>
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
                brands: [],
                activeTab: 0,
                activeBrand: 0
            }
        },
        created() {
            this.fetchBranches();
            this.fetchBrands();
            this.fetchProducts(this.activeTab, this.activeBrand);
            // this.fetchDownload();
        },
        methods: {
            onBrandChange(event) {
                console.log(event.target.value)
                this.fetchProducts(this.activeTab, event.target.value);
            },
            exportData() {
                axios({
                    url: window.base_url + '/api/v1/auth/getProductStockExcel/' + this.activeTab + '/' + this.activeBrand,
                    method: 'GET',
                    responseType: 'blob', // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'file.xlsx'); //or any other extension
                    document.body.appendChild(link);
                    link.click();
                });
            },
            fetchBranches() {
                axios.get(window.base_url + '/api/v1/auth/fetchBranches')
                    .then(response => {
                        this.branches = response.data.data;
                        console.log(this.branches);
                    })
                    .catch((err) => console.error(err));
            },
            fetchBrands() {
                axios.get(window.base_url + '/api/v1/auth/fetchBrands')
                    .then(response => {
                        this.brands = response.data.data;
                        console.log(this.brands);
                    })
                    .catch((err) => console.error(err));
            },
            fetchProducts(branchId, brandId) {
                this.activeTab = branchId;
                this.activeBrand = brandId
                axios.get(window.base_url + '/api/v1/auth/getProductStock/' + branchId + '/' + brandId)
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

