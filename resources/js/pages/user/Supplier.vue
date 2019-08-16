<template>
    <div class="container" id="supplier_form">
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

                            <div class="form-group"
                                 v-bind:class="{ 'has-error': has_error && errors.gstin }">
                                <label for="gstin">GSTIN</label>
                                <input class="form-control" id="gstin" placeholder="Supplier GSTIN" type="text"
                                       v-model="gstin">
                                <span class="help-block" v-if="has_error && errors.gstin">{{ errors.gstin[0] }}</span>
                            </div>

                            <div class="form-group"
                                 v-bind:class="{ 'has-error': has_error && errors.email }">
                                <label for="email">EMail</label>
                                <input class="form-control" id="email" placeholder="Supplier EMail" type="email"
                                       v-model="email">
                                <span class="help-block" v-if="has_error && errors.email">{{ errors.email[0] }}</span>
                            </div>

                            <div class="form-group"
                                 v-bind:class="{ 'has-error': has_error && errors.phone }">
                                <label for="phone">Phone</label>
                                <input class="form-control" id="phone" placeholder="Supplier Phone" type="text"
                                       v-model="phone">
                                <span class="help-block" v-if="has_error && errors.phone">{{ errors.phone[0] }}</span>
                            </div>

                            <div class="form-group"
                                 v-bind:class="{ 'has-error': has_error && errors.address }">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" placeholder="Supplier Address"
                                          v-model="address"></textarea>
                                <span class="help-block"
                                      v-if="has_error && errors.address">{{ errors.address[0] }}</span>
                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>

                            <button @click="cancelEdit()" class="btn btn-danger" v-if="isUpdate">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>GSTIN</th>
                        <th>Phone</th>
                        <th>EMail</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if='suppliers.length === 0'>
                        <td align="center" colspan="6">There are no Supplier yet!</td>
<!--                        <td align="center" colspan="6" v-if="loading"> Loading...</td>-->
                    </tr>
                    <tr :key="supplier.id" v-for="(supplier, index) in suppliers">
                        <td>{{supplier.supplier_name}}</td>
                        <td>{{supplier.gstin}}</td>
                        <td>{{supplier.phone}}</td>
                        <td>{{supplier.email}}</td>
                        <td>{{supplier.address}}</td>
                        <td>
                            <button v-scroll-to="'#supplier_form'" @click="editSupplier(supplier)"
                                    class="btn btn-sm btn-outline-info">Edit</button>
                            <button @click="deleteSupplier(supplier.id)" class="btn btn-sm btn-outline-danger"
                                    v-if="supplier.activeStatus == true">Delete
                            </button>
                            <button @click="unDeleteSupplier(supplier.id)" class="btn btn-sm btn-outline-secondary"
                                    v-if="supplier.activeStatus == false">UnDelete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
<!--                <ul class="list-group">-->
<!--                    <li class="list-group-item text-center text-primary"> List Of Suppliers</li>-->
<!--                    <li class="list-group-item text-center text-danger" v-if='suppliers.length === 0'>There are no-->
<!--                        Supplier-->
<!--                        yet!-->
<!--                    </li>-->
<!--                    <li :key="supplier.id" class="list-group-item" v-for="(supplier, index) in suppliers">-->
<!--                        {{index + 1}}) {{supplier.supplier_name}}-->
<!--                        <button @click="editSupplier(supplier)" class="btn btn-sm btn-outline-info">Edit</button>-->
<!--                        <button @click="deleteSupplier(supplier.id)" class="btn btn-sm btn-outline-danger"-->
<!--                                v-if="supplier.activeStatus == true">Delete-->
<!--                        </button>-->
<!--                        <button @click="unDeleteSupplier(supplier.id)" class="btn btn-sm btn-outline-secondary"-->
<!--                                v-if="supplier.activeStatus == false">UnDelete-->
<!--                        </button>-->
<!--                    </li>-->
<!--                </ul>-->
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                supplier_name: '',
                gstin: '',
                email: '',
                phone: '',
                address: '',
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
                axios.post(window.base_url + '/api/v1/auth/addSupplier', {
                    supplier_name: app.supplier_name,
                    gstin: app.gstin,
                    email: app.email,
                    phone: app.phone,
                    address: app.address,
                })
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success("Supplier Added Successfully");
                            this.supplier_name = '';
                            this.gstin = '';
                            this.email = '';
                            this.phone = '';
                            this.address = '';
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
                this.gstin = supplier.gstin;
                this.email = supplier.email;
                this.phone = supplier.phone;
                this.address = supplier.address;
            },
            cancelEdit() {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'addSupplier';
                this.isUpdate = false;
                this.supplier_id = '';
                this.supplier_name = '';
                this.gstin = '';
                this.email = '';
                this.phone = '';
                this.address = '';
            },
            updateSupplier() {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/updateSupplier/' + app.supplier_id, {
                    supplier_name: app.supplier_name,
                    gstin: app.gstin,
                    email: app.email,
                    phone: app.phone,
                    address: app.address,
                })
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success("Supplier Updated Successfully");
                            this.isUpdate = false;
                            this.action = 'addSupplier';
                            this.isUpdate = false;
                            this.supplier_name = '';
                            this.gstin = '';
                            this.email = '';
                            this.phone = '';
                            this.address = '';
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

