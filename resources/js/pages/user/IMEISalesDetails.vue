<template>
    <div class="container-fluid">

        <div class="row justify-content-center">
            <h4>IMEI Based Sales Details</h4>
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

        <div class="row justify-content-left mt-5 ml-3">
            <v-md-date-range-picker :auto-apply=false @change="handleChange" show-year-select></v-md-date-range-picker>
        </div>


        <div class="row justify-content-center mt-3">
            <div class="col-12">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>IMEI</th>
                        <th>Purchase Bill</th>
                        <th>Sales Invoice</th>
                        <th>Sales Date</th>
                        <th>Sale By</th>
                        <th>Supplier</th>
                        <th>Product</th>
                        <th>Located At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td align="center" colspan="6" v-if="loading"> Loading...</td>
                    </tr>
                    <tr :key="stocksDetail.id" v-for="(stocksDetail, index) in stockSalesDetails" @click.prevent="fetchImeiTxnLogs(stocksDetail.id)">
                        <td>{{stocksDetail.imei_number}}</td>
                        <td>{{stocksDetail.invoice_number}}</td>
                        <td>{{stocksDetail.sales_invoice}}
                        </td>
                        <td>{{stocksDetail.sales_at}}
                        </td>
                        <td>{{stocksDetail.name}}</td>
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

        <modal :scrollable="true" @before-open="beforeOpen" height="auto" name="log-details">
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
                stockSalesDetails: [],
                branches: [],
                activeTab: 0,
                loading: false,
                request_source: '',
                start_date: '',
                end_date: '',
                imeiTxnLogs: [],
            }
        },
        // components: { DateRangePicker },
        created() {
            this.start_date = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
            this.end_date = new Date().toJSON().slice(0, 10).replace(/-/g, '-');
            this.fetchBranches();
            this.fetchProducts(0);
        },
        methods: {
            handleChange(values) {
                this.start_date = values[0].format('YYYY-MM-DD');
                this.end_date = values[1].format('YYYY-MM-DD');
                this.fetchProducts(this.activeTab);
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
                var CancelToken1 = axios.CancelToken;
                var source = CancelToken1.source();
                if (this.request_source != '')
                    this.request_source.cancel('Operation canceled by the user.');
                this.request_source = source;
                axios.post(window.base_url + '/api/v1/auth/getImeiBasedSalesDetails/' + branchId,
                    {'from': this.start_date, 'to': this.end_date}, {
                        cancelToken1:
                        this.request_source.token
                    })
                    .then(response => {
                        this.loading = false;
                        this.stockSalesDetails = response.data;
                        console.log(this.stockSalesDetails);
                    })
                    .catch((err) => console.error(err));
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
        components: {
            //
        }
    }
</script>

