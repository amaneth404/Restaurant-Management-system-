<template>
   <div>
    <b-row no-gutters style="display: flex;align-items: center;justify-content: center;">
      <b-col md="11" style="margin-top:5px;">
          <div v-if="false">
              <b-input-group style="margin-bottom:50px;">
                    <template v-slot:prepend>
                      <i class="fas fa-search" style="color:black;margin-top:6px;background:grey;height:35px;margin-top:-3px;padding:10px;border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;"></i>
                    </template>
                                <input
                                @keyup="FilterOrder"
                                v-model="form.search"
                                  class="form-control search-controller"
                                  name="search"
                                  placeholder="search any order ..."
                                  style="box-shadow:none;background-color:rgb(255,255,255);height:35px;margin-top:-3px;color:black;"
                                >
                 </b-input-group>
                <!-- <input type="text" class="form-control" style="border-radius:10px;border:1px solid transparent;padding:5px;margin-bottom:50px;" placeholder="Search ..." v-model="form.username" @keyup="FilterUser"> -->
              </div>
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
                >All Flagged Orders</strong
              >
        
            </div>
             </div>
          <div class="panel-body table-responsive">
            <datatable
            :columns="columns"
            :data="orders"
           class="table text-nowrap table-striped"
            style="font-size:1em;color:black;"
          >
            <template slot-scope="{row,index}">
              <tr >
                <td style="vertical-align: middle">{{ index + 1 }}</td>
                <td style="vertical-align: middle">{{ row.menu!=null?row.menu.name:row.orders }}</td>
                <td style="vertical-align: middle"><span v-if="row.waiter">{{ row.waiter.first_name+row.waiter.middle_name+row.waiter.last_name }}</span></td>
                <td style="vertical-align: middle">{{ row.total }}</td>
                <td style="vertical-align: middle">{{ row.price }}</td>
                <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
               
              </tr>
            </template>
          </datatable>
          <div style="float:right;">
             <pagination :data="orders" @pagination-change-page="getmyorder">
                </pagination>
          </div>
          </div>
        </div>
        </b-card>
      </b-col>
    </b-row>
    <b-modal :id="'post-update-modal-order'" :size="'lg'">
          <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">View Order <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-order')"></i></span></div>
        <div style="background-color: black;">
          <b-card style="color:black;border: 0;border-radius: 0;">
            <div style="display: flex;justify-content: space-between;">
              <div>
            <img src="shege showcase5.png" alt="" style="height: 100px;width:100px;">
            <!-- <div class="logo-dis"></div> -->
            <h5>Shege Burger</h5>
          </div>
          <div>
            <h3>Date   :{{ order_holder.created_at|myDate }}</h3>
            <h3>Order   :{{ order_holder.index_holder }}</h3>
            <h3  v-if="order_holder.waiter">{{order_holder.waiter?'Waiter':'Delivery'}} :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder.waiter.first_name+' '+order_holder.waiter.middle_name+' '+order_holder.waiter.last_name }}</span> </h3>
            <h3  v-if="order_holder.isdelivery">Phone Number :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder.phone_number }}</span> </h3>
            <h3 v-if="order_holder.waiter">Table number : <span class="badge badge-primary" style="font-size: 1.7em;font-size: bolder;">{{ order_holder.table_name }}</span> </h3>
          </div>
          </div>
             
              <table class="table table-sm table-striped" style="width:100% !important;">
  <thead style="background-color: transparent !important;">
    <tr>
      <th scope="col" style="text-align: left !important;width: 10% !important;">#</th>
      <th scope="col" style="text-align: left !important;width: 30% !important;">Order</th>
      <th scope="col" style="text-align: left !important;width: 10% !important;">Amount</th>
      <th scope="col" style="text-align: left !important;width: 10% !important;" class="last">Price</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="ord,index in order_holder.order" :key="index" style="width:100%;">
      <td scope="row">{{ index+1 }}</td>
      <td>{{ord.menu_id?ord.menu.name:ord.order}}</td>
      <td>{{ord.amount}}</td>
      <td class="last">{{ ord.price*ord.amount }}</td>
    </tr>
  </tbody>
</table>
              <div style="padding:30px;">
            <!-- <h5 style="text-align:left;">SubTotal <span style="float:right;">{{ Number(order_holder.price/1.15).toFixed(2) }}</span></h5> -->
            <!-- <h5 style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;">Tax <span style="float:right;">{{Number(order_holder.price-(order_holder.price/1.15)).toFixed(2) }}</span></h5> -->
            <h3 v-if="order_holder.isdelivery" style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;"><strong>Delivery Fee</strong> <span style="float:right;">{{ order_holder.fee }}</span></h3>
            <h3 v-if="order_holder.isdelivery" style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;"><strong>Box</strong> <span style="float:right;">{{ order_holder.box }}</span></h3>
            <h3 style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;"><strong>Total</strong> <span style="float:right;">{{ order_holder.price }}</span></h3>
          </div>
            </b-card>
            <div v-if="order_holder.state==1">
            <b-button v-if="order_holder.isflaged==0"  style="width:100%;text-align:left;margin-top:10px;background:rgb(197, 4, 4);border:0;border-radius:10px;padding:15px;" @click="payed(order_holder.id)">Paid <span style="float:right;"><i class="fas fa-arrow-right"></i></span></b-button>
         
          </div>
        </div>
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
        { label: "Order", field: "order" },
        { label: "Waiter", field: "waiter" },
        { label: "Amount", field: "amount" },
        { label: "Price", field: "price" },
         { label: "Created At", field: "created_at" },
      ],
      orders: {data:[]},
      order_holder:{},
      display: false,
      url: "",
      title:'',
      fee:'',
      code:'',
      selected_titles:[],
      isedit:-1,
      order: new window.Form({
        title: "",
        attachment:"",
        id: "",
      }),
    };
  },
  beforeMount() {
    this.getmyorder()
  },
  methods: {
    attachmentprocessor (e) {
      var photos = e.target.files[0]
      var file = photos
      var limit = 1024 * 1024 * 2
      if (file.size <= limit) {
        this.order.attachment = file
        this.url = URL.createObjectURL(file)
      }
    },
    FilterOrder(){
       this.$store.dispatch('admin/AdminController',{'data':{search:this.form.search},'method':'post','api':'admin_search_order','variable':'order'}, { root: true }).then(() => {
        this.orders=this.$store.getters['admin/Getorder']
      })
    },
    closemoadl(val){
      this.$bvModal.hide(val)
    },
    resetform() {
      this.order.reset();
      this.url = "";
      this.display=false
      this.updateindex=-1
    },
    getimage(image){
      return window.image_url+image
    },
    ViewOrder(order) {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_view_order','variable':'order','params':order.id}, { root: true }).then(() => {
        this.order_holder=this.$store.getters['admin/Getorder']
      })
    },
    getmyorder(page=1) {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_flaged_order','variable':'order','params':'?page='+page}, { root: true }).then(() => {
        this.orders=this.$store.getters['admin/Getorder']
      })
    },
    Flag(id,index) {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to Flag this order',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_flag_order','variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'order Flaged successfuly',
          });
        this.orders.data.splice(index,1);
      }).catch(()=>{
         window.Toast.fire({
            icon: "error",
            title:"order can't be Flaged !!",
          });
      })
            }
     });
    },
    UnFlag(id,index) {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to unFlag this order',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_un_flag_order','variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'order unFlag successfuly',
          });
          this.orders.data.splice(index,1);
      }).catch(()=>{
         window.Toast.fire({
            icon: "error",
            title:"order can't be unFlag !!",
          });
      })
            }
     });
    },
    deleteorder(id,index) {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to delete this order',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'delete','api':'admin_delete_order','variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'order deleted successfuly',
          });
        this.orders.data.splice(index,1)
      }).catch(()=>{
         window.Toast.fire({
            icon: "error",
            title:"order can't be deleted !!",
          });
      })
            }
     });
    },
    update() {
     var form =window.VFToFD(this.order);
      console.log(form)

      this.$store
        .dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_update_order','variable':'order'}, { root: true })
        .then(() => {
          var data = this.$store.getters["admin/Getorder"];
          this.orders.data[this.updateindex].id=data.id
          this.orders.data[this.updateindex].title=data.title
          this.orders.data[this.updateindex].attachment=data.attachment
          this.orders.data[this.updateindex].created_at=data.created_at
          this.display = false;
          this.$bvModal.hide("post-update-modal-order");
           window.Toast.fire({
            icon: "success",
            title:'order updated successfuly',
          });
        })
        .catch((err) => {
          this.order.errors.errors = err;
        });
    },
    create() {
      var form =window.VFToFD(this.order);
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_create_order','variable':'order'}, { root: true }).then(() => {
          this.orders.data.unshift(this.$store.getters["admin/Getorder"]);
          this.display = false;
          this.$bvModal.hide("post-update-modal-order");
          window.Toast.fire({
            icon: "success",
            title:'order created successfuly',
          });
        })
        .catch((err) => {
          this.order.errors.errors = err;
        });
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
    background-color:white;
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
.logo-dis{
  background: url('./../../../../assets/logo.png');
  height: 100px;
  width:100px;
  background-size: cover;
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