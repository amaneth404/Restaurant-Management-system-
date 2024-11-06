<template>
   <div>
    <b-row no-gutters style="display: flex;align-items: center;justify-content: center;">
      <b-col md="11" style="margin-top:30px;">
        <b-card
        no-body
          class="overflow-auto card"
          style="padding:0;"
        >
        <div class="panel">
          <div style="padding:20px;">
            <div style="display: flex; justify-content: space-between;margin-bottom:0px;padding:0px;">
              <strong
                class="float-left font-weight-black font-monospace"
                style="font-size: 1.3em;color:black;"
                >All Salaryes</strong
              >
            </div>
             </div>
          <div >
            <div class="panel-body table-responsive">
          <datatable
            :columns="columns"
            :data="salaryes.data"
            class="table text-nowrap table-striped"
            style="text-align:center;color:black;"
          >
            <template slot-scope="{row,index}">
              <tr>
                <td style="vertical-align: middle">{{ index + 1 }}</td>
                <td style="vertical-align: middle">{{ row.user.first_name+' '+row.user.middle_name+' '+row.user.last_name }}</td>
                <td style="vertical-align: middle">{{ row.user.username }}</td>
                <td style="vertical-align: middle">{{ row.full_name }}</td>
                <td style="vertical-align: middle">{{ row.phone_number }}</td>
                <td style="vertical-align: middle">{{ row.money }}</td>
                <td style="vertical-align: middle">{{ row.paid }}</td>
                <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
                <td>
                  <b-dropdown>
                    <template v-slot:button-content >
                      <i class="fas fa-ellipsis-v" style="color:grey;"></i>
                             </template>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-user'"  @click='Pay(row,index)'><i class="fas fa-edit" style="color:grey;margin-right:10px;"></i>Pay</span>
                    </b-dropdown-item>
                  </b-dropdown>
                </td>
              </tr>
            </template>
          </datatable>
        </div>
        </div>
          </div>
          <div style="float:right;">
            <pagination :data="salaryes" @pagination-change-page="getmyexpense">
               </pagination>
         </div>
        </b-card>
      </b-col>
    </b-row>
    <b-modal :id="'post-update-modal-user'" :size="'md'">
      <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">{{display?'Update User':'Add User'}} <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-user')"></i></span></div>
  
      <form class="login-form" label-position="left"  @submit.prevent="PayCredit()" style="padding:10px;">
    <div style="margin-bottom:20px;text-align:left;font-size:1.3em;">
      <input
      style="box-shadow:none;padding:25px;"
      ref="money"
      class="form-control"
        v-model="money"
        required
        placeholder="amount"
        name="money"
        type="text"
        tabindex="1"
        autocomplete="on"
      />
    </div>
    <b-button  type="primary" style="width:100%;margin-bottom:30px;padding:10px;font-size:20px;">Pay</b-button>
  </form>
  </b-modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      form:new window.Form({
      search:''
            }),
      updateindex:-1,
      columns: [
        { label: "#" },
        { label: "Name", field: "name" },
        { label: "Username", field: "username" },
        { label: "Full Name", field: "full_name" },
        { label: "Phone Number", field: "phone_number" },
        { label: "Credit", field: "money" },
        { label: "Paid", field: "paid" },
         { label: "Created At", field: "created_at" },
         { label: "Manage" }
      ],
      money:0,
      active:{},
      salaryes: {data:[]},
      display: false,
      url: "",
      title:'',
      fee:'',
      code:'',
      selected_titles:[],
      isedit:-1,
    
    };
  },
  beforeMount() {
    this.getmyexpense()
  },
  methods: {
    getmyexpense(page=1) {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_credit','variable':'expense','params':'?page='+page}, { root: true }).then(() => {
        this.salaryes=this.$store.getters['admin/Getexpense']
      })
    },
    Pay(row,index){
      this.active={row:row,index:index}
    },
    PayCredit(){
      if(this.active.row.money>=this.money&&this.money>0){
      this.$store.dispatch('admin/AdminController',{'data':{id:this.active.row.id,money:this.money},'method':'post','api':'admin_pay_credit','variable':'expense'}, { root: true }).then(() => {
        window.Toast.fire({
            icon: "success",
            title:'Credit Paid successfuly',
          });
          this.salaryes.data[this.active.index].money=this.active.row.money*1-this.money*1;
          this.salaryes.data[this.active.index].paid+=this.money*1;
          this.active={}
          this.money=0
          this.$bvModal.hide("post-update-modal-user");
      })
    }else{
      window.Toast.fire({
            icon: "error",
            title:`the amount must be between 0-${this.active.row.money}`,
          });
    }
    }
  },
};
</script>

<style scoped>
.search-controller{
  border-left:0;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
}
::placeholder {
color: black;
  font-style: italic;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-size: 1em;
}
.grade-icon{
  color:#fbcf00;
  font-size: 1.5em;
}

.close{
    color:blacksmoke;
    opacity: 1;
}

.card {
  position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color:black;
    background-clip: border-box;
    border: 0 solid #d9dee3;
    border-radius: 0.5rem;
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
  color:#000;
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
  color:#000;
  text-shadow: 3px 3px rgba(255,255,255,0.2);
}
.panel .panel-heading .form-control{
  color:#000;
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
  color:#000;
  background-color: rgba(255, 255, 255, 0.2);
  font-size: 16px;
  font-weight: 500;
  text-transform: uppercase;
  padding: 12px;
  border: none;
}
.panel .panel-body .table tbody tr td{
  color:#000;
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
  color:#000;
  font-size: 15px;
  position: relative;
  z-index: 1;
  transition: all 0.3s ease 0s;
}
.panel .panel-body .table tbody .action-list li a:hover{ text-shadow: 3px 3px 0 rgba(255,255,255,0.3); }
.panel .panel-body .table tbody .action-list li a:before,
.panel .panel-body .table tbody .action-list li a:after{
  content: attr(data-tip);
  color:#000;
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
  color:#000;
  background-color: transparent;
  padding: 15px;
  border: none;
}
.panel .panel-footer .col{ line-height: 35px; }
.pagination{ margin: 0; }
.pagination li a{
  color:#000;
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
  color:#000;
  background-color: transparent;
  border-color: rgba(255,255,255,0.2);
}
.pagination li a:focus,
.pagination li.active a,
.pagination li.active a:hover{
  color:#000;
  background-color: transparent;
  border-color:#000;
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