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
                                <!-- Brand -->
                                <div class="col-sm-3 form-group"
                                     v-bind:class="{ 'has-error': has_error && errors.brand_id }">
                                    <label for="brand_id">Brand Name</label>
                                    <select @change="onBrandChange($event)" class="form-control custom-select"
                                            id="brand_id"
                                            v-model="inventory.brand_id">
                                        <option disabled selected value="">Select Brand</option>
                                        <option :key="brand.id" :value="brand.id"
                                                class="list-group-item"
                                                v-for="(brand, index) in brands">{{brand.brand_name}}
                                        </option>
                                    </select>
                                    <span class="help-block"
                                          v-if="has_error && errors.brand_id">Brand Required</span>
                                </div>

                                <!-- Product Name -->
                                <div class="col-sm-7 form-group"
                                     v-bind:class="{ 'has-error': has_error && errors.product_id }">
                                    <label for="product_id">Product Name</label>
                                    <select class="form-control custom-select" id="product_id"
                                            v-model="inventory.product_id">
                                        <option disabled selected value="">Select Product</option>
                                        <option :key="product.id" :value="product.id"
                                                class="list-group-item" v-for="(product, index) in products">
                                            {{product.product_name}}
                                        </option>
                                    </select>
                                    <span class="help-block"
                                          v-if="has_error && errors.product_id">Product
                                        Required</span>
                                </div>

                                <!-- Product Qty -->
                                <div class="col-sm-2 form-group" v-bind:class="{ 'has-error': has_error && errors.product_qty
                         }">
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
                            <div class="table-responsive-sm">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>IMEI Number</th>
                                        <th>Item Color</th>
                                        <th class="right">Unit Rate</th>
                                        <th class="center">GST</th>
                                        <th class="right">Total Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(imei, index) in inventory.product_serial_numbers"
                                        v-if="inventory.product_serial_numbers.length > 0">

                                        <!-- Serial No. -->
                                        <td class="center">{{index + 1}}</td>

                                        <!-- IMEI Number -->
                                        <td class="left">
                                            <input class="form-control" placeholder="Type or Scan IMEI" type="number"
                                                   v-bind:id="index"
                                                   v-model="imei.imei_number" v-on:blur="chckImei"
                                                   v-on:keydown.enter.prevent='addInventory'>
                                            <span class="help-block"
                                                  v-if="has_error && errors['product_serial_numbers.'+index+'.imei_number']">{{errors['product_serial_numbers.'+index+'.imei_number'][0]}}
                                    </span>
                                        </td>

                                        <!-- Product Color -->
                                        <td class="left strong">
                                            <input class="form-control" placeholder="Product Color" type="text"
                                                   v-bind:id="index"
                                                   v-model="imei.product_color">
                                            <span class="help-block"
                                                  v-if="has_error && errors['product_serial_numbers.'+index+'.product_color']">{{errors['product_serial_numbers.'+index+'.product_color'][0]}}
                                    </span>
                                        </td>

                                        <!-- Unit Price -->
                                        <td class="left">
                                            <input class="form-control" placeholder="Unit Price" type="number"
                                                   v-bind:id="index"
                                                   v-model="imei.unit_price">
                                            <span class="help-block"
                                                  v-if="has_error && errors['product_serial_numbers.'+index+'.unit_price']">{{errors['product_serial_numbers.'+index+'.unit_price'][0]}}
                                    </span>
                                        </td>

                                        <!-- GST -->
                                        <td class="right">
                                            <input class="form-control" placeholder="GST" type="number"
                                                   v-bind:id="index"
                                                   v-model="imei.gst">
                                            <span class="help-block"
                                                  v-if="has_error && errors['product_serial_numbers.'+index+'.gst']">{{errors['product_serial_numbers.'+index+'.gst'][0]}}
                                    </span>
                                        </td>

                                        <!-- Total Price -->
                                        <td class="right">
                                            <input class="form-control" placeholder="Total Price" type="number"
                                                   v-bind:id="index"
                                                   v-model="imei.total_price">
                                            <span class="help-block"
                                                  v-if="has_error && errors['product_serial_numbers.'+index+'.total_price']">{{errors['product_serial_numbers.'+index+'.total_price'][0]}}
                                    </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
                                <td class="right">$8.497,00</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong>Discount (20%)</strong>
                                </td>
                                <td class="right">$1,699,40</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong>VAT (10%)</strong>
                                </td>
                                <td class="right">$679,76</td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong>Total</strong>
                                </td>
                                <td class="right">
                                    <strong>$7.477,36</strong>
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
                    // branch_id: 1,
                    brand_id: '',
                    product_id: '',
                    product_qty: '',
                    purchase_price: 0,
                    product_serial_numbers: []
                },
                has_error: false,
                error: '',
                errors: {},
                success: false,
                suppliers: [],
                brands: [],
                products: [],
            }
        },
        created() {
            this.fetchSuppliers();
            this.fetchBrands();
            // this.fetchProductsByBrand(6);
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
                let filteredNonEmptyIMEI = this.inventory.product_serial_numbers.filter(function (element, index, arr) {
                    return element.imei_number != '';
                });
                this.inventory.product_serial_numbers = filteredNonEmptyIMEI;

                let totalNonEmptyIMEI = nos - (this.inventory.product_serial_numbers).length;
                for (let i = 0; i < totalNonEmptyIMEI; i++) {
                    this.inventory.product_serial_numbers.push({
                        imei_number: '', product_color: '', unit_price: '', gst: '', total_price: ''
                    });
                }
            },
            deleteIMEI: function (index) {
                this.inventory.product_serial_numbers.splice(index, 1);
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
            onBrandChange(event) {
                console.log(event.target.value)
                this.fetchProductsByBrand(event.target.value);
            },
            onProductQtyChange(event) {
                if (event.target.value > 100) {
                    return false;
                }

                this.addIMEI(event.target.value);
                // this.fetchProductsByBrand(event.target.value);
            },
            fetchProductsByBrand(brandId) {
                axios.get(window.base_url + '/api/v1/auth/fetchProductsByBrand/' + brandId)
                    .then(response => {
                        this.products = response.data.data;
                        console.log(this.products);
                    })
                    .catch((err) => console.error(err));
            },
            addInventory(event) {
                // const found = this.inventory.product_serial_numbers.filter(el => el.imei_number === event.target.value);
                // if (found.length > 1) {
                //     Vue.$toast.error("Duplicate IMEI Numbered");
                //     return false;
                // }

                var valueArr = this.inventory.product_serial_numbers.filter(el => el.imei_number != "").map(function
                    (item) {
                    if (item.imei_number != '') {
                        return item.imei_number;
                    }
                });

                var isDuplicate = valueArr.some(function (item, idx) {
                    return valueArr.indexOf(item) != idx
                });
                if (isDuplicate) {
                    Vue.$toast.error("Duplicate IMEI Numbered");
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
                axios.post(window.base_url + '/api/v1/auth/addInventory', this.inventory)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success("Product Stock Added Successfully");
                            this.inventory.invoice_number = '';
                            this.inventory.supplier_id = '';
                            this.inventory.brand_id = '';
                            this.inventory.product_id = '';
                            this.inventory.product_qty = '';
                            // this.inventory.purchase_price = '';
                            this.inventory.product_serial_numbers = [];
                            // this.fetchBranches();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            },
            chckImei(event) {
                var valueArr = this.inventory.product_serial_numbers.filter(el => el.imei_number != "").map(function
                    (item) {
                    if (item.imei_number != '') {
                        return item.imei_number;
                    }
                });

                var isDuplicate = valueArr.some(function (item, idx) {
                    return valueArr.indexOf(item) != idx
                });
                if (isDuplicate) {
                    Vue.$toast.error("Duplicate IMEI Numbered");
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
        },
        components: {
            //
        }
    }
</script>

