import VueRouter from 'vue-router'
// Pages
import Home from './pages/Home'
import About from './pages/About'
import Register from './pages/Register'
import Login from './pages/Login'
import Dashboard from './pages/user/Dashboard'
import Supplier from './pages/user/Supplier'
import Brand from "./pages/user/Brand";
import Branch from "./pages/user/Branch";
import Product from "./pages/user/Product";
import InventoryAdd from "./pages/user/InventoryAdd";
import StockDetails from "./pages/user/StockDetails";
import ManageUser from "./pages/user/ManageUser";
// Routes
const routes = [
    {
        path: '/sqindia/public/',
        name: 'home',
        component: Login,
        meta: {
            auth: false
        }
    },
    {
        path: '/sqindia/public/about',
        name: 'about',
        component: About,
        meta: {
            auth: undefined
        }
    },
    // {
    //     path: '/sqindia/public/register',
    //     name: 'register',
    //     component: Register,
    //     meta: {
    //         auth: false
    //     }
    // },
    {
        path: '/sqindia/public/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    // USER ROUTES
    {
        path: '/sqindia/public/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'dashboard' },
                forbiddenRedirect: '/sqindia/public/admin/403'
            }
        }
    },
    {
        path: '/sqindia/public/users',
        name: 'users',
        component: ManageUser,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqindia/public/admin/403'
            }
        }
    },
    {
        path: '/sqindia/public/supplier',
        name: 'supplier',
        component: Supplier,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqindia/public/admin/403'
            }
        }
    },
    {
        path: '/sqindia/public/brand',
        name: 'brand',
        component: Brand,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqindia/public/admin/403'
            }
        }
    },
    {
        path: '/sqindia/public/branch',
        name: 'branch',
        component: Branch,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqindia/public/admin/403'
            }
        }
    },
    {
        path: '/sqindia/public/product',
        name: 'product',
        component: Product,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqindia/public/admin/403'
            }
        }
    },
    {
        path: '/sqindia/public/addinventory',
        name: 'addinventory',
        component: InventoryAdd,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqindia/public/admin/403'
            }
        }
    },
    {
        path: '/sqindia/public/stockdetail',
        name: 'stockdetail',
        component: StockDetails,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqindia/public/admin/403'
            }
        }
    },
]
const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
})
export default router
