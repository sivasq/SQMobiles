<template>
    <div>
        <div class="container" id="user_form">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <div class="card card-default">
                        <div class="card-header" v-if="!isUpdate">Add New User</div>
                        <div class="card-header" v-if="isUpdate">Update User</div>
                        <div class="card-body">
                            <form autocomplete="off" method="post" v-if="!success"
                                  v-on:submit.prevent="handle_function_call(action)">
                                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.name }">
                                    <label for="name">Name</label>
                                    <input class="form-control" id="name" placeholder="Full Name" type="text"
                                           v-model="name">
                                    <span class="help-block" v-if="has_error && errors.name">{{ errors.name[0] }}</span>
                                </div>

                                <div class="form-group" v-bind:class="{ 'has_error': has_error && errors.email }">
                                    <label for="email">E-mail</label>
                                    <input class="form-control" id="email" placeholder="user@example.com" type="email"
                                           v-model="email">
                                    <span class="help-block"
                                          v-if="has_error && errors.email">{{ errors.email[0] }}</span>
                                </div>

                                <div class="form-group" v-bind:class="{ 'has_error': has_error && errors.mobile }">
                                    <label for="mobile">Mobile</label>
                                    <input @keypress="isNumber($event)" class="form-control" id="mobile"
                                           placeholder="1234567890" type="text"
                                           v-model="mobile">
                                    <span class="help-block"
                                          v-if="has_error && errors.mobile">{{ errors.mobile[0] }}</span>
                                </div>

                                <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.branch_id }">
                                    <label for="branch_id">Branch Name</label>
                                    <select class="form-control" id="branch_id" v-model="branch_id">
                                        <option disabled selected value="">Select Branch</option>
                                        <option :key="branch.id" :value="branch.id" class="list-group-item"
                                                v-for="(branch, index) in branches">{{branch.branch_name}}
                                        </option>
                                    </select>
                                    <span class="help-block"
                                          v-if="has_error && errors.branch_id">{{ errors.branch_id[0] }}</span>
                                </div>

                                <div class="form-group" v-bind:class="{ 'has_error': has_error && errors.password }">
                                    <label for="password">Password</label>
                                    <input autocomplete="new-password" class="form-control" id="password"
                                           type="password"
                                           v-model="password">
                                    <span class="help-block"
                                          v-if="has_error && errors.password">{{ errors.password[0] }}</span>
                                </div>

                                <div class="form-group" v-bind:class="{ 'has_error': has_error && errors.password }">
                                    <label for="password_confirmation">Password confirmation</label>
                                    <input class="form-control" id="password_confirmation" type="password"
                                           v-model="password_confirmation">
                                </div>

                                <button class="btn btn-primary" type="submit">Submit</button>

                                <button @click="cancelEdit()" class="btn btn-danger" v-if="isUpdate">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 mt-5">
                    <div class="card card-default">
                        <div class="card-header">Users List</div>
                        <table class="table table-responsive-xl">
                            <thead>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Branch</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <tr :key="user.id" v-for="(user, index) in users">
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.mobile}}</td>
                                <td>{{user.branch_details.branch_name}} - {{user.branch_details.branch_location}}</td>
                                <td>
                                    <button v-scroll-to="'#user_form'" @click="editUser(user)" class="btn btn-sm btn-outline-info">Edit</button>
                                    <button @click="deleteUser(user.id)" class="btn btn-sm btn-outline-danger"
                                            v-if="user.roles != 'admin' && user.activeStatus == true">Delete
                                    </button>
                                    <button @click="unDeleteUser(user.id)" class="btn btn-sm btn-outline-secondary"
                                            v-if="user.roles != 'admin' && user.activeStatus == false">UnDelete
                                    </button>
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
                user_id: '',
                name: '',
                email: '',
                mobile: '',
                password: '',
                password_confirmation: '',
                branch_id: '',
                roles: 'user',
                has_error: false,
                error: '',
                errors: {},
                success: false,
                branches: [],
                users: [],
                isUpdate: false,
                action: 'addUser'
            }
        },
        created() {
            this.fetchBranches();
            this.fetchUsers();
        },
        methods: {
            handle_function_call(function_name) {
                this[function_name]()
            },
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
            fetchBranches() {
                axios.get(window.base_url + '/api/v1/auth/fetchBranches')
                    .then(response => {
                        this.branches = response.data.data;
                    })
                    .catch((err) => console.error(err));
            },
            fetchUsers() {
                axios.get(window.base_url + '/api/v1/auth/fetchUsers')
                    .then(response => {
                        this.users = response.data.data;
                    })
                    .catch((err) => console.error(err));
            },
            addUser() {
                var app = this;
                axios.post(window.base_url + '/api/v1/auth/addUser', {
                    name: app.name,
                    email: app.email,
                    mobile: app.mobile,
                    branch_id: app.branch_id,
                    roles: app.roles,
                    password: app.password,
                    password_confirmation: app.password_confirmation
                })
                    .then(response => {
                        if (response.data.success) {
                            Vue.$toast.success("User Added Successfully");
                            this.name = '';
                            this.email = '';
                            this.mobile = '';
                            this.branch_id = '';
                            // this.roles = '';
                            this.password = '';
                            this.password_confirmation = '';
                            this.fetchUsers();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            },
            editUser(user) {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'updateUser';
                this.isUpdate = true;
                this.user_id = user.id;
                this.name = user.name;
                this.email = user.email;
                this.mobile = user.mobile;
                this.password = '';
                this.password_confirmation = '';
                this.branch_id = user.branch_details.id;
            },
            cancelEdit() {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'addUser';
                this.isUpdate = false;
                this.user_id = '';
                this.name = '';
                this.email = '';
                this.mobile = '';
                this.branch_id = '';
                this.password = '';
                this.password_confirmation = '';
            },
            updateUser() {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/updateUser/' + app.user_id, {
                    name: app.name,
                    email: app.email,
                    mobile: app.mobile,
                    branch_id: app.branch_id,
                    password: app.password,
                    password_confirmation: app.password_confirmation
                })
                    .then(response => {
                        if (response.data.success) {
                            Vue.$toast.success("User Updated Successfully");
                            this.isUpdate = false;
                            this.action = 'addUser';
                            this.name = '';
                            this.email = '';
                            this.mobile = '';
                            this.branch_id = '';
                            // this.roles = '';
                            this.password = '';
                            this.password_confirmation = '';
                            this.fetchUsers();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            },
            deleteUser(userid) {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/deleteUser/' + userid)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success(response.data.message);
                            this.fetchUsers();
                        }
                    })
                    .catch((res) => {

                    });
            },
            unDeleteUser(userid) {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/unDeleteUser/' + userid)
                    .then(response => {
                        if (response.data.status === 'success') {
                            Vue.$toast.success(response.data.message);
                            this.fetchUsers();
                        }
                    })
                    .catch((res) => {

                    });
            }
        }
    }
</script>
