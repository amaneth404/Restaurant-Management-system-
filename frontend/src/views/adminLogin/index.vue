<template>
  <div class="login-container">
   <div class="cover-back">
            <div class="body-reg">
              <div style="display:flex;justify-content:center;align-item:center;">
    <b-card no-body class="login-card">
    <form class="login-form" label-position="left" @submit.prevent="handleLogin()">
      <div class="title-container">
        <h1 class="title"><b>Sign in <span class="disk-diplay-title">to Store</span></b></h1>
         </div>
  
      <div style="margin-bottom:20px;text-align:left;">
        
        <b-input-group prop="password" style="margin-bottom:20px;">
           <template v-slot:prepend>
              <span class="svg-container-end">
                <b-icon icon='envelope'></b-icon>
          </span>
          </template>
        <input
        style="box-shadow:none;padding:5%;font-size:100%;"
        class="form-control login-input"
         :class="{ 'is-invalid': loginForm.errors.has('username') }"
          v-model="loginForm.username"
          required
          ref="username"
          placeholder="Username"
          name="username"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
        </b-input-group>
         <div style="color:tomato;" v-if="loginForm.errors.has('username')">{{loginForm.errors.errors.username[0]}}</div>
      </div>

      
        <b-input-group prop="password" style="margin-bottom:20px;">
           <template v-slot:prepend>
              <span class="svg-container-end" @click="showPwd">
              <b-icon :icon="passwordType === 'password'?'lock':'eye'" aria-hidden="true"></b-icon>
          </span>
          </template>
          <input
          style="box-shadow:none;padding:5%;font-size:100%;"
          class="form-control login-input"
            :key="passwordType"
            ref="password"
            required
            v-model="loginForm.password"
            :type="passwordType"
            placeholder="Password"
            name="password"
            tabindex="2"
            autocomplete="on"
            @keyup="checkCapslock"
            @blur="capsTooltip = false"
          />
        </b-input-group>

      <b-button :loading="loading" type="submit"
                    class="login-button" >Login</b-button>
    </form>
    </b-card>
    </div>
    </div>
      </div>
  </div>
</template>

<script>

export default {
  name: 'Login',
  data() {
    return {
      loginForm: new window.Form({
        username: '',
        password: ''
      }),
      passwordType: 'password',
      capsTooltip: false,
      loading: false,
      redirect: undefined,
    }
  },
  mounted() {
    if (this.loginForm.username === '') {
      this.$refs.username.focus()
    } else if (this.loginForm.password === '') {
      this.$refs.password.focus()
    }
  },
  methods: {
    checkCapslock(e) {
      const { key } = e
      this.capsTooltip = key && key.length === 1 && (key >= 'A' && key <= 'Z')
    },
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = ''
      } else {
        this.passwordType = 'password'
      }
      this.$nextTick(() => {
        this.$refs.password.focus()
      })
    },
    handleLogin() {
         this.loading = true
         var form =window.VFToF(this.loginForm)
          this.$store.dispatch('Adminlogin', {form})
            .then(() => {
              this.$router.push('/')
              this.loading = false
            })
            .catch((err) => {
              this.loginForm.errors.errors=err
              this.loading = false
            })
    },
    getOtherQuery(query) {
      return Object.keys(query).reduce((acc, cur) => {
        if (cur !== 'redirect') {
          acc[cur] = query[cur]
        }
        return acc
      }, {})
    }
  }
}
</script>
<style lang="scss" scoped>
$bg:#2d3a4b;
$dark_gray:#889aa4;
$light_gray:#eee;
.cover-back{
    position:fixed;
    z-index: 1;
    left:0;
    right: 0;
    top:0;
    bottom:0;
}
.body-reg{
    padding-top:0px;
    height: 100%;
    overflow-y: auto;
    overflow-x:hidden;
}
.login-card{
  margin-top:5%;
  font-weight: bolder;
  border:0;
  padding:0;
  background:transparent;
}
.login-input{
    border:0;
    height: 60px;
}
.login-container {
  height:100vh;
  width: 100%;
  background: black;
  // background-image:url('../../assets/img/background.jpg');
  background-size:contain;
  background-repeat: no-repeat;
  background-position: center;
  overflow: hidden;

  .login-form {
    position: relative;
    max-width: 100%;
    padding: 70px 35px 0;
    margin: 0 auto;
    overflow: hidden;
  }

  .svg-container {
    padding: 15px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
    background:white;
    border-left:0;
  }
.svg-container-end{
    padding: 5% 5% 5% 10px;
    color: $dark_gray;
    vertical-align: middle;
    // width: 30px;
    display: inline-block;
    background:white;
    border-right:0px;
    font-size:2em;
}
  .title-container {
    position: relative;

    .title {
      color: $light_gray;
      margin: 0px auto 40px auto;
      text-align: center;
      font-weight: bold;
       text-align:left;
      font-size:300%;
      letter-spacing:10px;
      font-family:serif;
    }
  }

  .show-pwd {
    position: absolute;
    right: 10px;
    top: 7px;
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
    user-select: none;
  }
}
.login-button{
  margin-bottom:20px;
  padding:10px;
  font-size:1.6em;
  background:#0cbbb6;
  border:0;
  padding-left:30%;
  padding-right:30%;
  margin-top:20px;
}
/* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
.title{
  text-align:left;
  font-size:300% !important;
  margin: 0px auto 10px auto !important;
  letter-spacing:1px !important; 
}
.disk-diplay-title{
  visibility: hidden;
}
.svg-container-end{
    padding: 5% 5% 5% 5 10px !important;
    color: $dark_gray;
    vertical-align: middle;
    display: inline-block;
    border-right:0px;
    font-size:1.4em !important;
}
.login-button{
  margin-bottom:30px;
  padding:5px;
  font-size:1.2em;
  background:#0cbbb6;
  border:0;
  padding-left:30%;
  padding-right:30%;
  margin-top:50px;
}
.forget-button{
  text-align:center;
  margin-bottom:40px;
  color:white;
  font-size:1em;
}
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
.title{
  text-align:left;
  font-size:100%;
  letter-spacing:1px; 
}
.disk-diplay-title{
  visibility: hidden;
}
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
.title{
  text-align:left;
  font-size:300%;
  letter-spacing:10px; 
}
.disk-diplay-title{
  visibility: visible;
}
}

/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
.title{
  text-align:left;
  font-size:300%;
  letter-spacing:10px; 
}
.disk-diplay-title{
  visibility: visible;
}
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
.title{
  text-align:left;
  font-size:300%;
  letter-spacing:10px; 
}
.disk-diplay-title{
  visibility: visible;
}
}
.forget-button{
  text-align:center;
  margin-bottom:5px;
  color:white;
  font-size:1.3em;
}
</style>
