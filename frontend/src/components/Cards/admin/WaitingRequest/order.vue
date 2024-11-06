<template>
   <div>
    <b-row no-gutters style="display: flex;align-items: center;justify-content: center;">
      <b-col md="11" style="margin-top:30px;">
          <div v-if="false">
              <b-input-group style="margin-bottom:50px;">
                    <template v-slot:prepend>
                      <i class="fas fa-search" style="color:white;margin-top:6px;background:grey;height:35px;margin-top:-3px;padding:10px;border-top-left-radius: 10px;
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
          <div style="padding:20px;">
            <div style="display: flex; justify-content: space-between;margin-bottom:0px;padding:0px;">
              <strong
                class="float-left font-weight-black font-monospace"
                style="font-size: 1.3em;color:black;"
                >All Orders</strong
              >
              <b-button
                class="btn  bg-gradient-danger" 
                style="padding:0;"
                v-b-modal="'post-update-modal-order'"
                @click="resetform()"
                >
                <div class="top-1"></div>
                <span style="padding-left:10px;padding-right:10px;"><i class="fas fa-gamepad" style="margin-right: 10px"></i
                >Add Order</span>
                <div class="top-2"></div>
                </b-button
              >
            </div>
             </div>
          <div >
          <datatable
            :columns="columns"
            :data="orders.data"
            class="table text-nowrap table-striped"
            style="text-align:center;color:black;"
          >
            <template slot-scope="{row,index}">
              <tr :style="row.isflaged==1?'background:tomato !important;color:white;':''">
                <td style="vertical-align: middle">{{ index + 1 }}</td>
                <td style="vertical-align: middle">{{ row.table_name }}</td>
                <td style="vertical-align: middle">{{ row.price }}</td>
                <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
                <td style="vertical-align: middle">
                  <b-dropdown>
                    <template v-slot:button-content >
                      <i class="fas fa-ellipsis-v" style="color:grey;"></i>
                             </template>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-order'" @click='ViewOrder(row,index)'><i class="fa fa-eye" style="color:grey;margin-right:10px;"></i>View</span>
                    </b-dropdown-item>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='addOrder(row,index)'><i class="fa fa-eye" style="color:grey;margin-right:10px;"></i>Add Order</span>
                    </b-dropdown-item>
                    <b-dropdown-item v-if="row.isflaged==0">
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='Flag(row.id,index)'><i class="fas fa-flag" style="color:grey;margin-right:10px;"></i> Flag</span>
                    </b-dropdown-item>
                    <b-dropdown-item v-if="row.isflaged==1">
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='UnFlag(row.id,index)'><i class="fas fa-flag" style="color:red;margin-right:10px;"></i> UnFlag</span>
                    </b-dropdown-item>
                    <b-dropdown-item v-if="$store">
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='deleteorder(row.id,index)'><i class="fas fa-trash-alt" style="color:grey;margin-right:10px;"></i> Delete</span>
                    </b-dropdown-item>
                  </b-dropdown>
                </td>
              </tr>
            </template>
          </datatable>
          <div style="float:right;">
             <pagination :data="orders" @pagination-change-page="getmyorder">
                </pagination>
          </div>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-modal :id="'post-update-modal-order'" :size="'lg'">
          <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">View Order <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-order')"></i></span></div>
        <div>
          <b-card style="color:black;border: 0;border-radius: 0;">
              <h5>Table number: <span class="badge badge-primary">{{ order_holder.table_name }}</span> <span><i class="fas fa-print" style="float:right;"></i></span></h5>
              <table class="table table-sm table-striped">
  <thead style="background-color: transparent !important;">
    <tr>
      <th scope="col" style="text-align: left !important;">#</th>
      <th scope="col" style="text-align: left !important;">Order</th>
      <th scope="col" style="text-align: left !important;">Amount</th>
      <th scope="col" style="text-align: left !important;">Price</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="ord,index in order_holder.order" :key="index">
      <td scope="row">{{ index+1 }}</td>
      <td>{{ord.menu_id?ord.menu.name:ord.order}}</td>
      <td>{{ord.amount}}</td>
      <td>{{ ord.price*ord.amount }}</td>
    </tr>
  </tbody>
</table>
              <div style="margin-top:20px;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:20px;">
            <h5 style="text-align:left;">SubTotal <span style="float:right;">{{ Number(order_holder.price/1.15).toFixed(2) }}</span></h5>
            <h5 style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;">Tax <span style="float:right;">{{order_holder.price-Number(order_holder.price/1.15).toFixed(2) }}</span></h5>
            <h5 style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;"><strong>Total</strong> <span style="float:right;">{{ order_holder.price }}</span></h5>
          </div>
            </b-card>
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
        { label: "Table Name", field: "table_name" },
        { label: "Price", field: "price" },
         { label: "Created At", field: "created_at" },
        { label: "Manage" },
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
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_order','variable':'order','params':'?page='+page}, { root: true }).then(() => {
        this.orders=this.$store.getters['admin/Getorder']
      })
    },
    addOrder(order){
      localStorage.setItem('order_id',order.id)
        this.$router.push('/manage/order')
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
        this.orders.data[index].isflaged=1;
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
        this.orders.data[index].isflaged=0;
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
  color:white;
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

</style>