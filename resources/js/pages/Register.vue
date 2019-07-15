<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="has_error && !success">
                            <p v-if="error == 'registration_validation_error'">Validation Errors.</p>
                            <p v-else>Error, can not register at the moment. If the problem persists, please contact an
                                administrator.</p>
                        </div>

                        <form @submit.prevent="register" autocomplete="off" method="post" v-if="!success">
                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.name[0] }">
                                <label for="name">Name</label>
                                <input class="form-control" id="name" placeholder="Full Name" type="text"
                                       v-model="name">
                                <span class="help-block" v-if="has_error && errors.name[0]">{{ errors.name[0] }}</span>
                            </div>

                            <div class="form-group" v-bind:class="{ 'has_error': has_error && errors.email[0] }">
                                <label for="email">E-mail</label>
                                <input class="form-control" id="email" placeholder="user@example.com" type="email"
                                       v-model="email">
                                <span class="help-block"
                                      v-if="has_error && errors.email[0]">{{ errors.email[0] }}</span>
                            </div>

                            <div class="form-group" v-bind:class="{ 'has_error': has_error && errors.mobile[0] }">
                                <label for="mobile">Mobile</label>
                                <input @keypress="isNumber($event)" class="form-control" id="mobile"
                                       placeholder="1234567890" type="text"
                                       v-model="mobile">
                                <span class="help-block"
                                      v-if="has_error && errors.mobile[0]">{{ errors.mobile[0] }}</span>
                            </div>

<!--                            <div class="form-group" v-bind:class="{ 'has-error': has_error && errors.branch_id }">-->
<!--                                <label for="branch_id">Branch Name</label>-->
<!--                                <select class="form-control" id="branch_id"-->
<!--                                        v-model="branch_id">-->
<!--                                    <option disabled selected value="">Select Branch</option>-->
<!--                                    <option :key="branch.id" :value="branch.id"-->
<!--                                            class="list-group-item" v-for="(branch, index) in branches">{{branch-->
<!--                                        .branch_name}}-->
<!--                                    </option>-->
<!--                                </select>-->
<!--                                <span class="help-block"-->
<!--                                      v-if="has_error && errors.branch_id">{{ errors.branch_id }}</span>-->
<!--                            </div>-->

                            <div class="form-group" v-bind:class="{ 'has_error': has_error && errors.password[0] }">
                                <label for="password">Password</label>
                                <input class="form-control" id="password" type="password" v-model="password">
                                <span class="help-block"
                                      v-if="has_error && errors.password[0]">{{ errors.password[0] }}</span>
                            </div>

                            <div class="form-group" v-bind:class="{ 'has_error': has_error && errors.password[0] }">
                                <label for="password_confirmation">Password confirmation</label>
                                <input class="form-control" id="password_confirmation" type="password"
                                       v-model="password_confirmation">
                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>
                        </form>
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
                name: '',
                email: '',
                mobile: '',
                password: '',
                password_confirmation: '',
                // branch_id: '',
                roles: 'user',
                has_error: false,
                error: '',
                errors: {},
                success: false,
                branches: []
            }
        },
        created() {
            // this.fetchBranches();
        },
        methods: {
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
            register() {
                var app = this
                this.$auth.register({
                    data: {
                        name: app.name,
                        email: app.email,
                        mobile: app.mobile,
                        // branch_id: app.branch_id,
                        roles: app.roles,
                        password: app.password,
                        password_confirmation: app.password_confirmation
                    },
                    success: function () {
                        app.success = true;
                        this.$router.push({name: 'login', params: {successRegistrationRedirect: true}})
                    },
                    error: function (res) {
                        app.has_error = true;
                        app.error = res.response.data.error;
                        app.errors = res.response.data.errors || {}
                    }
                })
            }
        }
    }
</script>
