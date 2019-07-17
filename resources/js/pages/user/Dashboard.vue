<template>
    <div class="container">
        <div class="card card-default">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                <ul class="navbar-nav mr-auto" v-if="$auth.check('admin')">
                    <li class="nav-item" v-bind:key="route.path" v-for="(route, key) in routes.admin">
                        <router-link :key="key" :to="{ name : route.path }" class="nav-link">{{route.name}}
                        </router-link>
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
                routes: {
                    // LOGGED
                    admin: [
                        {name: "Manage Branches", path: "branch"},
                        {name: "Manage Users", path: "users"},
                        {name: "Suppliers", path: "supplier"},
                        {name: "Brands", path: "brand"},
                        {name: "Products", path: "product"},
                        {name: "Add Inventory", path: "addinventory"},
                        {name: "IMEI Stock Details", path: "imeistockdetail"},
                        {name: "IMEI Sales Details", path: "imeisalesdetail"}
                    ],
                }
            }
        },
        created() {
            //
        },
        methods: {
            //
        },
        components: {
            //
        }
    }
</script>
