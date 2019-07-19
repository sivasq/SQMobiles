<template>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <router-link :to="{name: 'home'}" class="navbar-brand">SQIndia Mobiles</router-link>
        <button
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
            class="navbar-toggler"
            data-target="#navbarSupportedContent"
            data-toggle="collapse"
            type="button"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto" v-if="$auth.check('user')">
                <li class="nav-item" v-bind:key="route.path" v-for="(route, key) in routes.user">
                    <router-link :key="key" :to="{ name : route.path }" class="nav-link">{{route.name}}</router-link>
                </li>
            </ul>
            <ul class="navbar-nav mr-auto" v-if="$auth.check('admin')">
                <li class="nav-item" v-bind:key="route.path" v-for="(route, key) in routes.user">
                    <router-link :key="key" :to="{ name : route.path }" class="nav-link">{{route.name}}</router-link>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto" v-if="!$auth.check()">
                <li class="nav-item" v-bind:key="route.path" v-for="(route, key) in routes.unlogged">
                    <router-link :key="key" :to="{ name : route.path }" class="nav-link">{{route.name}}</router-link>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto" v-if="$auth.check()">
                <li class="nav-item">
                    <a @click.prevent="$auth.logout()" class="nav-link" href="#">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</template>
<script>
    export default {
        data() {
            return {
                routes: {
                    // UNLOGGED
                    unlogged: [
                        // {name: "Register", path: "register"},
                        // {name: "Login", path: "login"}
                    ],
                    // LOGGED USER
                    user: [
                        {name: "Dashboard", path: "dashboard"}
                    ]
                    // LOGGED ADMIN
                    // admin: [{name: "Dashboard", path: "admin.dashboard"}]
                }
            };
        },
        mounted() {
            console.log('Component mounted.');
        }
    };
</script>
<style>
    .navbar {
        margin-bottom: 30px;
    }
</style>
