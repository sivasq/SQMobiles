<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header" v-if="!isUpdate">Add New Brand</div>
                    <div class="card-header" v-if="isUpdate">Update Brand</div>
                    <div class="card-body">
                        <form autocomplete="off" method="post" v-if="!success"
                              v-on:submit.prevent="handle_function_call(action)">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.brand_name }">
                                <label for="brand_name">Brand Name</label>
                                <input class="form-control" id="brand_name" placeholder="Brand Name" type="text"
                                       v-model="brand_name">
                                <span class="help-block" v-if="has_error && errors.brand_name">{{ errors.brand_name[0] }}</span>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <button @click="cancelEdit()" class="btn btn-danger" v-if="isUpdate">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <ul class="list-group">
                    <li class="list-group-item text-center text-primary"> List of Brands</li>
                    <li class="list-group-item text-center text-danger" v-if='brands.length === 0'>There are no Brand
                        yet!
                    </li>
                    <li :key="brand.id" class="list-group-item" v-for="(brand, index) in brands">
                        {{index + 1}}) {{brand.brand_name}}
                        <button @click="editBrand(brand)" class="btn btn-sm btn-outline-info">Edit</button>
                        <button @click="deleteBrand(brand.id)" class="btn btn-sm btn-outline-danger"
                                v-if="brand.activeStatus == true">Delete</button>
                        <button @click="unDeleteBrand(brand.id)" class="btn btn-sm btn-outline-secondary"
                                v-if="brand.activeStatus == false">UnDelete
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
                brand_name: '',
                has_error: false,
                error: '',
                errors: {},
                success: false,
                brands: [],
                action: 'addBrand',
                brand_id: '',
                isUpdate: false
            }
        },
        created() {
            this.fetchBrands();
        },
        methods: {
            handle_function_call(function_name) {
                this[function_name]()
            },
            addBrand() {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/addBrand', {brand_name: app.brand_name})
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success("Brand Added Successfully");
                            this.brand_name = '';
                            this.fetchBrands();
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
            editBrand(brand) {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'updateBrand';
                this.isUpdate = true;
                this.brand_id = brand.id;
                this.brand_name = brand.brand_name;
            },
            cancelEdit() {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'addBrand';
                this.isUpdate = false;
                this.brand_id = '';
                this.brand_name = '';
            },
            updateBrand() {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/updateBrand/' + app.brand_id, {
                    brand_name: app.brand_name
                })
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success("Brand Updated Successfully");
                            this.isUpdate = false;
                            this.action = 'addBrand';
                            this.isUpdate = false;
                            this.brand_name = '';
                            this.fetchBrands();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            },
            deleteBrand(brandid) {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/deleteBrand/' + brandid)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success(response.data.message);
                            this.fetchBrands();
                        }
                    })
                    .catch((res) => {

                    });
            },
            unDeleteBrand(brandid) {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/unDeleteBrand/' + brandid)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success(response.data.message);
                            this.fetchBrands();
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

