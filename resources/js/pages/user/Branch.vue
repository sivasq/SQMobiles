<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-6">
                <div class="card card-default">
                    <div class="card-header">Add New Branch</div>
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="has_error && !success">
                            <p v-if="error == 'registration_validation_error'">Validation Errors.</p>
                            <p v-else>Error, can not Add Branch at the moment. If the problem persists, please contact
                                an administrator.</p>
                        </div>
                        <div class="alert alert-success" v-if="submitted">
                            Created!
                        </div>
                        <form @submit.prevent="addBranch" autocomplete="off" method="post" v-if="!success">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.branch_name[0]
                             }">
                                <label for="branch_name">Name</label>
                                <input class="form-control" id="branch_name" placeholder="Branch Name" type="text"
                                       v-model="branch.branch_name">
                                <span class="help-block"
                                      v-if="has_error && errors.branch_name[0]">{{ errors.branch_name[0] }}</span>
                            </div>

                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.branch_location[0] }">
                                <label for="branch_location">Location</label>
                                <input class="form-control" id="branch_location" placeholder="Branch Location"
                                       type="text"
                                       v-model="branch.branch_location">
                                <span class="help-block"
                                      v-if="has_error && errors.branch_location[0]">{{ errors.branch_location[0] }}</span>
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
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
            }
        },
        created() {
            this.fetchBranches();
        },
        methods: {
            addBranch() {
                var app = this
                axios.post(window.base_url + '/api/v1/auth/addBranch', this.branch)
                    .then(response => {
                        this.branch.branch_name = '';
                        this.branch.branch_location = '';
                        this.fetchBranches();
                    })
                    .catch((res) => {
                        app.has_error = true
                        app.error = res.response.data.error
                        app.errors = res.response.data.errors || {}
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
        },
        components: {
            //
        }
    }
</script>

