<template>
    <div class="container">
        <div class="card card-default">
            <div class="card-header">Navigation</div>
            <div class="card-body p-0">
                <ul class="list-group" v-if="$auth.check('admin')">
                    <li class="list-group-item" v-bind:key="route.path" v-for="(route, key) in
                    routes.admin">
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
                        {name: "Manage Branches", path: "branch"},
                        {name: "Manage Users", path: "users"},
                        {name: "Suppliers", path: "supplier"},
                        {name: "Brands", path: "brand"},
                        {name: "Products", path: "product"},
                        {name: "Add Inventory", path: "addinventory"},
                        {name: "New Inventory", path: "inventory"},
                        {name: "Extra Inventory", path: "inventoryExtra"},
                        {name: "IMEI Stock By Location", path: "imeistockdetail"},
                        {name: "IMEI Sales By Location", path: "imeisalesdetail"},
                        {name: "Product Stock Details", path: "productstockdetail"}
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
