<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header">Add New Inventory Stock</div>
                    <div class="card-body">
                        <form @submit.prevent="addInventory" autocomplete="off" method="post" v-if="!success">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.invoice_number }">
                                <label for="invoice_number">Bill Number</label>
                                <input class="form-control" id="invoice_number" placeholder="Bill Number" type="text"
                                       v-model="inventory.invoice_number">
                                <span class="help-block"
                                      v-if="has_error && errors.invoice_number">Bill NumberRequired</span>
                            </div>

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.supplier_id }">
                                <label for="supplier_id">Supplier Name</label>
                                <select class="form-control" id="supplier_id" v-model="inventory.supplier_id">
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

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.brand_id }">
                                <label for="brand_id">Brand Name</label>
                                <select @change="onBrandChange($event)" class="form-control" id="brand_id"
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

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.product_id }">
                                <label for="product_id">Product Name</label>
                                <select class="form-control" id="product_id" v-model="inventory.product_id">
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

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.purchase_price }">
                                <label for="product_qty">Purchase Price</label>
                                <input @keypress="isNumber($event)" class="form-control" id="purchase_price"
                                       placeholder="Purchase Price"
                                       type="text" v-model="inventory.purchase_price">
                                <span class="help-block"
                                      v-if="has_error && errors.purchase_price">{{ errors.purchase_price[0] }}</span>
                            </div>

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.product_qty }">
                                <label for="product_qty">Product Qty</label>
                                <input @keypress="isNumber($event)" class="form-control" id="product_qty"
                                       placeholder="Product Qty"
                                       type="text" v-model="inventory.product_qty"
                                       v-on:input="onProductQtyChange($event)">
                                <span class="help-block"
                                      v-if="has_error && errors.product_qty">{{ errors.product_qty[0] }}</span>
                            </div>

                            <div class="form-group" v-if="inventory.product_serial_numbers.length > 0">
                                <label for="imei_number">IMEI No.</label>
                                <div class="mb-1" v-for="(imei, index) in inventory.product_serial_numbers">
                                    <input class="form-control" id="imei_number" placeholder="IMEI No." type="text"
                                           v-model="imei.imei_number">
                                    <span class="help-block"
                                          v-if="has_error && errors['product_serial_numbers.'+index+'.imei_number']">{{errors['product_serial_numbers.'+index+'.imei_number'][0]}}
                                    </span>
                                </div>

                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>
                        </form>
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
                    this.inventory.product_serial_numbers.push({imei_number: ''});
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
                console.log(event.target.value);
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
            addInventory() {
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
                            this.inventory.purchase_price = '';
                            this.inventory.product_serial_numbers = [];
                            // this.fetchBranches();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            }
        },
        components: {
            //
        }
    }
</script>

