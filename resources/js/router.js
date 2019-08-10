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
import Inventory from "./pages/user/Inventory";

// Routes
const routes = [
    {
        path: '',
        name: 'home',
        component: Login,
        meta: {
            auth: false,
            // auth: {
            //     redirect: { name: 'dashboard' },
            //     forbiddenRedirect: 'dashboard'
            // }
        }
    },
    {
        path: '/403',
        name: '403',
        component: About,
        meta: {
            auth: undefined
        }
    },
    // {
    //     path: '/about',
    //     name: 'about',
    //     component: About,
    //     meta: {
    //         auth: undefined
    //     }
    // },
    // {
    //     path: '/sqmobiles/public/register',
    //     name: 'register',
    //     component: Register,
    //     meta: {
    //         auth: false
    //     }
    // },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            // auth: false
            auth: {
                redirect: { name: 'dashboard' },
                forbiddenRedirect: 'dashboard'
            }
        }
    },
    // USER ROUTES
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth: true,
        }
    },
    {
        path: '/users',
        name: 'users',
        component: ManageUser,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: '403' },
                forbiddenRedirect: '/403'
            }
        }
    },
    {
        path: '/supplier',
        name: 'supplier',
        component: Supplier,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/admin/403'
            }
        }
    },
    {
        path: '/brand',
        name: 'brand',
        component: Brand,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/admin/403'
            }
        }
    },
    {
        path: '/branch',
        name: 'branch',
        component: Branch,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/admin/403'
            }
        }
    },
    {
        path: '/product',
        name: 'product',
        component: Product,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/admin/403'
            }
        }
    },
    {
        path: '/addinventory',
        name: 'addinventory',
        component: InventoryAdd,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/admin/403'
            }
        }
    },
    {
        path: '/inventory',
        name: 'inventory',
        component: Inventory,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/admin/403'
            }
        }
    },
    {
        path: '/imeistockdetail',
        name: 'imeistockdetail',
        component: IMEIStockDetails,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/admin/403'
            }
        }
    },
    {
        path: '/imeisalesdetail',
        name: 'imeisalesdetail',
        component: IMEISalesDetails,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/admin/403'
            }
        }
    },
    {
        path: '/productstockdetail',
        name: 'productstockdetail',
        component: ProductStockDetails,
        meta: {
            // auth: true,
            auth: {
                roles: 'admin',
                redirect: { name: 'admin' },
                forbiddenRedirect: '/admin/403'
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
