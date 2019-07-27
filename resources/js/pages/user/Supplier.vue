<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header" v-if="!isUpdate">Add New Supplier</div>
                    <div class="card-header" v-if="isUpdate">Update Supplier</div>
                    <div class="card-body">
                        <form autocomplete="off" method="post" v-if="!success"
                              v-on:submit.prevent="handle_function_call(action)">
                            <div class="form-group"
                                 v-bind:class="{ 'has-error': has_error && errors.supplier_name }">
                                <label for="supplier_name">Name</label>
                                <input class="form-control" id="supplier_name" placeholder="Supplier Name" type="text"
                                       v-model="supplier_name">
                                <span class="help-block" v-if="has_error && errors.supplier_name">{{ errors.supplier_name[0] }}</span>
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
                        <button @click="deleteSupplier(supplier.id)" class="btn btn-sm btn-outline-danger"
                                v-if="supplier.activeStatus == true">Delete
                        </button>
                        <button @click="unDeleteSupplier(supplier.id)" class="btn btn-sm btn-outline-secondary"
                                v-if="supplier.activeStatus == false">UnDelete
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
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/addSupplier', {supplier_name: app.supplier_name})
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success("Supplier Added Successfully");
                            this.supplier_name = '';
                            this.fetchSuppliers();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
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
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'updateSupplier';
                this.isUpdate = true;
                this.supplier_id = supplier.id;
                this.supplier_name = supplier.supplier_name;
            },
            cancelEdit() {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'addSupplier';
                this.isUpdate = false;
                this.supplier_id = '';
                this.supplier_name = '';
            },
            updateSupplier() {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/updateSupplier/' + app.supplier_id, {
                    supplier_name: app.supplier_name
                })
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success("Supplier Updated Successfully");
                            this.isUpdate = false;
                            this.action = 'addSupplier';
                            this.isUpdate = false;
                            this.supplier_name = '';
                            this.fetchSuppliers();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            },
            deleteSupplier(supplierid) {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/deleteSupplier/' + supplierid)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success(response.data.message);
                            this.fetchSuppliers();
                        }
                    })
                    .catch((res) => {

                    });
            },
            unDeleteSupplier(supplierid) {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/unDeleteSupplier/' + supplierid)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success(response.data.message);
                            this.fetchSuppliers();
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

