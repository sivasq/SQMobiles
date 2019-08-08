<template>
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-6">
                <div class="card card-default">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <div class="alert alert-danger" v-if="has_error && !success">
                            <p class="mb-0" v-if="error == 'unAuthorized'">Invalid Credentials.</p>
                            <p v-else>Error, unable to connect with these credentials.</p>
                        </div>
                        <form @submit.prevent="login" autocomplete="off" method="post">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input class="form-control" id="email" placeholder="user@example.com" required
                                       type="email" v-model="email">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input class="form-control" id="password" required type="password" v-model="password">
                            </div>
                            <button class="btn btn-primary" type="submit">Signin</button>
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
                email: null,
                password: null,
                success: false,
                has_error: false,
                error: ''
            }
        },
        mounted() {
            //
        },
        methods: {
            login() {
                // get the redirect object
                var redirect = this.$auth.redirect()
                var app = this
                this.$auth.login({
                    data: {
                        email: app.email,
                        password: app.password
                    },
                    success: function () {
                        // handle redirection
                        app.success = true;
                        const redirectTo = 'dashboard';
                        this.$router.push({name: redirectTo})
                    },
                    error: function (res) {
                        app.has_error = true;
                        app.error = res.response.data.message
                    },
                    rememberMe: true,
                    fetchUser: true
                })
            }
        }
    }
</script>
