<template>
  <div>
    <b-row >
      <b-col md="11" style="margin-top:5px;margin-left:2%;">
      
        <b-card
          no-body
          class="overflow-auto card"
          style=" 
    background-clip: border-box;height:100%;border-radius:5px;border:0;"
        >
        <div class="panel">
            <div style="padding:20px;">
            <div style="display: flex; justify-content: space-between;margin-bottom:0px;padding:0px;padding-bottom:0px;">
              <strong
                class="float-left font-weight-black font-monospace"
                style="font-size: 1.3em;color:black;"
                >All Users</strong
              >
              <div>
                <b-input-group style="margin-bottom:0px;margin-top:5px;">
                      <template v-slot:prepend>
                        <i class="fas fa-search" style="color:white;margin-top:6px;margin-left:10px;background:grey;height:35px;margin-top:-3px;padding:10px;border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;"></i>
                      </template>
                                  <input
                                  @keyup="FilterUsers"
                                  v-model="search"
                                    class="form-control search-controller"
                                    name="search"
                                    placeholder="search any user.."
                                    style="box-shadow:none;background-color:white;height:35px;margin-top:-3px;color:black;"
                                  >
                   </b-input-group>
                  <!-- <input type="text" class="form-control" style="border-radius:10px;border:1px solid transparent;padding:5px;margin-bottom:50px;" placeholder="Search ..." v-model="form.username" @keyup="FilterUser"> -->
                </div>
                <b-button
                class="btn  bg-gradient-danger" 
                style="padding:0;"
                v-b-modal="'post-update-modal-user'"
                @click="resetform()"
                >
                <div class="top-1"></div>
                <span style="padding-left:10px;padding-right:10px;"><i class="fas fa-gamepad" style="margin-right: 10px"></i
                >Add User</span>
                <div class="top-2"></div>
                </b-button
              >
            </div>
             </div>
       <div class="panel-body table-responsive">
          <div style='padding:20px;padding-top:0;'>
          <datatable
            :columns="columns"
            :data="(search?filtered_users:users)"
            class="table text-nowrap table-striped"
            style="font-size:1em;color:black;"
          >
            <template slot-scope="{row,index}">
              <tr>
                <td style="vertical-align: middle">{{ index + 1 }}</td>
                <td style="vertical-align: middle">{{ row.first_name+' '+row.middle_name+' '+row.last_name }}</td>
                <td style="vertical-align: middle">{{ row.username }}</td>
                <td style="vertical-align: middle">{{ row.salary }}</td>
                <td style="vertical-align: middle">{{ row.credit }}</td>
                <td style="vertical-align: middle">{{ row.role }}</td>
                <td style="vertical-align: middle"><span class="badge badge-pill btn-primary"   v-if='row.state==1'>Enabled</span><span class="badge badge-pill badge-danger" v-if='row.state!=1'>Disabled</span></td>
                <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
                 <td>
                  <b-dropdown>
                    <template v-slot:button-content >
                      <i class="fas fa-ellipsis-v" style="color:grey;"></i>
                             </template>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-user'"  @click='EditUser(row,index)'><i class="fas fa-edit" style="color:grey;margin-right:10px;"></i>Edit</span>
                    </b-dropdown-item>
                     <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-view-modal-user'" @click="viewUser(row)"><i class="fas fa-eye" style="color:grey;margin-right:10px;"></i>View</span>
                    </b-dropdown-item>
                    <b-dropdown-item>
                      <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click="Payuser(row,index)"><i class="fas fa-eye" style="color:grey;margin-right:10px;"></i>Pay</span>
                        </b-dropdown-item>
                     <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='passwordchanger(row)' v-b-modal="'post-update-modal-password'"><i class="fas fa-lock" style="color:grey;margin-right:10px;"></i> Change Password</span>
                    </b-dropdown-item>
                     <b-dropdown-item v-if='row.state==1'>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='disable(row.id,index)'><i class="fas fa-user-alt-slash" style="color:grey;margin-right:10px;"></i> Disable</span>
                    </b-dropdown-item>
                     <b-dropdown-item v-if='row.state!=1'>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='enable(row.id,index)'><i class="fas fa-user-check" style="color:grey;margin-right:10px;"></i> Enable</span>
                    </b-dropdown-item>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='deleteitem(row.id,index)'><i class="fas fa-trash-alt" style="color:grey;margin-right:10px;"></i> Delete</span>
                    </b-dropdown-item>
                  </b-dropdown>
                </td>
              </tr>
            </template>
          </datatable>
           <div v-if='!form.username&&false'>
            <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item" :class="users.currentPage==1?'disabled':''" @click="users.currentPage==1?'':getAllUsers(users.currentPage-1)">
      <a class="page-link"  tabindex="-1" aria-disabled="true" >Previous</a>
    </li>
    <li class="page-item" v-for='i in users.lastPage' :key='i' :class="users.currentPage==i?'active':''" @click="getAllUsers(i)"><a class="page-link" >{{i}}</a></li>
    <li class="page-item" :class="users.currentPage==users.lastPage?'disabled':''" @click="users.currentPage==users.lastPage?'':getAllUsers(users.currentPage+1)">
      <a class="page-link">Next</a>
    </li>
  </ul>
</nav>
          </div>
          </div>
        </div>
        </div>
        </b-card>
      </b-col>
    </b-row>
    <b-modal :id="'post-update-modal-user'" :size="'md'">
        <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">{{display?'Update User':'Add User'}} <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-user')"></i></span></div>
    
        <form class="login-form" label-position="left"  @submit.prevent="display ? update() : create()" style="padding:10px;">
      <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <select   style="box-shadow:none;padding:12px;color:black;width:100%;border-radius: 5px;border:0;"
          v-model="registerForm.role"
          >
        <option value="">Select Role</option>
        <option value="Store">Store</option>
        <option value="Casher">Casher</option>
        <option value="Waiter">Waiter</option>
        <option value="Chef">Chef</option>
        <option value="Employee">Employee</option>
        </select>
         <has-error :form="registerForm" field="role"></has-error>
      </div> 
      <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
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
      <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
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
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
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
      <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
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
         <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="phone_number"
        class="form-control"
        :class="{ 'is-invalid': registerForm.errors.has('phone_number') }"
          v-model="registerForm.phone_number"
          required
          placeholder="Phone Number"
          name="phone_number"
          type="number"
          tabindex="1"
          autocomplete="on"
        />
         <has-error :form="registerForm" field="phone_number"></has-error>
      </div>
      <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
        <input
        style="box-shadow:none;padding:25px;"
        ref="salary"
        class="form-control"
        :class="{ 'is-invalid': registerForm.errors.has('salary') }"
          v-model="registerForm.salary"
          required
          placeholder="salary"
          name="salary"
          type="number"
          tabindex="1"
          autocomplete="on"
        />
         <has-error :form="registerForm" field="salary"></has-error>
      </div>
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='!display'>
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
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='!display'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="password_confirmation"
        class="form-control"
        :class="{ 'is-invalid': registerForm.errors.has('password_confirmation') }"
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
      <b-button  type="primary" style="width:100%;margin-bottom:30px;padding:10px;font-size:20px;">{{display?'Update':'Register'}}</b-button>
    </form>
    </b-modal>
     <b-modal :id="'post-update-modal-password'" :size="'md'">
        <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">Change Password <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-password')"></i></span></div>
     <form class="login-form" label-position="left"  @submit.prevent="changePassword" style="padding:10px;">
       <label for="">{{registerForm.first_name +' '+registerForm.middle_name+' '+registerForm.last_name}}</label>
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='!display'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="password"
        :class="{ 'is-invalid': registerForm.errors.has('password') }"
        class="form-control"
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
       <div style="margin-bottom:20px;text-align:left;font-size:1.3em;" v-if='!display'>
        <input
        style="box-shadow:none;padding:25px;"
        ref="password_confirmation"
        :class="{ 'is-invalid': registerForm.errors.has('password_confirmation') }"
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
      <b-button  type="primary" style="width:100%;margin-bottom:30px;padding:10px;font-size:20px;">Change Password</b-button>
    </form>
    </b-modal>
     <b-modal :id="'post-view-modal-user'" size='lg'>
           <template v-slot:modal-title>
        <div>User</div>
        </template>
        <div style="margin-left:-20px;margin-right:-20px; display: flex;align-items: center;justify-content: center;" v-if='!finished'>
          <div class="spinner-border" role="status">
  <span class="sr-only">Loading...</span>
</div>
        </div>
        <div style="padding:20px;">
          <div style="display:flex;align-items: center;justify-content: center;">
           <img src="./../../../../assets/svg/illustrations/verified-user.svg" alt="" style="width:80px;" v-if='!user.image'>
          <img :src="'http://127.0.0.1:3000/emp/api/v1/image/'+user.image" alt="" style="width:80px;height:80px;border-radius:50%;" v-if='user.image'>
          </div>
        <h4 style="text-align:center;">Profile</h4>
        <b-row v-if='user.id'>
          <b-col md='6'>
            <label for="">Full Name :</label>
            <input type="text" class="form-control" disabled :value="user.first_name+' '+user.middle_name+' '+user.last_name">
          </b-col>
           <b-col md='4' style="margin-top:10px;">
            <label for="">UserName :</label>
            <input type="text" class="form-control" :value="user.username" disabled>
          </b-col>
          <b-col md='4' style="margin-top:10px;">
            <label for="">Phone Number :</label>
            <input type="text" class="form-control" :value="user.phone_number" disabled>
          </b-col>
        </b-row>
        <div v-if="user.role=='Chef'" style="margin-top:20px;">
          <div v-for="remark,index in user.remark" :key="index" style="border-top: 2px  dashed white;padding:10px;">
            <div>
              <strong>Remark: {{remark.remark}}</strong>
            </div>
            <div>
              <strong>Order: {{remark.menu}}</strong>
            </div>
            <div>
              <strong>Time: {{remark.time}} M</strong>
            </div>
            <div>
              <strong>Date: {{remark.date}}</strong>
            </div>
          </div>
        </div>
        </div>
        </b-modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      updateindex:-1,
      form:new window.Form({
      username:'',
      state:0
      }),
      registerForm:new window.Form({
        id:'',
        username:'',
        first_name:'',
        middle_name:'',
        salary:0,
        role:'',
        title:'',
        location:'',
        last_name:'',
        phone_number:'',
        password:'',
        password_confirmation:''
      }),
      search:'',
      finished:false,
      isupdate:false,
      detaileUser:{},
      columns: [
        { label: "#" },
        {
          label: "Full Name",
          field: "name"
        },
        {
          label: "Username",
          field: "username"
        },
        {
          label: "Salary",
          field: "salary"
        },
        {
          label: "Credit",
          field: "credit"
        },
        {
          label: "role",
          field: "role"
        },
         {
          label: "state",
          field: "state"
        },
         { label: "Created At", field: "created_at" },
        { label: "Manage" },
      ],
      users:[],
      filtered_users:[],
      user:{branch:[]},
      selectedUser:{},
      display: false,
      percent:1,
      predictions:[],
      money:0,
      searchUsers:[],
    };
  },
  beforeMount() {
    window.Fire.$emit('search-user-data','req')
    this.getAllUsers()
  },
  methods: {
    passwordchanger(row){
      this.registerForm.fill(row)
      this.registerForm.password="";
      this.display=false
    },
    EditUser(data,index){
      this.display=true
      this.registerForm.reset();
      this.registerForm.fill(data)
      this.updateindex=index
    },
    resetform(){
      this.registerForm.reset();
      this.url = "";
      this.display=false
      this.updateindex=-1
    },
    closemoadl(val){
      this.$bvModal.hide(val)
    },
    viewUser(row) {
      this.user=row
      if(this.user.remark)
      this.user.remark=JSON.parse(this.user.remark)
    else
    this.user.remark=[]
      this.finished=true
    },
    FilterUsers(e){
      // var form = window.VFToF(this.form);
      this.$store.dispatch('admin/AdminController',{'data':{username:e.target.value},'method':'post','api':'admin_filter_user','variable':'filteredUsers'}, { root: true }).then((val) => {
        this.filtered_users=val
        })
    },
    ConfirmUser(row,index) {
       this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_confirm_requests','variable':'status','params':row.id}, { root: true }).then(() => {
         this.users.data.splice(index,1)
         window.Toast.fire({
            icon: "success",
            title:'user Confirmed successfuly',
          });
        })
    },
    Payuser(row,index) {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to Pay this user',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
       this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_pay_user','variable':'status','params':row.id}, { root: true }).then(() => {
        window.Toast.fire({
            icon: "success",
            title:'user Paid successfuly',
          });
          var left=this.users[index].salary-this.users[index].credit
         this.users[index].credit=(left<0?(left*-1):0)
        
        })
      }
     });
    },
    getAllUsers(page=1) {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_users','variable':'users','params':'?page='+page}, { root: true }).then(() => {
        this.users=this.$store.getters['admin/Getusers'].data
      })
    },
    deleteuser(id,index) {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to delete this user',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'delete','api':'admin_delete_user','variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'user deleted successfuly',
          });
        this.users.splice(index,1)
      })
            }
     });
    },
    disable(id,index) {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to disable this user',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_disable_user','variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'user disabled successfuly',
          });
           this.users[index].state=-1
      })
            }
     });
    },
      enable(id,index) {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to enable this user',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_enable_user','variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'user enabled successfuly',
          });
          this.users[index].state=1
      })
            }
     });
    },
       create() {
           var form =window.VFToF(this.registerForm);
      this.$store
        .dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_create_user','variable':'users'}, { root: true })
        .then(() => {
           var data = this.$store.getters["admin/Getusers"];
          this.users.unshift(data);
          this.display = false;
          this.$bvModal.hide("post-update-modal-user");
           window.Toast.fire({
            icon: "success",
            title:'user Registerd successfuly',
          });
        })
        .catch((err) => {
          this.registerForm.errors.errors = err;
        });
    },
     deleteitem(id,index) {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to delete this user',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'delete','api':'admin_delete_user','variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'item deleted successfuly',
          });
        this.users.splice(index,1)
      })
            }
     });
    },
    changePassword() {
           var form =window.VFToF(this.registerForm);
      this.$store
        .dispatch('admin/AdminController',{'data':{password:form.password,password_confirmation:form.password_confirmation},'method':'put','api':'admin_password_user','variable':'status','params':form.id}, { root: true })
        .then(() => {
          this.display = false;
          this.$bvModal.hide("post-update-modal-password");
           window.Toast.fire({
            icon: "success",
            title:'user Password Changed successfuly',
          });
        })
        .catch((err) => {
          this.registerForm.errors.errors = err;
        });
    },
    update(){
      var id=this.registerForm.id
      var form =window.VFToF(this.registerForm);
      this.$store
        .dispatch('admin/AdminController',{'data':form,'method':'put','api':'admin_update_user','variable':'users','params':id}, { root: true })
        .then(() => {
          var data = this.$store.getters["admin/Getusers"];
          this.users[this.updateindex].id=data.id
          this.users[this.updateindex].salary=data.salary
          this.users[this.updateindex].first_name=data.first_name
          this.users[this.updateindex].phone_number=data.phone_number
          this.users[this.updateindex].last_name=data.last_name
          this.users[this.updateindex].middle_name=data.middle_name
          this.users[this.updateindex].username=data.username
          this.users[this.updateindex].role=data.role
          this.users[this.updateindex].created_at=data.created_at
          this.display = false;
          this.$bvModal.hide("post-update-modal-user");
           window.Toast.fire({
            icon: "success",
            title:'user Updated successfuly',
          });
        })
        .catch((err) => {
          this.registerForm.errors.errors = err;
        });
    },
  },
};
</script>
<style scoped>
.search-controller::placeholder {
  color: silver;
  font-style: italic;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-size: 1em;
}
</style>
<style>
.search-controller{
  border-left: 0;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
}
.bg-gradient-danger{
  border-radius: 5px !important;
}
.bg-gradient-danger:hover{
  background:transparent !important;
  color:black;
}
.bg-gradient-danger:hover .top-1{
   position: relative;
  visibility:visible;
  top:0;
  width:100%;
  border:0;
  border-top:1.5px solid blue;
}
.bg-gradient-danger:hover .top-2{
 position: relative;
  visibility:visible;
  bottom:0;
  float:right;
  width:100%;
  border-bottom:1.5px solid blue;  
}
.top-1{
  position: relative;
  visibility:hidden;
  top:0;
  width:20%;
  border:0;
  z-index: 0;
  border-top:1px solid blue;
  transition: all 0.700s;
  padding-bottom: 10px;
}
.top-2{
  position: relative;
  z-index: 0;
  visibility:hidden;
  bottom:0;
  float:right;
  width:20%;
  border:0;
  border-bottom:1px solid blue;
  transition: all 0.700s;
  padding-top:10px;
}

.users{
  border-bottom:0.5px solid rgb(214,214,214);
  padding:5px;
}
.users:hover{
  background:#007bff ;
  color:black;
}
.user-results{
  position: relative;
  background: white;
  border:0.5px solid rgb(214,214,214);
  border-top:0;
  padding:10px;
  top:0;
}
.play-button{
    padding-top:250px;
    font-size: 3em;
}
.cast:hover{
    color:#fbcf00;
    border-bottom:0.2px solid white;
}
.summary{
    text-align:unset;
}
.cover-image{
    display: flex;
    justify-content:center;
    align-content: center;
    opacity: 0.7;
    margin:0;
    background-size: cover;
    background-repeat:no-repeat ;
}
.user-icon{
  color:#fbcf00;
  font-size: 1.5em;
}
.modal-body{
    padding:0;
}
.Triage-icon{
  color:#fbcf00;
  font-size: 1.5em;
}
.modal-content{
  /* background-color:#1C1B1B; */
  /* color:#313131; */
  margin-top:10%;
}


.panel{
  padding: 0;
  border-radius: 10px;
  border: none;
  box-shadow: 0 0 0 5px rgba(0,0,0,0.05),0 0 0 10px rgba(0,0,0,0.05);
}
.panel .panel-heading{
  padding: 20px 15px;
  border-radius: 10px 10px 0 0;
  margin: 0;
}
.panel .panel-heading .title{
  color: #000;
  font-size: 28px;
  font-weight: 500;
  text-transform: capitalize;
  line-height: 40px;
  margin: 0;
}
.panel .panel-heading .btn{
  color: rgba(255,255,255,0.5);
  background: transparent;
  font-size: 16px;
  text-transform: capitalize;
  border: 2px solid #000;
  border-radius: 50px;
  transition: all 0.3s ease 0s;
}
.panel .panel-heading .btn:hover{
  color: #000;
  text-shadow: 3px 3px rgba(255,255,255,0.2);
}
.panel .panel-heading .form-control{
  color: #000;
  background-color: transparent;
  width: 35%;
  height: 40px;
  border: 2px solid #000;
  border-radius: 20px;
  display: inline-block;
  transition: all 0.3s ease 0s;
}
.panel .panel-heading .form-control:focus{
  background-color: rgba(255,255,255,0.2);
  box-shadow: none;
  outline: none;
}
.panel .panel-heading .form-control::placeholder{
  color: rgba(255,255,255,0.5);
  font-size: 15px;
  font-weight: 500;
}
.panel .panel-body{ padding: 0; }
.panel .panel-body .table thead tr th{
  color: #000;
  background-color: rgba(255, 255, 255, 0.2);
  font-size: 16px;
  font-weight: 500;
  text-transform: uppercase;
  padding: 12px;
  border: none;
}
.panel .panel-body .table tbody tr td{
  color: #000;
  font-size: 15px;
  padding: 10px 12px;
  vertical-align: middle;
  border: none;
}
.panel .panel-body .table tbody tr:nth-child(even){ background-color: rgba(255,255,255,0.05); }
.panel .panel-body .table tbody .action-list{
  padding: 0;
  margin: 0;
  list-style: none;
}
.panel .panel-body .table tbody .action-list li{
  display: inline-block;
  margin: 0 5px;
}
.panel .panel-body .table tbody .action-list li a{
  color: #000;
  font-size: 15px;
  position: relative;
  z-index: 1;
  transition: all 0.3s ease 0s;
}
.panel .panel-body .table tbody .action-list li a:hover{ text-shadow: 3px 3px 0 rgba(255,255,255,0.3); }
.panel .panel-body .table tbody .action-list li a:before,
.panel .panel-body .table tbody .action-list li a:after{
  content: attr(data-tip);
  color: #000;
  background-color: #111;
  font-size: 12px;
  padding: 5px 7px;
  border-radius: 4px;
  text-transform: capitalize;
  display: none;
  transform: translateX(-50%);
  position: absolute;
  left: 50%;
  top: -32px;
  transition: all 0.3s ease 0s;
}
.panel .panel-body .table tbody .action-list li a:after{
  content: '';
  height: 15px;
  width: 15px;
  padding: 0;
  border-radius: 0;
  transform: translateX(-50%) rotate(45deg);
  top: -18px;
  z-index: -1;
}
.panel .panel-body .table tbody .action-list li a:hover:before,
.panel .panel-body .table tbody .action-list li a:hover:after{
  display: block;
}
.panel .panel-footer{
  color: #000;
  background-color: transparent;
  padding: 15px;
  border: none;
}
.panel .panel-footer .col{ line-height: 35px; }
.pagination{ margin: 0; }
.pagination li a{
  color: #000;
  background-color: transparent;
  border: 2px solid transparent;
  font-size: 18px;
  font-weight: 500;
  text-align: center;
  line-height: 31px;
  width: 35px;
  height: 35px;
  padding: 0;
  margin: 0 3px;
  border-radius: 50px;
  transition: all 0.3s ease 0s;
}
.pagination li a:hover{
  color: #000;
  background-color: transparent;
  border-color: rgba(255,255,255,0.2);
}
.pagination li a:focus,
.pagination li.active a,
.pagination li.active a:hover{
  color: #000;
  background-color: transparent;
  border-color: #000;
}
.pagination li:first-child a,
.pagination li:last-child a{
  border-radius: 50%;
}
@media only screen and (max-width:767px){
  .panel .panel-heading .title{
      text-align: center;
      margin: 0 0 10px;
  }
  .panel .panel-heading .btn_group{ text-align: center; }
}
</style>
