<template>
  <div class="login-container">
   <div class="cover-back">
            <div class="body-reg">
              <div style="display:flex;justify-content:center;align-item:center;">
    <b-card no-body class="login-card">
    <form class="login-form" label-position="left" @submit.prevent="handleConfirmation">
      <div class="title-container">
        <h1 class="title" style="float:left;">Confirmation</h1>
      </div>
      <b-input-group style="margin-bottom:20px;">
          <template v-slot:prepend>
              <div class="svg-container">
             <i class="fa fa-key" aria-hidden="true"></i>
             </div>
          </template>
        <input
        ref="confirmation"
        style="box-shadow:none;padding:25px;"
        class="form-control login-input"
          v-model="confirmation.confirmation"
          required
          placeholder="Confirmation"
          name="confirmation"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
      </b-input-group>
       <div style="color:black;text-align:left;width:100%;">
     <h6>code have been sent to this phone number: <strong style="margin-left:10px;">+{{this.$route.query.phone_number}}</strong></h6>
     <h6>Code :{{this.$route.query.code}}</h6>
    </div>
      <b-button :loading="loading" type="primary" style="width:100%;margin-bottom:30px;padding:10px;font-size:20px;">Confirm</b-button>
     <div style="text-align:left;margin-bottom:40px;color:black;">Confirmation Code not Set ? <b-link to="/register">Resent Confirmation Code</b-link></div>
    </form>
    </b-card>
    </div>
    </div>
      </div>
  </div>
</template>

<script>

export default {
  name: 'confiramtion',
  props:{
    phone_number:String,
    code:String
  },
  data() {
    return {
    confirmation: new window.Form({
        confirmation: '',
        phone_number: ''
      }),
      capsTooltip: false,
      loading: false,
      redirect: undefined,
    }
  },
  beforMount(){
    this.confirmation.phone_number=this.phone_number
  },
  mounted() {
    if (this.confirmation.username === '') {
      this.$refs.confirmation.focus()
    }
  },
  methods: {
    handleConfirmation(){
      var form=[]
        form['phone_number']=this.$route.query.phone_number
        form['code']=this.$route.query.code
     this.$store.dispatch('Confirm', { form }).then(() => {
         window.Toast.fire({
            icon: "success",
            title: 'User Confirmed Successfuly',
          });
     this.$router.push('/login')
     })
    }
  }
}
</script>

<style lang="scss">
$bg:#283443;
$light_gray:#000;
$cursor: #000;

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
    padding-top:100px;
    height: 100%;
    overflow-y: auto;
    overflow-x:hidden;
}
.login-card{
  margin-top:5%;
  padding:0;
  background:rgba(0.5,0.5,0.5,0.8);
}
.login-input{
    border:0;
}
.login-container {
  height:100vh;
  width: 100%;
  background-image:url('../../assets/itali.png');
  background-size:cover;
  overflow: hidden;

  .login-form {
    position: relative;
    width: 520px;
    max-width: 100%;
    padding: 70px 35px 0;
    margin: 0 auto;
    overflow: hidden;
  }

  .svg-container {
    padding: 15px 5px 6px 12px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
    background:black;
    border-left:0;
  }
.svg-container-end{
    padding: 15px 5px 6px 0px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
    background:black;
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
