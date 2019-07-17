import VueRouter from 'vue-router'
// Pages
// import Home from './pages/Home'
import About from './pages/About'
// import Register from './pages/Register'
import Login from './pages/Login'
import Dashboard from './pages/user/Dashboard'
import Supplier from './pages/user/Supplier'
import Brand from "./pages/user/Brand";
import Branch from "./pages/user/Branch";
import Product from "./pages/user/Product";
import InventoryAdd from "./pages/user/InventoryAdd";
import IMEIStockDetails from "./pages/user/IMEIStockDetails";
import ManageUser from "./pages/user/ManageUser";
import IMEISalesDetails from "./pages/user/IMEISalesDetails";
import ProductStockDetails from "./pages/user/ProductStockDetails";
// Routes
const routes = [
    {
        path: '/sqmobiles/public/',
        name: 'home',
        component: Login,
        meta: {
            auth: false
        }
    },
    {
        path: '/sqmobiles/public/about',
        name: 'about',
        component: About,
        meta: {
            auth: undefined
        }
    },
    // {
    //     path: '/sqmobiles/public/register',
    //     name: 'register',
    //     component: Register,
    //     meta: {
    //         auth: false
    //     }
    // },
    {
        path: '/sqmobiles/public/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    // USER ROUTES
    {
        path: '/sqmobiles/public/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'dashboard' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
    {
        path: '/sqmobiles/public/users',
        name: 'users',
        component: ManageUser,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
    {
        path: '/sqmobiles/public/supplier',
        name: 'supplier',
        component: Supplier,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
    {
        path: '/sqmobiles/public/brand',
        name: 'brand',
        component: Brand,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
    {
        path: '/sqmobiles/public/branch',
        name: 'branch',
        component: Branch,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
    {
        path: '/sqmobiles/public/product',
        name: 'product',
        component: Product,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
    {
        path: '/sqmobiles/public/addinventory',
        name: 'addinventory',
        component: InventoryAdd,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
    {
        path: '/sqmobiles/public/imeistockdetail',
        name: 'imeistockdetail',
        component: IMEIStockDetails,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
    {
        path: '/sqmobiles/public/imeisalesdetail',
        name: 'imeisalesdetail',
        component: IMEISalesDetails,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
    {
        path: '/sqmobiles/public/productstockdetail',
        name: 'productstockdetail',
        component: ProductStockDetails,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/sqmobiles/public/admin/403'
            }
        }
    },
];
const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
});
export default router
