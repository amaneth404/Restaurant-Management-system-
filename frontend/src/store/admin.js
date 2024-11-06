import { Admin } from '../api/api'
export default ({
    namespaced: true,
    state: {
        status: '',
        users: {},
        category: {},
        games: {},
        filteredUsers: {},
        percent: {},
        result: {},
        dashbored: [],
        predictions: {},
        comp: [],
        item: [],
        items: [],
        branch: [],
        report: {},
        reporttr: [],
        bank: [],
        order:[],
        expense:[],
        ordergroup:[],
        totalcash:[]
    },
    mutations: {
        set(state, [variable, value]) {
            state[variable] = value
        },
    },
    modules: {},
    actions: {
        Register({ commit }, data) {
            return new Promise((resolve, reject) => {
                Admin.registerUser(data).then(response => {
                    const user_data = response.data
                    commit('registered', { user_data })
                    resolve(response)
                }).catch((err) => {
                    commit('auth_error')
                    reject(err.response.data)
                })
            })
        },
        AdminController({ commit }, { data, method, api, variable, params = false }) {
            return new Promise((resolve, reject) => {
                Admin.Apicontroller(data, method, api, params).then(response => {
                    commit('set', [variable, response.data])
                    resolve(response.data)
                }).catch((err) => {
                    reject(err.response.data)
                })
            })
        },
    },
    getters: {
        Getorder:state=>state.order,
        Getcategory: state => state.category,
        GetGame: state => state.games,
        Getusers: state => state.users,
        GetFiltered: state => state.filteredUsers,
        GetPercent: state => state.percent,
        GetResult: state => state.result,
        GetDashbored: state => state.dashbored,
        Getcomp: state => state.comp,
        Getitem: state => state.item,
        Getitems: state => state.items,
        GetBranch: state => state.branch,
        GetReport: state => state.report,
        GetReporttr: state => state.reporttr,
        Getbank: state => state.bank,
        Getexpense:state=>state.expense,
        Getordergroup:state=>state.ordergroup,
        Gettotalcash:state=>state.totalcash
        
    }
})