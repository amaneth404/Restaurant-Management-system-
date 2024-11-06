const ApiList = require('./ApiList').apis
export const UserAuth = Object.assign({
    loginUser(data) {
        return window.axios.post("/login", data.form)
    },
    loginAdmin(data) {
        return window.axios.post("/login", data.form)
    },
    registerUser(data) {
        console.log(data)
        return window.axios.post("/register", data.form)
    },
    Confirm(data) {
        return window.axios.post("/confirmaccount", data.form)
    },
    logout() {
        return window.axios.get("/logout")
    },
});
export const Admin = Object.assign({
    loginUser(data) {
        return window.axios.post("/login", data)
    },
    registerUser(data) {
        return window.axios.post("/register", data)
    },
    Apicontroller(data, method, api, params) {
        return window.axios({
            method: method,
            url: !params ? ApiList[api] : ApiList[api] + params,
            data: data
        });
    }
});
export const User = Object.assign({
    loginUser(data) {
        return window.axios.post("/login", data)
    },
    registerUser(data) {
        return window.axios.post("/register", data)
    },
    Betcontroller(data) {
        return window.axios.post("/users/prediction/store", data)
    },
    Apicontroller(data, method, api, params) {
        return window.axios({
            method: method,
            url: !params ? ApiList[api] : ApiList[api] + params,
            data: data
        });
    }
});