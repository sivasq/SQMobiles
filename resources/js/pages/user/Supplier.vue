<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header" v-if="!isUpdate">Add New Supplier</div>
                    <div class="card-header" v-if="isUpdate">Update Supplier</div>
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="has_error && !success">
                            <p v-if="error == 'registration_validation_error'">Validation Errors.</p>
                            <p v-else>Error, can not Add Supplier at the moment. If the problem persists, please contact
                                an administrator.</p>
                        </div>
                        <form v-on:submit.prevent="handle_function_call(action)" autocomplete="off" method="post" v-if="!success">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.supplier_name[0] }">
                                <label for="supplier_name">Name</label>
                                <input class="form-control" id="supplier_name" placeholder="Supplier Name" type="text"
                                       v-model="supplier_name">
                                <span class="help-block" v-if="has_error && errors.supplier_name[0]">{{ errors.supplier_name[0] }}</span>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>

                            <button @click="cancelEdit()" class="btn btn-danger" v-if="isUpdate">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <ul class="list-group">
                    <li class="list-group-item text-center text-primary"> List Of Suppliers</li>
                    <li class="list-group-item text-center text-danger" v-if='suppliers.length === 0'>There are no
                        Supplier
                        yet!
                    </li>
                    <li :key="supplier.id" class="list-group-item" v-for="(supplier, index) in suppliers">
                        {{index + 1}}) {{supplier.supplier_name}}
                        <button @click="editSupplier(supplier)" class="btn btn-sm btn-outline-info">Edit</button>
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
                supplier_name: '',
                has_error: false,
                error: '',
                errors: {},
                success: false,
                suppliers: [],
                action: 'addSupplier',
                supplier_id: '',
                isUpdate: false
            }
        },
        created() {
            this.fetchSuppliers();
        },
        methods: {
            handle_function_call(function_name) {
                this[function_name]()
            },
            addSupplier() {
                var app = this;
                axios.post(window.base_url + '/api/v1/auth/addSupplier', {supplier_name: app.supplier_name})
                    .then(response => {
                        this.supplier_name = '';
                        this.fetchSuppliers();
                    })
                    .catch((res) => {
                        app.has_error = true
                        app.error = res.response.data.error
                        app.errors = res.response.data.errors || {}
                    });
            },
            fetchSuppliers() {
                axios.get(window.base_url + '/api/v1/auth/fetchSuppliers')
                    .then(response => {
                        this.suppliers = response.data.data;
                    })
                    .catch((err) => console.error(err));
            },
            editSupplier(supplier) {
                this.action = 'updateSupplier';
                this.isUpdate = true;
                this.supplier_id = supplier.id;
                this.supplier_name = supplier.supplier_name;
            },
            cancelEdit() {
                this.action = 'addSupplier';
                this.isUpdate = false;
                this.supplier_id = '';
                this.supplier_name = '';
            },
            updateSupplier() {
                var app = this;
                axios.post(window.base_url + '/api/v1/auth/updateSupplier/' + app.supplier_id, {
                    supplier_name: app.supplier_name
                })
                    .then(response => {
                        this.isUpdate = false;
                        this.supplier_name = '';
                        this.fetchSuppliers();
                    })
                    .catch((res) => {
                        app.has_error = true
                        app.error = res.response.data.error
                        app.errors = res.response.data.errors || {}
                    });
            }
        },
        components: {
            //
        }
    }
</script>

