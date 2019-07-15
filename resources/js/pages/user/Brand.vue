<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header">Add New Brand</div>
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="has_error && !success">
                            <p v-if="error == 'registration_validation_error'">Validation Errors.</p>
                            <p v-else>Error, can not Add Brand at the moment. If the problem persists, please contact
                                an administrator.</p>
                        </div>
                        <form @submit.prevent="addBrand" autocomplete="off" method="post" v-if="!success">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.brand_name[0] }">
                                <label for="brand_name">Brand Name</label>
                                <input class="form-control" id="brand_name" placeholder="Brand Name" type="text"
                                       v-model="brand_name">
                                <span class="help-block" v-if="has_error && errors.brand_name[0]">{{ errors.brand_name[0] }}</span>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
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
            }
        },
        created() {
            this.fetchBrands();
        },
        methods: {
            addBrand() {
                var app = this
                axios.post(window.base_url + '/api/v1/auth/addBrand', {brand_name: app.brand_name})
                    .then(response => {
                        this.brand_name = '';
                        this.fetchBrands();
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
        },
        components: {
            //
        }
    }
</script>

