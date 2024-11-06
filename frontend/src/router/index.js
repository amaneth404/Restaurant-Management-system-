import Vue from 'vue'
import VueRouter from 'vue-router'
import Layout from './../layouts'
import Login from './../views/login'
import Print from './../components/Cards/admin/requested/print.vue'
import Register from './../views/register'
Vue.use(VueRouter)
const routes = [{
        path: '/login',
        name: 'login',
        component: Login,
        props() {
            return { path: 'login' }
        }
    },
    {
        path: '/',
        name: 'login',
        component: Login,
        props() {
            return { path: 'login' }
        }
    },
    {
        path: '/print',
        name: 'print',
        component: Print,
        props() {
            return { path: 'print' }
        }
    },
    {
        path: '/loc',
        name: 'loc',
        component: null,
        meta: {
            localaccess: true
        },
        props() {
            return { path: 'pub' }
        }
    },
    {
        path: '/pub',
        name: 'pub',
        component: null,
        meta: {
            publicaccess: true
        },
        props() {
            return { path: 'pub' }
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        props() {
            return { path: 'register' }
        }
    },
    {
        path: '/admin',
        name: '',
        component: Layout,
        meta: {
            requireslicence: true
        },
        props() {
            return { type: 'dash' }
        },
        children: [{
                path: '/',
                component: () =>
                    import ('./../components/Cards/admin/dashbored/dashbored.vue'),
                name: 'home'
            },
            {
                path: '/salary',
                component: () =>
                    import ('./../components/Cards/admin/salary/salary.vue'),
                name: 'home'
            },
            {
                path: '/manage/credit',
                component: () =>
                    import ('./../components/Cards/admin/credit/credit.vue'),
                name: 'home'
            },
            {
                path: '/manage/totalcash',
                component: () =>
                    import ('./../components/Cards/admin/TotalCash/category.vue'),
                name: 'home'
            },
            {
                path: '/manage/bank',
                component: () =>
                    import ('./../components/Cards/admin/Bank/bank.vue'),
                name: 'home'
            },
            {
                path: '/manage/waiter',
                component: () =>
                    import ('./../components/Cards/admin/WaiterReports/report.vue'),
                name: 'home'
            },
            {
                path: '/casher/report',
                component: () =>
                    import ('./../components/Cards/admin/CasherReports/report.vue'),
                name: 'casher report'
            },
            {
                path: '/expense',
                component: () =>
                    import ('./../components/Cards/admin/expense/expense.vue'),
                name: 'casher report'
            },
            {
                path: '/items/log',
                component: () =>
                    import ('./../components/Cards/admin/ItemsLog/item.vue'),
                name: 'Items Log'
            },
            {
                path: '/manage/menu',
                component: () =>
                    import ('./../components/Cards/admin/menu/menu.vue'),
                name: 'menu'
            },
            {
                path: '/manage/requests',
                component: () =>
                    import ('./../components/Cards/admin/requested/requested.vue'),
                name: 'requests'
            },
            {
                path: '/manage/transfered',
                component: () =>
                    import ('./../components/Cards/admin/transfered/item.vue'),
                name: 'transfered'
            },
            {
                path: '/manage/order',
                component: () =>
                    import ('./../components/Cards/admin/cahser/menu.vue'),
                name: 'order'
            },
            {
                path: '/manage/waiting/orders',
                component: () =>
                    import ('./../components/Cards/admin/WaitingOrder/order.vue'),
                name: 'waiting orders'
            },
            {
                path: '/manage/chef/waiting/orders',
                component: () =>
                    import ('./../components/Cards/admin/WaitingOrder/cheforder.vue'),
                name: 'waiting orders'
            },
            {
                path: '/manage/chef/done/orders',
                component: () =>
                    import ('./../components/Cards/admin/WaitingOrder/cheforderDone.vue'),
                name: 'waiting orders'
            },
            {
                path: '/manage/flaged/orders',
                component: () =>
                    import ('./../components/Cards/admin/FlagedOrder/order.vue'),
                name: 'flagged orders'
            },
            {
                path: '/manage/users',
                component: () =>
                    import ('./../components/Cards/admin/Users/AllUsers.vue'),
                name: 'All Users'
            },
            {
                path: '/account/and/profile',
                component: () =>
                    import ('./../components/Cards/admin/AcountAndProfile/acount.vue'),
                name: 'profile'
            },
            {
                path: '/manage/categorys',
                component: () =>
                    import ('./../components/Cards/admin/Category/category.vue'),
                name: 'Category'
            },
            {
                path: '/manage/chef/order',
                component: () =>
                    import ('./../components/Cards/admin/WaitingOrder/admin.vue'),
                name: 'Category'
            },
            {
                path: '/manage/categorys/expense',
                component: () =>
                    import ('./../components/Cards/admin/ExpenseCategory/category.vue'),
                name: 'Category'
            },
            {
                path: '/manage/item',
                component: () =>
                    import ('./../components/Cards/admin/Item/item.vue'),
                name: 'item'
            },
            {
                path: '/manage/items',
                component: () =>
                    import ('./../components/Cards/admin/Items/item.vue'),
                name: 'items'
            }
        ]
    },
]

const router = new VueRouter({
    routes
    // mode: 'history'
})

export default router