<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                Add New Inventory
            </div>
            <div class="card-body">
                <form @submit.prevent="addInventory" autocomplete="off" method="post" v-if="!success">
                    <div class="row mb-2 pb-2 pt-2" style="background-color: #efe9e9a6; border-radius: 4px;">
                        <!-- Bill Number -->
                        <div class="col-sm-4 form-group"
                             v-bind:class="{ 'has-error': has_error && errors.invoice_number }">
                            <label for="invoice_number">Bill Number</label>
                            <input class="form-control" id="invoice_number" placeholder="Bill Number" type="text"
                                   v-model="inventory.invoice_number" v-on:input="onBillNoChange($event)">
                            <span class="help-block"
                                  v-if="has_error && errors.invoice_number">Bill NumberRequired</span>
                        </div>

                        <!-- Supplier Name-->
                        <div class="col-sm-8 form-group"
                             v-bind:class="{ 'has-error': has_error && errors.supplier_id }">
                            <label for="supplier_id">Supplier Name</label>
                            <select class="form-control custom-select" id="supplier_id"
                                    v-model="inventory.supplier_id">
                                <option disabled selected value="">Select Supplier</option>
                                <option :key="supplier.id" :value="supplier.id"
                                        class="list-group-item"
                                        v-for="(supplier, index) in suppliers">{{supplier.supplier_name}}
                                </option>
                            </select>
                            <span class="help-block"
                                  v-if="has_error && errors.supplier_id">supplier
                                        Name Required</span>
                        </div>
                    </div>

                    <div class="row mb-2 pb-2 pt-2" style="background-color: #efe9e9a6; border-radius: 4px;">
                        <div class="col">
                            <div class="row">
                                <!-- Product Qty -->
                                <div class="col-sm-2 form-group"
                                     v-bind:class="{ 'has-error': has_error && errors.product_qty }">
                                    <label for="product_qty">Product Qty</label>
                                    <input @keypress="isNumber($event)" class="form-control" id="product_qty" min="1"
                                           placeholder="Product Qty"
                                           type="number" v-model="inventory.product_qty"
                                           v-on:input="onProductQtyChange($event)">
                                    <span class="help-block"
                                          v-if="has_error && errors.product_qty">{{ errors.product_qty[0] }}</span>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="table ">
                                <table class="table table-responsive-lg">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 150px;">Brand</th>
                                        <th style="width: 250px;">Product</th>
                                        <th style="width: 150px;">Item Color</th>
                                        <th style="min-width: 250px;">IMEI Number</th>
                                        <th style="width: 150px;">Unit Rate(&#8377;)</th>
                                        <th style="width: 100px;">GST %</th>
                                        <th style="width: 150px; display: none;">GST</th>
                                        <th style="width: 150px;">Total Price(&#8377;)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(imei, index) in inventory.products_details_list"
                                        v-if="inventory.products_details_list.length > 0">

                                        <!-- Serial No. -->
                                        <td>{{index + 1}}</td>

                                        <!-- Brand Name -->
                                        <td style="width: 150px;">
                                            <select @change="onBrandChange($event,index)"
                                                    class="form-control custom-select"
                                                    id="brand_id"
                                                    v-model="imei.brand_id">
                                                <option disabled selected value="">Select Brand</option>
                                                <option :key="brand.id" :value="brand.id"
                                                        class="list-group-item"
                                                        v-for="(brand, index) in brands">{{brand.brand_name}}
                                                </option>
                                            </select>
                                            <span class="help-block"
                                                  v-if="has_error && errors['products_details_list.'+index+'.brand_id']">{{errors['products_details_list.'+index+'.brand_id'][0]}}
                                            </span>
                                        </td>

                                        <!-- Product Name -->
                                        <td style="width: 250px;">
                                            <select class="form-control custom-select" id="product_id"
                                                    v-model="imei.product_id">
                                                <option disabled selected value="">Select Product</option>
                                                <option :key="product.id" :value="product.id"
                                                        class="list-group-item" v-for="(product, index) in
                                                        imei.products_in_brand">
                                                    {{product.product_name}}
                                                </option>
                                            </select>
                                            <span class="help-block"
                                                  v-if="has_error && errors['products_details_list.'+index+'.product_id']">{{errors['products_details_list.'+index+'.product_id'][0]}}
                                            </span>
                                        </td>

                                        <!-- Product Color -->
                                        <td style="width: 150px;">
                                            <input class="form-control" placeholder="Product Color" type="text"
                                                   v-bind:id="index"
                                                   v-model="imei.product_color">
                                            <span class="help-block"
                                                  v-if="has_error && errors['products_details_list.'+index+'.product_color']">{{errors['products_details_list.'+index+'.product_color'][0]}}
                                            </span>
                                        </td>

                                        <!-- IMEI Number -->
                                        <td style="min-width: 250px;">
                                            <input class="form-control" placeholder="Type or Scan IMEI" type="text"
                                                   v-bind:id="index"
                                                   v-model="imei.imei_number" v-on:blur="checkImei"
                                                   v-on:keydown.enter.prevent='addInventory'>
                                            <span class="help-block"
                                                  v-if="has_error && errors['products_details_list.'+index+'.imei_number']">{{errors['products_details_list.'+index+'.imei_number'][0]}}
                                            </span>
                                        </td>

                                        <!-- Unit Price -->
                                        <td style="width: 150px;">
                                            <input class="form-control" min="0" placeholder="Unit Price"
                                                   step="0.01" type="number" v-bind:id="index"
                                                   v-model="imei.unit_price" v-on:input="onPriceChange($event, index)">
                                            <span class="help-block"
                                                  v-if="has_error && errors['products_details_list.'+index+'.unit_price']">{{errors['products_details_list.'+index+'.unit_price'][0]}}
                                            </span>
                                        </td>

                                        <!-- GST Percentage -->
                                        <td style="width: 150px;">
                                            <select @change="onPriceChange($event,index)"
                                                    class="form-control custom-select"
                                                    v-model="imei.gst_percentage">
                                                <option disabled selected value="">GST</option>
                                                <option :key="gst_percentage" :value="gst_percentage"
                                                        class="list-group-item" v-for="(gst_percentage, index) in
                                                        gst_percentages">
                                                    {{gst_percentage}}
                                                </option>
                                            </select>
                                            <span class="help-block"
                                                  v-if="has_error && errors['products_details_list.'+index+'.gst_percentage']">{{errors['products_details_list.'+index+'.gst_percentage'][0]}}
                                            </span>
                                        </td>

                                        <!-- GST -->
                                        <td style="width: 150px; display: none;">
                                            <input class="form-control" placeholder="GST" readonly step="0.01"
                                                   type="number"
                                                   v-bind:id="index"
                                                   v-model="imei.gst" v-on:input="onPriceChange($event, index)">
                                            <span class="help-block"
                                                  v-if="has_error && errors['products_details_list.'+index+'.gst']">{{errors['products_details_list.'+index+'.gst'][0]}}
                                            </span>
                                        </td>

                                        <!-- Total Price -->
                                        <td style="width: 150px;">
                                            <input class="form-control" placeholder="Total Price" readonly
                                                   step="0.01" type="number"
                                                   v-bind:id="index" v-model="imei.total_price" v-on:input="onPriceChange($event,
                                                   index)">
                                            <span class="help-block"
                                                  v-if="has_error && errors['products_details_list.'+index+'.total_price']">{{errors['products_details_list.'+index+'.total_price'][0]}}
                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>

                <!-- Footer -->
                <div class="row">
                    <div class="col-lg-4 col-sm-5">
                    </div>
                    <div class="col-lg-4 col-sm-5 ml-auto">
                        <table class="table table-clear">
                            <tbody>
                            <tr>
                                <td class="left">
                                    <strong>Subtotal</strong>
                                </td>
                                <td class="right">&#8377; {{inventory.total_unit_price}}</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong>GST</strong>
                                </td>
                                <td class="right">&#8377; {{inventory.total_gst}}</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong>Total</strong>
                                </td>
                                <td class="right">
                                    <strong>&#8377; {{inventory.total_price}}</strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                inventory: {
                    invoice_number: '',
                    supplier_id: '',
                    product_qty: 1,
                    total_unit_price: 0,
                    total_gst: 0,
                    total_price: 0,
                    products_details_list: []
                },
                has_error: false,
                error: '',
                errors: {},
                success: false,
                suppliers: [],
                brands: [],
                products: [],
                gst_percentages: [12, 18, 28]
            }
        },
        created() {
            this.fetchSuppliers();
            this.fetchBrands();
            // this.fetchProductsByBrand(6);
            this.inventory.products_details_list.push({
                brand_id: '',
                product_id: '',
                imei_number: '',
                product_color: '',
                unit_price: '',
                gst: '',
                gst_percentage: '',
                total_price: '',
                products_in_brand: [],
            });
        },
        methods: {
            isNumber: function (evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                // console.log(charCode);
                if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            addIMEI: function (nos) {
                let filteredNonEmptyIMEI = this.inventory.products_details_list.filter(function (element, index, arr) {
                    if (element.brand_id != '' || element.product_id != '' || element.imei_number != '' || element.product_color != '' || element.unit_price != '' || element.gst != '' || element.total_price != '') {
                        return element;
                    }
                });
                this.inventory.products_details_list = filteredNonEmptyIMEI;

                let totalNonEmptyIMEI = nos - (this.inventory.products_details_list).length;
                for (let i = 0; i < totalNonEmptyIMEI; i++) {
                    this.inventory.products_details_list.push({
                        brand_id: '',
                        product_id: '',
                        imei_number: '',
                        product_color: '',
                        unit_price: '',
                        gst: '',
                        gst_percentage: '',
                        total_price: '',
                        products_in_brand: []
                    });
                }
            },
            deleteIMEI: function (index) {
                this.inventory.products_details_list.splice(index, 1);
                if (index === 0)
                    this.addIMEI()
            },
            fetchSuppliers() {
                axios.get(window.base_url + '/api/v1/auth/fetchSuppliers')
                    .then(response => {
                        this.suppliers = response.data.data;
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
            onBrandChange(event, index) {
                // console.log(event.target.value)
                this.fetchProductsByBrand(event.target.value, index);
            },
            onProductQtyChange(event) {
                if (event.target.value > 100) {
                    return false;
                }

                this.addIMEI(event.target.value);
                // this.fetchProductsByBrand(event.target.value);
            },
            fetchProductsByBrand(brandId, index) {
                axios.get(window.base_url + '/api/v1/auth/fetchProductsByBrand/' + brandId)
                    .then(response => {
                        this.inventory.products_details_list[index].products_in_brand = response.data.data;
                    })
                    .catch((err) => console.error(err));
            },
            addInventory(event) {
                var valueArr = this.inventory.products_details_list.filter(el => el.imei_number != "").map(function
                    (item) {
                    if (item.imei_number != '') {
                        return item.imei_number;
                    }
                });

                var isDuplicate = valueArr.some(function (item, idx) {
                    return valueArr.indexOf(item) != idx
                });
                if (isDuplicate) {
                    Vue.$toast.error("Duplicate IMEI Number Found..");
                    return false;
                }

                if (event.which == '10' || event.which == '13') {
                    $('#imei #' + (Number(event.target.id) + 1)).focus();
                    return false;
                }
                if (event.keyCode == '10' || event.keyCode == '13') {
                    $('#imei #' + (Number(event.target.id) + 1)).focus();
                    return false;
                }

                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/addInventoryExtra', this.inventory)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success("Product Stock Added Successfully");
                            this.inventory.invoice_number = '';
                            this.inventory.supplier_id = '';
                            this.inventory.brand_id = '';
                            this.inventory.product_id = '';
                            this.inventory.product_qty = '';
                            this.inventory.products_details_list = [];
                            // this.fetchBranches();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            },
            checkImei(event) {
                var valueArr = this.inventory.products_details_list.filter(el => el.imei_number != "").map(function
                    (item) {
                    if (item.imei_number != '') {
                        return item.imei_number;
                    }
                });

                var isDuplicate = valueArr.some(function (item, idx) {
                    return valueArr.indexOf(item) != idx
                });
                if (isDuplicate) {
                    Vue.$toast.error("Duplicate IMEI Number Found");
                    return false;
                }

                if (event.which == '10' || event.which == '13') {
                    $('#imei #' + (Number(event.target.id) + 1)).focus();
                    return false;
                }
                if (event.keyCode == '10' || event.keyCode == '13') {
                    $('#imei #' + (Number(event.target.id) + 1)).focus();
                    return false;
                }
            },
            onBillNoChange(event) {
                console.log(event.target.value);
            },
            calcTotalAmount() {
                this.inventory.total_unit_price = this.inventory.products_details_list.map(t => t.unit_price).reduce((previous, current) => {
                    return parseFloat(previous) + parseFloat(current == '' ? 0 : current);
                }, 0);

                this.inventory.total_gst = this.inventory.products_details_list.map(t => t.gst).reduce((previous, current) => {
                    return parseFloat(previous) + parseFloat(current == '' ? 0 : current);
                }, 0);

                this.inventory.total_price =
                    this.inventory.products_details_list.map(t => t).reduce((previous, current) => {
                        let tot = parseFloat(current.gst == '' ? 0 : current.gst) + parseFloat(current.unit_price ==
                        '' ? 0 : current.unit_price);
                        return parseFloat(previous) + parseFloat(tot);
                    }, 0);
            },
            onPriceChange(event, index) {
                let unitPrice = this.inventory.products_details_list[index].unit_price;
                let gstPercentage = this.inventory.products_details_list[index].gst_percentage;
                let gstAmount = (gstPercentage / 100) * unitPrice;

                this.inventory.products_details_list[index].gst = gstAmount;
                this.inventory.products_details_list[index].total_price = parseFloat(unitPrice == '' ? 0 : unitPrice) + parseFloat(gstAmount == '' ? 0 : gstAmount);

                this.calcTotalAmount();
            }
        },
        components: {
            //
        }
    }
</script>

