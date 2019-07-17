<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header">Add New Product</div>
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="has_error && !success">
                            <p v-if="error == 'registration_validation_error'">Validation Errors.</p>
                            <p v-else>Error, can not Add Product at the moment. If the problem persists, please contact
                                an administrator.</p>
                        </div>
                        <form @submit.prevent="addProduct" autocomplete="off" method="post" v-if="!success">

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.brand_id[0] }">
                                <label for="product_name">Brand Name</label>

                                <select class="form-control" id="brand_id" v-model="product.brand_id">
                                    <option disabled selected value="">Select Brand</option>
                                    <option :key="brand.id" :value="brand.id" class="list-group-item"
                                            v-for="(brand, index) in brands">{{brand.brand_name}}
                                    </option>
                                </select>

                                <span class="help-block" v-if="has_error && errors.brand_id[0]">{{
                                    errors.brand_id[0] }}</span>
                            </div>

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.product_name[0] }">
                                <label for="product_name">Name</label>
                                <input class="form-control" id="product_name" placeholder="Product Name" type="text"
                                       v-model="product.product_name">
                                <span class="help-block" v-if="has_error && errors.product_name[0]">{{
                                    errors.product_name[0] }}</span>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
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
                        {{index + 1}}) {{product.product_name}}
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
                products: []
            }
        },
        created() {
            this.fetchBrands();
            this.fetchProducts();
        },
        methods: {
            addProduct() {
                var app = this;
                console.log(this.product);
                axios.post(window.base_url + '/api/v1/auth/addProduct', this.product)
                    .then(response => {
                        this.product.brand_id = '';
                        this.product.product_name = '';
                        this.fetchProducts();
                    })
                    .catch((res) => {
                        app.has_error = true
                        app.error = res.response.data.error
                        app.errors = res.response.data.errors || {}
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
        },
        components: {
            //
        }
    }
</script>

