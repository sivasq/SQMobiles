<template>
    <div class="container">
        <div class="card card-default">
            <div class="card-header">Navigation</div>

            <div class="card-body p-0">
                <ul class="list-group" v-if="$auth.check('admin') || $auth.check('account') ||
                $auth.check('stockuser')">
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
                        {
                            name: "Manage Branches",
                            path: "branch", access: ["admin", "account"]
                        },
                        {
                            name: "Manage Users",
                            path: "users", access: ["admin", "account"]
                        },
                        {
                            name: "Suppliers",
                            path: "supplier", access: ["admin", "account"]
                        },
                        {
                            name: "Brands",
                            path: "brand", access: ["admin", "account", "stockuser"]
                        },
                        {
                            name: "Products",
                            path: "product", access: ["admin", "account", "stockuser"]
                        },
	                    {
		                    name: "ProductsPrice",
		                    path: "productprice", access: ["admin", "account"]
	                    },
                        // {
                        //     name: "Inventory Old",
                        //     path: "addinventory", access: ["admin", "account"]
                        // },
                        {
                            name: "Inventory",
                            path: "inventory", access: ["admin", "account", "stockuser"]
                        },
                        {
                            name: "IMEI Stock By Location",
                            path: "imeistockdetail",
                            access: ["admin", "account", "stockuser"]
                        },
                        {
                            name: "IMEI Sales By Location",
                            path: "imeisalesdetail", access: ["admin", "account"]
                        },
                        {
                            name: "Product Stock Details",
                            path: "productstockdetail", access: ["admin", "account"]
                        }
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
