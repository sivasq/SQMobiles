<template>
    <div class="container" id="product_form">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header" v-if="!isUpdate">Add New Product</div>
                    <div class="card-header" v-if="isUpdate">Update Product</div>
                    <div class="card-body">
                        <form autocomplete="off" method="post" v-if="!success"
                              v-on:submit.prevent="handle_function_call(action)">

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.brand_id }">
                                <label for="brand_id">Brand Name</label>

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
                <input class="form-control mb-1" placeholder="Filter By Brand" type="search" v-model="searchBrand">
                <ul class="list-group">
                    <li class="list-group-item text-center text-primary"> Products List &nbsp;&nbsp;&nbsp;<button
                        @click.prevent="exportData" class="btn btn-sm btn-outline-secondary">Export As Excel
                    </button>
                    </li>
                    <li class="list-group-item text-center text-danger" v-if='products.length === 0'>There are no
                        Product yet!
                    </li>
                    <li :key="product.id" class="list-group-item" v-for="(product, index) in filterProducts">
                        {{index + 1}}) {{product.brand_details.brand_name}} {{product.product_name}}
                        <button v-scroll-to="'#product_form'" @click="editProduct(product)"
                                class="btn btn-sm btn-outline-info">Edit</button>
                        <button @click="deleteProduct(product.id)" class="btn btn-sm btn-outline-danger"
                                v-if="($auth.check('admin') || $auth.check('account')) && product.activeStatus == true">
                            Delete
                        </button>
                        <button @click="unDeleteProduct(product.id)" class="btn btn-sm btn-outline-secondary"
                                v-if="($auth.check('admin') || $auth.check('account')) && product.activeStatus == false">
                            UnDelete
                        </button>
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
                isUpdate: false,
                searchBrand: '',
            }
        },
        created() {
            this.fetchBrands();
            this.fetchProducts();
        },
        computed: {
            filterProducts() {
                return this.products.filter(stock => {
                    return !this.searchBrand.trim() ||
                        stock.brand_details.brand_name.toLowerCase().indexOf(this.searchBrand.toLowerCase().trim()) > -1
                    // return client.imei_number.indexOf(this.searchClient) > -1;
                });
            }
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
                            Vue.$toast.success("Product Added");
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
            exportData() {
                axios({
                    url: window.base_url + '/api/v1/auth/fetchProductsExcel',
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
                            Vue.$toast.success("Product updated");
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
                            Vue.$toast.success(response.data.message);
                            this.fetchProducts();
                        }
                    })
                    .catch((res) => {

                    });
            },
            unDeleteProduct(productid) {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/unDeleteProduct/' + productid)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success(response.data.message);
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

