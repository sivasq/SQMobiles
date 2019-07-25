<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-6">
                <div class="card card-default">
                    <div class="card-header" v-if="!isUpdate">Add New Branch</div>
                    <div class="card-header" v-if="isUpdate">Update Branch</div>
                    <div class="card-body">
                        <div class="alert alert-success" v-if="submitted">
                            Created!
                        </div>
                        <form autocomplete="off" method="post" v-if="!success"
                              v-on:submit.prevent="handle_function_call(action)">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.branch_name
                             }">
                                <label for="branch_name">Name</label>
                                <input class="form-control" id="branch_name" placeholder="Branch Name" type="text"
                                       v-model="branch.branch_name">
                                <span class="help-block"
                                      v-if="has_error && errors.branch_name">{{ errors.branch_name[0] }}</span>
                            </div>

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.branch_location }">
                                <label for="branch_location">Location</label>
                                <input class="form-control" id="branch_location" placeholder="Branch Location"
                                       type="text"
                                       v-model="branch.branch_location">
                                <span class="help-block"
                                      v-if="has_error && errors.branch_location">{{ errors.branch_location[0] }}</span>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <button @click="cancelEdit()" class="btn btn-danger" v-if="isUpdate">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-5">
                <ul class="list-group">
                    <li class="list-group-item text-center text-primary"> List Of Branches</li>
                    <li class="list-group-item text-center text-danger" v-if='branches.length === 0'>There are no branch
                        yet!
                    </li>
                    <li :key="branch.id" class="list-group-item" v-for="(branch, index) in branches">
                        {{index + 1}}) {{branch.branch_name}} - {{branch.branch_location}}
                        <button @click="editBranch(branch)" class="btn btn-sm btn-outline-info">Edit</button>
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
                branch: {
                    branch_name: '',
                    branch_location: '',
                },
                has_error: false,
                error: '',
                errors: {},
                success: false,
                branches: [],
                submitted: false,
                action: 'addBranch',
                branch_id: '',
                isUpdate: false
            }
        },
        created() {
            this.fetchBranches();
        },
        methods: {
            handle_function_call(function_name) {
                this[function_name]()
            },
            addBranch() {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/addBranch', this.branch)
                    .then(response => {
                        console.log(response.data.status);
                        if (response.data.status === 'success') {
                            this.branch.branch_name = '';
                            this.branch.branch_location = '';
                            this.fetchBranches();
                        }
                    })
                    .catch((res) => {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {};
                    });
            },
            fetchBranches() {
                axios.get(window.base_url + '/api/v1/auth/fetchBranches')
                    .then(response => {
                        this.branches = response.data.data;
                        console.log(this.branches);
                    })
                    .catch((err) => console.error(err));
            },
            editBranch(branch) {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'updateBranch';
                this.isUpdate = true;
                this.branch_id = branch.id;
                this.branch.branch_name = branch.branch_name;
                this.branch.branch_location = branch.branch_location;
            },
            cancelEdit() {
                this.has_error = false;
                this.error = '';
                this.errors = {};
                this.action = 'addBranch';
                this.isUpdate = false;
                this.branch_id = '';
                this.branch_name = '';
            },
            updateBranch() {
                var app = this;
                app.has_error = false;
                app.errors = {};
                axios.post(window.base_url + '/api/v1/auth/updateBranch/' + app.branch_id, {
                    branch_name: app.branch.branch_name, branch_location: app.branch.branch_location
                })
                    .then(response => {
                        if (response.data.status === 'success') {
                            this.isUpdate = false;
                            this.action = 'addBranch';
                            this.branch.branch_name = '';
                            this.branch.branch_location = '';
                            this.fetchBranches();
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

