<template>
    <div>
        <b-row no-gutters style="display: flex;margin-left:1%;">
          <b-col md='7'>
          <b-row no-gutters>
            <b-col md='12'>
        <div class="header" style="border-radius:10px;border:0;">
          <h4 style="color:white;text-align:left;"><strong style="font-size:1em;font-weight: bolder;">Hello</strong>, {{$store.getters.role}}!</h4>
          <h5 style="color:white;text-align:left;" class="header-sub"><strong>Welcome back to Shege Burger</strong></h5>
        </div>
      </b-col>
               <b-col md='12' style="text-align:left;margin-top:20px;" >
                 <b-card style="background:rgb(255,255,255);color:white;margin-bottom:10px;padding:20px;">
                   <b-row>
                     <b-col md='12'>
                       <img src="./../../../../assets/img/Group 33.png" alt="" style=" height:100px;width:100px;border-radius:50%;margin-top:0px;float:left;" class="image-shadow">
                       <span style="float:left;margin-top:60px;margin-left:20px;font-size:1.5em;color:black;">
                         <span style="font-size:1.6em;"><b>{{$store.getters.user}}</b></span><br>
                         {{$store.getters.role}}
                       </span>
                     </b-col>
                   </b-row>
                   <div style="display: flex;margin-top:20px;overflow:auto;">
           <div style="margin-top:10px;background:#145751;color:white;border:0;margin-right:2%;width:30%;font-size:0.9em;" v-b-modal="'post-update-modal-user-profile'" class="btn btn-light" @click="UpdateType('u')">
             change Username
           </div><br>
            <div style="margin-top:10px;background:#348897;color:white;border:0;margin-right:2%;width:30%;font-size:0.9em;" v-b-modal="'post-update-modal-user-profile'" class="btn btn-light" @click="UpdateType('ph')">
             change Phone Number
           </div><br>
             <div style="margin-top:10px;background:#fda100;color:white;border:0;margin-right:2%;width:30%;font-size:0.9em;" v-b-modal="'post-update-modal-user-profile'" class="btn btn-light" @click="UpdateType('ps')">
             change Password
           </div><br>

             <div style="margin-top:10px;background:#8b2530;color:white;border:0;margin-right:2%;width:30%;font-size:0.9em;" v-b-modal="'post-update-modal-user-profile'" class="btn btn-light" @click="UpdateType('p')">
             Edit profile
           </div>
           </div>
           </b-card>
            </b-col>
            </b-row>
            </b-col>
            
             </b-row>
              <b-modal :id="'post-update-modal-user-profile'" :size="'md'">
        <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">{{display?'Update User':'Add User'}} <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-user-profile')"></i></span></div>
     <form class="login-form" label-position="left"  @submit.prevent="update()" style="padding:10px;">
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='update_type=="u"'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="username"
        class="form-control"
        :class="{ 'is-invalid': registerForm.errors.has('username') }"
          v-model="registerForm.username"
          required
          placeholder="username"
          name="username"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
         <has-error :form="registerForm" field="username"></has-error>
      </div>
      <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='update_type=="p"'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="first_name"
        class="form-control"
          v-model="registerForm.first_name"
          required
          placeholder="First Name"
        :class="{ 'is-invalid': registerForm.errors.has('first_name') }"
          name="first_name"
          type="text"
          tabindex="1"
          autocomplete="on"
        />
         <has-error :form="registerForm" field="first_name"></has-error>
      </div>
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='update_type=="p"'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="middle_name"
        class="form-control"
        :class="{ 'is-invalid': registerForm.errors.has('middle_name') }"
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
      <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='update_type=="p"'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="last_name"
        class="form-control login-input"
        :class="{ 'is-invalid': registerForm.errors.has('last_name') }"
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
         <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='update_type=="ph"'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="phone_number"
        class="form-control"
        :class="{ 'is-invalid': registerForm.errors.has('phone_number') }"
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
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='update_type=="ps"'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="password"
        class="form-control"
        :class="{ 'is-invalid': registerForm.errors.has('old_password') }"
          v-model="registerForm.old_password"
          required
          placeholder="old password"
          name="password"
          type="password"
          tabindex="1"
          autocomplete="on"
        />
         <has-error :form="registerForm" field="old_password"></has-error>
      </div>
         <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='update_type=="ps"||update_type=="ph"||update_type=="u"'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="password"
        class="form-control"
        :class="{ 'is-invalid': registerForm.errors.has('password') }"
          v-model="registerForm.password"
          required
          placeholder="password"
          name="password"
          type="password"
          tabindex="1"
          autocomplete="on"
        />
         <has-error :form="registerForm" field="password"></has-error>
      </div>
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='update_type=="ps"||update_type=="ph"||update_type=="u"'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="password_confirmation"
        class="form-control"
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
      <b-button  type="primary" style="width:100%;margin-bottom:30px;padding:10px;font-size:20px;">Update</b-button>
    </form>
    </b-modal>
    </div>
</template>
<script>
export default {
    data(){
        return {
          display:true,
              registerForm:new window.Form({
        id:'',
        username:'',
        first_name:'',
        middle_name:'',
        last_name:'',
        phone_number:'',
        old_password:'',
        password:'',
        password_confirmation:''
      }),
      update_type:'p',
      user:{},
      api:''
        }
    },
    beforeMount(){
      this.getUserProfile()
    },
    methods:{
      getUserProfile(){
         this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_my_profile','variable':'users'}, { root: true }).then(() => {
        this.user=this.$store.getters['admin/Getusers']
      })
      },
       closemoadl(val){
      this.$bvModal.hide(val)
    },
      update(){
         var form =window.VFToF(this.registerForm);
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':this.api,'variable':'status'}, { root: true }).then(() => {
          this.$bvModal.hide("post-update-modal-user-profile");
          this.getUserProfile()
          window.Toast.fire({
            icon: "success",
            title:'Profile Updated successfuly',
          });
        })
        .catch((err) => {
          this.registerForm.errors.errors = err;
        });
      },
      UpdateType(type){
        this.update_type=type
        if(type=='p'){
          this.api='admin_update_profile'
          this.registerForm.fill(this.user)
        }else{
          if(type=='ph'){
            this.api='admin_update_phone_number'
          }else if(type=='ps'){
            this.api='admin_update_password'
          }else if(type=='u'){
            this.api='admin_update_username'
          }
          this.registerForm.reset()
        }
      }
    }
}
</script>
<style scoped>
.image-shadow{
  border:2px solid orange;
  -webkit-box-shadow: -2px 10px 31px -18px rgba(0,0,0,0.75);
-moz-box-shadow: -2px 10px 31px -18px rgba(0,0,0,0.75);
box-shadow: -2px 10px 431px -18px rgba(0,0,0,0.75);
}
.main{
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.header-sub{
  padding-top:5px;
  font-family: sans-serif;
  font-style: italic;
  font-size: 1.3em;
  letter-spacing: 1px;
}
.header{
  width: 100%;
  padding:30px;
  background: -webkit-gradient(linear, left top, right top, from(#e59811), color-stop(150%, #976e27)) !important;
        background: linear-gradient(to right, #e59811, #976e27 70%) !important;
}
</style>