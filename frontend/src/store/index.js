import Vue from 'vue'
import Vuex from 'vuex'
import admin from './admin'
import { UserAuth } from "./../api/api"
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        status: '',
        inventor_id: '',
        token: localStorage.getItem('token_store_token_identifyer') || '',
        config: localStorage.getItem('config') || '',
        username: localStorage.getItem('username') || '',
        user_id: localStorage.getItem('store_user_id') || '',
        licence: localStorage.getItem('licence') || '',
        pro_pic: localStorage.getItem('pro_pic'),
        role: localStorage.getItem('role') || '',
        lang: localStorage.getItem('lang') || 'en'
    },
    mutations: {
        set(state, [variable, value]) {
            state[variable] = value
        },
        setlang(state, data) {
            state.lang = data
        },
        auth_request(state) {
            state.status = 'loading'
        },
        auth_success(state, { token, username, id, role }) {
            state.status = 'success'
            state.token = token
            state.username = username
            state.user_id = id
            state.role = role
        },
        registered(state, inventor_id) {
            state.inventor_id = inventor_id
        },
        auth_error(state) {
            state.status = "error"
        },
        logout(state) {
            state.status = ''
            state.token = '',
                state.user = {},
                state.role = ''
        }
    },
    modules: {
        admin
    },
    actions: {
        updateConfig({ commit }, data) {
            return new Promise((resolve, reject) => {
                try {
                    localStorage.setItem('config', data.config)
                    commit("set", ['config', data.config])
                    resolve(data.config)
                } catch {
                    reject('error')
                }
            })
        },
        login({ commit }, user) {
            return new Promise((resolve, reject) => {
                commit("auth_request")
                UserAuth.loginUser(user).then((response) => {
                    const token = response.data.token
                    const user = response.data.user
                    localStorage.setItem("token_store_token_identifyer", token)
                    localStorage.setItem('username', user.username)
                    localStorage.setItem('store_user_id', user.id)
                    localStorage.setItem('role', user.role)
                    const username = user.username
                    const id = user.id
                    const role = user.role
                    window.axios.defaults.headers.common['Authorization'] = 'Bearer' + token
                    commit('auth_success', { token, username, id, role })
                    resolve(response)
                }).catch((err) => {
                    commit('auth_error')
                    localStorage.removeItem('token')
                    reject(err.response.data)
                })
            })
        },
        Adminlogin({ commit }, user) {
            return new Promise((resolve, reject) => {
                commit("auth_request")
                UserAuth.loginAdmin(user).then((response) => {
                    const token = response.data.token
                    const user = response.data.user
                    localStorage.setItem("token_store_token_identifyer", token)
                    localStorage.setItem('username', user.username)
                    localStorage.setItem('store_user_id', user.id)
                    localStorage.setItem('role', user.role)
                    const username = user.username
                    const id = user.id
                    const role = user.role
                    window.axios.defaults.headers.common['Authorization'] = 'Bearer' + token
                    commit('auth_success', { token, username, id, role })
                    resolve(response)
                }).catch((err) => {
                    commit('auth_error')
                    localStorage.removeItem('token')
                    reject(err.response.data)
                })
            })
        },
        Register({ commit }, user) {
            return new Promise((resolve, reject) => {
                commit("auth_request")
                UserAuth.registerUser(user).then(response => {
                    localStorage.setItem('config', 1)
                    commit("set", ['config', 1])
                    commit("set", ['status', 'success'])
                    resolve(response)
                }).catch((err) => {
                    commit('auth_error')
                        //localStorage.removeItem('token')
                    reject(err.response.data)
                })
            })
        },
        Confirm({ commit }, user) {
            return new Promise((resolve, reject) => {
                commit("auth_request")
                UserAuth.Confirm(user).then(response => {
                    const token = response.data.token
                    const role = response.data.user.role
                    const user = response.data.user
                    localStorage.setItem("token_store_token_identifyer", token)
                    localStorage.setItem('role', role)
                    localStorage.setItem('username', user.username)
                    localStorage.setItem('pro_pic', user.image)
                    const username = user.username
                    const pro_pic = user.image
                    window.axios.defaults.headers.common['Authorization'] = 'Bearer' + token
                    commit('auth_success', { token, role, username, pro_pic })
                    resolve(response)
                }).catch((err) => {
                    commit('auth_error')
                    localStorage.removeItem('token_store_token_identifyer')
                    reject(err.response.data)
                })
            })
        },
        Logout({ commit }) {
            return new Promise((resolve, reject) => {
                try {
                    commit('logout')
                    localStorage.removeItem('token_store_token_identifyer')
                    localStorage.removeItem('role')
                    localStorage.removeItem('username')
                    localStorage.removeItem('store_user_id')
                    localStorage.removeItem('name')
                    localStorage.removeItem('licence')
                    delete window.axios.defaults.headers.common['Authorization']
                    resolve('success')
                } catch (error) {
                    reject(error)
                }
            });
        },
        setLangugetransliation({ commit }, data) {
            localStorage.setItem("lang", data);
            commit('setlang', data)
        }
    },
    getters: {
        isLoggedIn: state => !!state.token,
        getUserID: state => state.user_id,
        isLicenced: state => !!state.licence,
        authStatus: state => state.status,
        user: state => state.username,
        languge: state => state.lang,
        role: state => state.role,
        config: state => state.config,
        GetUsername: state => state.username
    }

})