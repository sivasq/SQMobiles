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
                <button @click.prevent="transferStock()" class="btn btn-sm btn-success" type="button"
                        v-if="transferTo!=''">Transfer
                    Now
                </button>
            </div>
        </div>

        <div class="row justify-content-center mt-3">
            <button @click.prevent="exportData" class="btn btn-sm btn-outline-secondary">Export As Excel</button>
        </div>

        <input class="form-control w-25" placeholder="Filter By IMEI / Bill" type="search" v-model="searchStock">
        <!-- Stock Details -->
        <div class="row justify-content-center mt-3">
            <div class="col-12">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th v-if="activeTab != 0"></th>
                        <th>IMEI</th>
                        <th>Bill</th>
                        <th>Supplier</th>
                        <th>Product</th>
                        <th>Located At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td align="center" colspan="6" v-if="loading"> Loading...</td>
                    </tr>
                    <tr :key="stocksDetail.id" v-for="(stocksDetail, index) in
                    filterStocks" v-if="stocksDetails.length > 0">
                        <td v-if="activeTab != 0">
                            <div class="form-check">
                                <input :value="stocksDetail.id" @change="selectChange"
                                       style="cursor: pointer" type="checkbox" v-model="selected">
                            </div>
                        </td>
                        <td @click.prevent="fetchImeiTxnLogs(stocksDetail.id)"
                            style="text-decoration-line: underline; text-decoration-style: dashed; text-decoration-color: red; cursor: pointer;">
                            {{stocksDetail.imei_number}}
                        </td>
                        <td>{{stocksDetail.invoice_number}}</td>
                        <td>{{stocksDetail.supplier_name}}
                        </td>
                        <td>{{stocksDetail.brand_name}} -
                            {{stocksDetail.product_name}}
                        </td>
                        <td>{{stocksDetail.branch_location}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <modal :scrollable="true" @before-open="beforeOpen" height="auto" name="log-details" width="90%">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Txn Date</th>
                    <th>Txn Detail</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="imeiTxnLogs.length === 0">
                    <td colspan="2" style="text-align:center">No Txn Logs Found</td>
                </tr>

                <tr :key="imeiTxnLog.id"
                    v-for="(imeiTxnLog, index) in imeiTxnLogs" v-if="imeiTxnLogs.length > 0">
                    <td>{{imeiTxnLog.created_at}}</td>
                    <td>{{imeiTxnLog.txn_details}}</td>
                </tr>
                </tbody>
            </table>
        </modal>

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
                transferTo: '',
                loading: false,
                request_source: '',
                imeiTxnLogs: [],
                searchStock: '',
            }
        },
        created() {
            this.fetchBranches();
            this.fetchProducts(0);
        },
        computed: {
            filterStocks() {
                return this.stocksDetails.filter(stock => {
                    return !this.searchStock.trim() || stock.imei_number.indexOf(this.searchStock.trim()) > -1 ||
                        stock.invoice_number.toLowerCase().indexOf(this.searchStock.toLowerCase().trim()) > -1
                    // return client.imei_number.indexOf(this.searchClient) > -1;
                });
            }
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
                var CancelToken = axios.CancelToken;
                // var call1 = CancelToken.source();
                // call1.cancel('cancelled');
                this.activeTab = branchId;
                this.stocksDetails = [];
                this.searchStock = '';
                this.loading = true;

                var source = CancelToken.source();
                if (this.request_source != '')
                    this.request_source.cancel('Operation canceled by the user.');
                this.request_source = source;

                axios.get(window.base_url + '/api/v1/auth/getImeiBasedStockDetails/' + branchId,
                    {cancelToken: this.request_source.token})
                    .then(response => {
                        this.selected = [];
                        this.loading = false;
                        this.showTransfer = false;
                        this.stocksDetails = response.data;
                        // console.log(this.stocksDetails);
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
                var app = this;
                axios.post(window.base_url + '/api/v1/auth/transferStock', {
                    transfer_to: app.transferTo,
                    transfer_from: app.activeTab,
                    transfer_items: app.selected
                })
                    .then(response => {
                        if (response.data.success) {
                            this.fetchProducts(app.activeTab);
                        }
                    })
                    .catch((res) => {

                    });
            },
            beforeOpen(event) {
                // console.log(event.params.stockData);
                this.imeiTxnLogs = event.params.logData;
            },
            fetchImeiTxnLogs(imeiId) {
                var CancelToken = axios.CancelToken;
                // var call1 = CancelToken.source();
                // call1.cancel('cancelled');

                var source = CancelToken.source();
                if (this.request_source != '')
                    this.request_source.cancel('Operation canceled by the user.');
                this.request_source = source;

                axios.get(window.base_url + '/api/v1/auth/getImeiTxnLog/' + imeiId, {cancelToken: this.request_source.token})
                    .then(response => {
                        this.$modal.show('log-details', {logData: response.data})
                    })
                    .catch((err) => console.error(err));
            },
        },
        watch: {},
        components: {
            //
        }
    }
</script>

