<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header" v-if="!isUpdate">Add New Product</div>
                    <div class="card-header" v-if="isUpdate">Update Product</div>
                    <div class="card-body">
                        <form autocomplete="off" method="post" v-if="!success"
                              v-on:submit.prevent="handle_function_call(action)">

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.brand_id }">
                                <label for="product_name">Brand Name</label>

                                <select class="form-control" id="brand_id" v-model="product.brand_id">
                                    <option disabled selected value="">Select Brand</option>
                                    <option :key="brand.id" :value="brand.id" class="list-group-item"
                                            v-for="(brand, index) in brands">{{brand.brand_name}}
                                    </option>
                                </select>

                                <span class="help-block" v-if="has_error && errors.brand_id">{{
                                    errors.brand_id[0] }}</span>
                            </div>

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.product_name }">
                                <label for="product_name">Name</label>
                                <input class="form-control" id="product_name" placeholder="Product Name" type="text"
                                       v-model="product.product_name">
                                <span class="help-block" v-if="has_error && errors.product_name">{{
                                    errors.product_name[0] }}</span>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <button @click="cancelEdit()" class="btn btn-danger" v-if="isUpdate">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <ul class="list-group">
                    <li class="list-group-item text-center text-primary"> List Of Products</li>
                    <li class="list-group-item text-center text-danger" v-if='products.length === 0'>There are no
                        Product yet!
                    </li>
                    <li :key="product.id" class="list-group-item" v-for="(product, index) in products">
                        {{index + 1}}) {{product.brand_details.brand_name}} {{product.product_name}}
                        <button @click="editProduct(product)" class="btn btn-sm btn-outline-info">Edit</button>
                        <button @click="deleteProduct(product.id)" class="btn btn-sm btn-outline-danger">Delete</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                product: {
                    brand_id: '',
                    product_name: ''
                },
                has_error: false,
                error: '',
                errors: {},
                success: false,
                brands: [],
                products: [],
                action: 'addProduct',
                product_id: '',
                isUpdate: false
            }
        },
        created() {
            this.fetchBrands();
            this.fetchProducts();
        },
        methods: {
            handle_function_call(function_name) {
                this[function_name]()
            },
            addProduct() {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/addProduct', this.product)
                    .then(response => {
                        console.log(response.data.status);
                        if (response.data.status === 'success') {
                            this.product_id = '';
                            this.product.brand_id = '';
                            this.product.product_name = '';
                            this.fetchProducts();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            },
            fetchBrands() {
                axios.get(window.base_url + '/api/v1/auth/fetchBrands')
                    .then(response => {
                        this.brands = response.data.data;
                        console.log(this.brands);
                    })
                    .catch((err) => console.error(err));
            },
            fetchProducts() {
                axios.get(window.base_url + '/api/v1/auth/fetchProducts')
                    .then(response => {
                        this.products = response.data.data;
                        console.log(this.products);
                    })
                    .catch((err) => console.error(err));
            },
            editProduct(product) {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'updateProduct';
                this.isUpdate = true;
                this.product_id = product.id;
                this.product.product_name = product.product_name;
                this.product.brand_id = product.brand_details.id;
            },
            cancelEdit() {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'addProduct';
                this.isUpdate = false;
                this.product_id = '';
                this.product.product_name = '';
                this.product.brand_id = '';
            },
            updateProduct() {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/updateProduct/' + app.product_id, this.product)
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.isUpdate = false;
                            this.action = 'addProduct';
                            this.product_id = '';
                            this.product.brand_id = '';
                            this.product.product_name = '';
                            this.fetchProducts();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            },
            deleteProduct(productid) {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/deleteProduct/' + productid)
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.fetchProducts();
                        }
                    })
                    .catch((res) => {

                    });
            }
        },
        components: {
            //
        }
    }
</script>

