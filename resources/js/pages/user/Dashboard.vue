<template>
    <div class="container">
        <div class="card card-default">
            <div class="card-header">Navigation</div>

            <div class="card-body p-0">
                <ul class="list-group" v-if="$auth.check('admin')">
                    <li class="list-group-item" v-bind:key="route.path"
                        v-for="(route, key) in
                    routes.admin" v-if="route.access.includes($auth.user().roles)">
                        <router-link :key="key" :to="{ name : route.path }" class="nav-link p-0">{{route.name}}
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
                        {name: "Manage Branches", path: "branch", access: ["admin"]},
                        {name: "Manage Users", path: "users", access: ["admin"]},
                        {name: "Suppliers", path: "supplier", access: ["admin"]},
                        {name: "Brands", path: "brand", access: ["admin"]},
                        {name: "Products", path: "product", access: ["admin"]},
                        {name: "Inventory Old", path: "addinventory", access: ["admin"]},
                        {name: "Inventory New", path: "inventory", access: ["admin"]},
                        {name: "IMEI Stock By Location", path: "imeistockdetail", access: ["admin"]},
                        {name: "IMEI Sales By Location", path: "imeisalesdetail", access: ["admin"]},
                        {name: "Product Stock Details", path: "productstockdetail", access: ["admin"]}
                    ],
                }
            }
        },
        computed: {
            checkUserPermission(access) {
                console.log(access);
                // console.log(access.includes($auth.user().roles))
                // return access.includes($auth.user().roles);
            }
        },
        created() {
            console.log(this.$auth.user());
        },
        methods: {
            //
        },
        components: {
            //
        }
    }
</script>
