<template>
  <div class="login-container">
   <div class="cover-back">
            <div class="body-reg">
              <b-row no-gutters>
                <b-col md='6'  style="min-height:100vh;max-height:100%;padding-top:5%;" class="left-register">
                   <div style="display:flex;justify-content:center;align-item:center;">
    <b-card no-body class="login-card" style="border:0;">
      <form class="login-form" label-position="left" @submit.prevent="handleRegisteration">
      <div class="title-container">
        <h3 class="title" style="float:left;">Register</h3>
      </div>
      <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="first_name"
        class="form-control"
          v-model="registerForm.first_name"
          required
          placeholder="First Name"
          name="first_name"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
         <has-error :form="registerForm" field="first_name"></has-error>
      </div>
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="middle_name"
        class="form-control"
          v-model="registerForm.middle_name"
          required
          placeholder="Middle Name"
          name="middle_name"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
         <has-error :form="registerForm" field="middle_name"></has-error>
      </div>
      <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="last_name"
        class="form-control login-input"
          v-model="registerForm.last_name"
          required
          placeholder="Last Name"
          name="last_name"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
        <has-error :form="registerForm" field="last_name"></has-error>
        </div>
          <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="phone_number"
        class="form-control login-input"
          v-model="registerForm.phone_number"
          required
          placeholder="Phone Number"
          name="phone_number"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
        <has-error :form="registerForm" field="phone_number"></has-error>
        </div>
        <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="username"
        class="form-control login-input"
          v-model="registerForm.username"
          required
          placeholder="Username"
          name="username"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
        <has-error :form="registerForm" field="username"></has-error>
        </div>
           <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="title"
        class="form-control login-input"
          v-model="registerForm.title"
          required
          placeholder="Branch"
          name="title"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
        <has-error :form="registerForm" field="title"></has-error>
        </div>
         <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="location"
        class="form-control login-input"
          v-model="registerForm.location"
          required
          placeholder="Location"
          name="location"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
        <has-error :form="registerForm" field="location"></has-error>
        </div>
         <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="password"
        class="form-control login-input"
          v-model="registerForm.password"
          required
          placeholder="Password"
          name="password"
          type="password"
          tabindex="1"
          autocomplete="on"
        />
        <has-error :form="registerForm" field="password"></has-error>
        </div>
         <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="password"
        class="form-control login-input"
          v-model="registerForm.password_confirmation"
          required
          placeholder="password confirmation"
          name="password_confirmation"
          type="password"
          tabindex="1"
          autocomplete="on"
        />
        <has-error :form="registerForm" field="password_confirmation"></has-error>
        </div>
        <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="key"
        class="form-control login-input"
          v-model="registerForm.key"
          required
          placeholder="key"
          name="key"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
        <has-error :form="registerForm" field="key"></has-error>
        </div>
         <div class="terms">
          <input type="checkbox" name="agre" id="agre" required>
          <router-link to="/aman" style="font-size:0.9em;margin-left:10px;"> do you agree to oure service and terms</router-link>
        </div>
      <b-button :loading="loading" type="submit" class="login-button" style="width:100%;margin-bottom:30px;padding:10px;font-size:20px;">Sign Up</b-button>
    </form>
    </b-card>
    </div>
                </b-col>
                <b-col md='6'>
                  <div class="footer" style="color:white;">
                    <div style="float:right;margin-right:25%;margin-bottom:20px;"><h2>Amanuel</h2>
                    </div>
                  </div>
                </b-col>
              </b-row>
   
    </div>
      </div>
  </div>
</template>

<script>

export default {
  name: 'Login',
  data() {
    return {
      registerForm: new window.Form({
        first_name:'',
        middle_name:'',
        last_name:'',
        key:'',
        username:'',
        phone_number:'',
        password:'',
        password_confirmation:'',
        title:'',
        location:'',
      }),
      capsTooltip: false,
      loading: false,
      redirect: undefined,
    }
  },
  beforeMount(){
    // if(this.$store.getters.config!=0){
    //   this.TestConfig()
    //   if(this.$store.getters.config!=0){
    //   this.$router.push('/login')
    //   }
    // }
  },
  mounted() {
    
  },
  methods: {
      TestConfig() {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'test_config','variable':'config'}, { root: true }).then(() => {
      })
    },
    handleRegisteration() {
        var form = window.VFToF(this.registerForm)
     this.$store.dispatch('Register', { form }).then(() => {
         window.Toast.fire({
            icon: "success",
            title: 'User Registerd Successfuly',
          });
     this.$router.push('/login')
     }).catch(err => {
         this.registerForm.errors.errors=err
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

<style lang="scss">
$bg:#283443;
$light_gray:#fff;
$cursor: #fff;
.left-register{
 background: -webkit-gradient(linear, left top, right top, from(#145751), color-stop(99%, #35a196 )) !important;
        background: linear-gradient(to right, #145751, #35a196  99%) !important;
}
@supports (-webkit-mask: none) and (not (cater-color: $cursor)) {
  .login-container .el-input input {
    color: $cursor;
  }
}
.login-container {
  .el-input {
    display: inline-block;
    height: 47px;
    width: 85%;

    input {
      background: transparent;
      border: 0px;
      -webkit-appearance: none;
      border-radius: 0px;
      padding: 12px 5px 12px 15px;
      color: $light_gray;
      height: 47px;
      caret-color: $cursor;

      &:-webkit-autofill {
        box-shadow: 0 0 0px 1000px $bg inset !important;
        -webkit-text-fill-color: $cursor !important;
      }
    }
  }

  .el-form-item {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    color: #454545;
  }
}
</style>

<style lang="scss" scoped>
$bg:#2d3a4b;
$dark_gray:#889aa4;
$light_gray:#eee;
.footer{
  margin-top:43%;
}
.terms{
    font-size: 1.3em;
    font-family: 'Times New Roman', Times, serif;
    padding-bottom: 10px;
    float:left;
}
.cover-back{
 background-color: rgba(0,0,0,0.5);
    position:fixed;
    z-index: 1;
    left:0;
    right: 0;
    top:0;
    bottom:0;
}
.body-reg{
    height: 100%;
    overflow-y: auto;
    overflow-x:hidden;
}
.login-card{
  padding:0;
  background:transparent;
}
.login-input{
    border:0;
}
.login-container {
  height:100vh;
  width: 100%;
  background:black;
  background-size:contain;
  background-repeat: no-repeat;
  background-position: center;
  overflow: hidden;

  .login-form {
    position: relative;
    width: 520px;
    max-width: 100%;
    padding: 0px 35px 0;
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
    padding: 15px 5px 6px 0px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
    background:white;
    border-right:0px;
}
  .title-container {
    position: relative;

    .title {
      font-size: 36px;
      color: $light_gray;
      margin: 0px auto 40px auto;
      text-align: center;
      font-weight: bold;
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

  .thirdparty-button {
    position: absolute;
    right: 0;
    bottom: 6px;
  }

  @media only screen and (max-width: 470px) {
    .thirdparty-button {
      display: none;
    }
  }
}
</style>
