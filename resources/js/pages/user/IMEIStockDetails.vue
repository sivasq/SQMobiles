<template>
    <div class="container-fluid">

        <div class="row justify-content-center">
            <h4>IMEI Based Stock Details</h4>
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

        <!-- Transfer To Branch -->
        <div class="row justify-content-center mt-3" v-if="showTransfer">
            <span>Transfer To</span>
            <div class="col-12 text-center">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label :key="branch.id" @click.prevent="transferTo=branch.id"
                           class="btn btn-sm btn-outline-info" v-for="(branch, index) in branches"
                           v-if="branch.id !== activeTab">
                        <input autocomplete="off" type="radio">
                        {{branch.branch_name}}
                    </label>
                </div>
                <button class="btn btn-sm btn-success" type="button" v-if="transferTo!=''"
                        @click.prevent="transferStock()">Transfer
                    Now</button>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <button @click.prevent="exportData" class="btn btn-sm btn-outline-secondary">Export As Excel</button>
        </div>

        <!-- Stock Details -->
        <div class="row justify-content-center mt-3">
            <div class="col-12">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th v-if="activeTab != 0">
                        </th>
                        <th>IMEI</th>
                        <th>Bill</th>
                        <th>Supplier</th>
                        <th>Product</th>
                        <th>Located At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr :key="stocksDetail.id" v-for="(stocksDetail, index) in stocksDetails">
                        <td v-if="activeTab != 0">
                            <div class="form-check">
                                <input :value="stocksDetail.id" @change="selectChange"
                                       type="checkbox" v-model="selected">
                            </div>
                        </td>
                        <td>{{stocksDetail.imei_number}}</td>
                        <td>{{stocksDetail.inventory_product_detail.inventory_detail.invoice_number}}</td>
                        <td>{{stocksDetail.inventory_product_detail.inventory_detail.supplier_details .supplier_name}}
                        </td>
                        <td>{{stocksDetail.inventory_product_detail.product_details.brand_details .brand_name}} -
                            {{stocksDetail.inventory_product_detail.product_details.product_name}}
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
                branches: [],
                selected: [],
                selectAll: false,
                activeTab: 0,
                showTransfer: false,
                transferTo: ''
            }
        },
        created() {
            this.fetchBranches();
            this.fetchProducts(0);
        },
        methods: {
            exportData() {
                axios({
                    url: window.base_url + '/api/v1/auth/getImeiBasedStockDetailsExcel/' + this.activeTab,
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
            selectALLIMEI() {
                this.selected = [];
                if (!this.selectAll) {
                    for (let i in this.stocksDetails) {
                        this.selected.push(this.stocksDetails[i].id);
                    }
                }
            },
            fetchBranches() {
                axios.get(window.base_url + '/api/v1/auth/fetchBranches')
                    .then(response => {
                        this.branches = response.data.data;
                        console.log(this.branches);
                    })
                    .catch((err) => console.error(err));
            },
            fetchProducts(branchId) {
                this.activeTab = branchId;
                axios.get(window.base_url + '/api/v1/auth/getImeiBasedStockDetails/' + branchId)
                    .then(response => {
                        this.selected = [];
                        this.showTransfer = false;
                        this.stocksDetails = response.data.data;
                        console.log(this.stocksDetails);
                    })
                    .catch((err) => console.error(err));
            },
            selectChange() {
                if (this.selected.length > 0) {
                    this.showTransfer = true;
                } else {
                    this.transferTo = '';
                    this.showTransfer = false;
                }
            },
            transferStock() {
                console.log(this.transferTo);
                console.log(this.activeTab);
                console.log(this.selected);
                var app = this
                axios.post(window.base_url + '/api/v1/auth/transferStock', {
                    transfer_to : app.transferTo,
                    transfer_from : app.activeTab,
                    transfer_items : app.selected
                })
                    .then(response => {
                        if(response.data.success) {
                            this.fetchProducts(app.activeTab);
                        }
                    })
                    .catch((res) => {

                    });
            }
        },
        watch: {},
        components: {
            //
        }
    }
</script>

