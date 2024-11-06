<template>
  <div>
   <b-row no-gutters style="display: flex;align-items: center;justify-content: center;">
     <b-col md="11" style="margin-top:5px;">
       
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
               >All Orders</strong
             >
             <div >
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
                                  placeholder="search any order by table  ..."
                                  style="box-shadow:none;background-color:rgb(255,255,255);height:35px;margin-top:-3px;color:white;"
                                >
                 </b-input-group>
                <!-- <input type="text" class="form-control" style="border-radius:10px;border:1px solid transparent;padding:5px;margin-bottom:50px;" placeholder="Search ..." v-model="form.username" @keyup="FilterUser"> -->
              </div>
             <select name="" id="" @change="changeOrder" style="background: transparent;">
             <option value="w">All Orders</option>
             <option value="wd">Waiting Delivery</option>
             <option value="dd">Delivered Delivery</option>
             <option value="f">Void Orders</option>
             <option value="p">Paid Orders</option>
             </select>
           </div>
            </div>
            
            <div class="panel-body table-responsive">
             <div style='padding:20px;padding-top:0;'>
         <datatable
           :columns="columns"
           :data="orders.data"
          class="table text-nowrap table-striped"
           style="font-size:1em;color:black;"
         >
           <template slot-scope="{row,index}">
             <tr :style="row.isflaged==1?'background:tomato !important;color:black;':'color:black;'">
               <td style="vertical-align: middle">{{ index + 1 }}</td>
               <td style="vertical-align: middle">{{ row.phone_number }}</td>
               <td style="vertical-align: middle"><span v-if="row.waiter">{{ row.waiter.first_name+row.waiter.middle_name+row.waiter.last_name }}</span></td>
               <td style="vertical-align: middle"><span class="badge badge-pill btn-primary"   v-if='row.isdelivery==1'>Delivery :{{ row.fee }}</span><span class="badge badge-pill badge-danger" v-if='row.isdelivery!=1'>Regular</span></td>
               <td style="vertical-align: middle">{{ row.order_price*1+(row.fee?row.fee:0)*1 }}</td>
               <td style="vertical-align: middle">{{ row.table_name }}</td>

               <td >
                 <b-dropdown>
                   <template v-slot:button-content >
                     <i class="fas fa-ellipsis-v" style="color:grey;"></i>
                            </template>
                   <b-dropdown-item>
                 <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-order'" @click='ViewOrder(row,index)'><i class="fa fa-eye" style="color:grey;margin-right:10px;"></i>View</span>
                   </b-dropdown-item>
                   <b-dropdown-item>
                    <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-order-config'" @click='changeconfigEdit(row,index)'><i class="fa fa-edit" style="color:grey;margin-right:10px;"></i>Change</span>
                      </b-dropdown-item>
                   <b-dropdown-item v-if="row.isflaged!=1&&row.state==1">
                 <span type='button'   style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='addOrder(row,index)'><i class="fa fa-eye" style="color:grey;margin-right:10px;"></i>Add Order</span>
                   </b-dropdown-item>
                   <b-dropdown-item v-if="row.isflaged==0&&row.state==1">
                 <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='Flag(row.folder_id,index)'><i class="fas fa-flag" style="color:grey;margin-right:10px;"></i> Flag</span>
                   </b-dropdown-item>
                   <!-- <b-dropdown-item v-if="row.isflaged==1&&row.state==1">
                 <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='UnFlag(row.folder_id,index)'><i class="fas fa-flag" style="color:red;margin-right:10px;"></i> UnFlag</span>
                   </b-dropdown-item> -->
                   <b-dropdown-item v-if="false">
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
         </div>
       </div>
       </b-card>
     </b-col>
   </b-row>
   <b-modal :id="'post-update-modal-order'" :size="'lg'">
         <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">View Order <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-order')"></i></span></div>
       <div style="background-color: black;" v-if="order_holder.length">
         <b-card style="color:black;border: 0;border-radius: 0;" id="print-card" >
           <div style="display: flex;justify-content: space-between;">
             <div>
           <img src="shege showcase2.png" alt="" style="height: 200px;width:200px;">
           <!-- <div class="logo-dis"></div> -->
           <h5> <span><i class="fas fa-print" style="float:right;margin-left:10px;font-size:2em;" @click="PrintData" v-if="printing==0&&order_holder[0].state==1"></i></span></h5>
         </div>
         <div>
           <h3 style="margin-bottom: 2px;" v-if='order_holder[0].created_at'>Date   :{{order_holder[0].created_at.split('T')[0]+'/'+order_holder[0].created_at.split('T')[1].split('.')[0]}}</h3>
           <h3 style="margin-bottom: 2px;">Order   :{{ order_holder[0].index_holder }}</h3>
           <h3 style="margin-bottom: 2px;"  v-if="order_holder[0].waiter">{{order_holder[0].waiter?'Waiter':'Delivery'}} :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder[0].waiter.first_name+' '+order_holder[0].waiter.middle_name+' '+order_holder[0].waiter.last_name }}</span> </h3>
           <h3 style="margin-bottom: 2px;"  v-if="order_holder[0].isdelivery">Phone Number :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder[0].phone_number }}</span> </h3>
           <h3 style="margin-bottom: 2px;" v-if="!order_holder[0].isdelivery">Table number : <span class="badge badge-primary" style="font-size: 1.7em;font-size: bolder;">{{ order_holder[0].table_name }}</span> </h3>
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
   <tr v-for="ord,index in order_holder" :key="index" style="width:100%;">
     <td scope="row">{{ index+1 }}</td>
     <td>{{ord.menu?ord.menu.name:ord.orders}}</td>
     <td>{{ord.total}}</td>
     <td class="last">{{ ord.order_price }}</td>
   </tr>
 </tbody>
</table>
             <div style="padding:30px;">
           <!-- <h5 style="text-align:left;">SubTotal <span style="float:right;">{{ Number(order_holder.price/1.15).toFixed(2) }}</span></h5> -->
           <!-- <h5 style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;">Tax <span style="float:right;">{{Number(order_holder.price-(order_holder.price/1.15)).toFixed(2) }}</span></h5> -->
           <h3 v-if="order_holder[0].isdelivery" style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;"><strong>Delivery Fee</strong> <span style="float:right;">{{ getDeliveryFee(order_holder) }}</span></h3>
           <h3  v-if="order_holder[0].state==0&&order_holder[0].bank_money" style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;"><strong>Bank</strong> <span style="float:right;">{{ order_holder[0].bank_money}}</span></h3>
          
           <h3 style="text-align:left;border-top:1px solid rgba(0, 0, 0, 0.253);padding-top:10px;"><strong>Total</strong> <span style="float:right;">{{ active_order_holder.order_price*1+(active_order_holder.fee?active_order_holder.fee:0)*1}}</span></h3>
         </div>
         
         <div style="text-align:center;font-size:1.2em;margin-top:20px;" id="footer_display">
          Delivery PH : 0945488888 - 0954588888/ ACC No:1000292738618 
          </div>
         <div style="text-align:center;font-size:1em;margin-top:20px;" id="footer_display">
           Developed By AMA / +251943236237 
           </div>
          
           </b-card>
           <div  style="background-color: white;"> 
           
           <div  v-if="order_holder[0].state==1" class="col-md-6">
             <div  style="text-align:left;padding:20px;font-size:1.2em;color:black;">
             <input type="checkbox" @change="type_bank=!type_bank;type=0;" :checked="type_bank?true:false">  is Bank
           </div>
           </div>
           <div>
             </div>
           
             
         </div>
         <div v-if="type" style="padding:10px;width:100%;">
           <input style="margin-bottom:10px;" type="text" class="form-control" placeholder="full name" v-model="full_name" required> 
           <input type="text" class="form-control" placeholder="phone number" v-model="phone_number" required> 
         </div>
         <div v-if="type_bank" style="padding:10px;width:100%;background:white;">
           <select style="margin-bottom:10px;background:white;"  class="form-control"  v-model="bank" required>
             <option value="0">Select Bank</option>
             <option :value="bank.id" v-for="bank in banks" :key="bank.id" style="color:black;">{{bank.name}}</option>
           </select> 
           <input style="margin-bottom:10px;background:white;" placeholder="Money" class="form-control"  v-model="bank_money" required/>
         </div>
         <div>
       <b-button v-if="order_holder[0].isflaged==0&&order_holder[0].state==1"  style="width:100%;text-align:left;margin-top:10px;background:rgb(197, 4, 4);border:0;border-radius:0px;padding:15px;" @click="payed(order_holder[0].folder_id)">Paid <span style="float:right;"><i class="fas fa-arrow-right"></i></span></b-button>
     </div>
       </div>
   </b-modal>
   <b-modal :id="'post-update-modal-order-config'" :size="'lg'">
    <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">Change Order <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-order-config')"></i></span></div>
  <div style="background-color: black;" >
    <b-card style="color:black;border: 0;border-radius: 0;" id="print-card" >
      <form
      style="padding:30px;padding-top:15px;margin-bottom:35px;"
     @submit.prevent="changeconfig()"
   >
   <div style="text-align:left;padding:10px;font-size:1.2em;">
    <input type="checkbox" @change="configform.isdelivery=!configform.isdelivery" :checked="configform.isdelivery?true:false" class="input_holder">  <i class="fas fa-truck"></i> 
  </div>
  <div class="form-group row" >
    <select  required
    style="background-color:white !important;"
      v-model="configform.waiter"
      name="waiter"
      placeholder="Waiter"
      class="form-control"
      :class="{ 'is-invalid': configform.errors.has('waiter') }">
      <option value="">Select Waiter</option>
      <option :value="user.id" v-for="user in users" :key="user.id">{{ user.first_name+' '+user.middle_name+' '+user.last_name }}</option>
    </select>
    <has-error :form="configform" field="waiter"></has-error>
  </div>
     <div class="form-group row" v-if="!configform.isdelivery">
      <label>Table Name</label>
      <input
      required
        v-model="configform.table_name"
        name="table_name"
        placeholder="table_name"
        class="form-control"
        :class="{ 'is-invalid': configform.errors.has('table_name') }"
      />
      <has-error :form="configform" field="table_name"></has-error>
    </div>
    <div class="form-group row" v-if="configform.isdelivery">
      <label>Phone Number</label>
      <input
      required
        v-model="configform.phone_number"
        name="phone_number"
        placeholder="phone_number"
        class="form-control"
        :class="{ 'is-invalid': configform.errors.has('phone_number') }"
      />
      <has-error :form="configform" field="phone_number"></has-error>
    </div>
    <div class="form-group row" v-if="configform.isdelivery">
      <label>Fee</label>
      <input
      required
        v-model="configform.fee"
        name="fee"
        placeholder="fee"
        class="form-control"
        :class="{ 'is-invalid': configform.errors.has('fee') }"
      />
      <has-error :form="configform" field="fee"></has-error>
    </div>
     <div  style='border-top:1px solid rgba(0.5,0.5,0.5,0.3);padding-top:10px;margin-top:15px;'>
     <div class="float-right">
       <b-button type="submit" class="btn-primary" >
         Update
       </b-button>
     </div>
     </div>
   </form>
      </b-card>
    <div>
</div>
  </div>
</b-modal>
 </div>
</template>


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
select {
 height: 40px;
 border: 0.5px solid #ccc;
 border-radius: 5px;
 background-color:#000;
 box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
 font-size: 16px;
 color: #000;
}

select:hover {
 border-color: #000;
 box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

select option {
 padding: 10px;
 cursor: pointer;
}

select option:hover {
 background-color: #ccc;
} 
.logo-dis{
 background: url('/logo.png');
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
       { label: "Phone Number", field: "phone_number" },
       { label: "Waiter", field: "waiter.first_name" },
       { label: "isDelivery", field: "isdelivery" },
       { label: "Price", field: "price" },
       { label: "Table Name", field: "table_name" },
       { label: "Manage" },
     ],
     printing:0,
     orders: {data:[]},
     order_holder:{},
     display: false,
     active_order:'w',
     url: "",
     title:'',
     fee:'',
     code:'',
     active_index:-1,
     selected_titles:[],
     isedit:-1,
     full_name:'',
     phone_number:'',
     bank:0,
     bank_money:0,
     active_order_holder:{},
     banks:[],
     type:0,
     type_bank:0,
     configform:new window.Form({
      fee:0,
      isdelivery:0,
      phone_number:'',
      waiter:'',
      table_name:'',
      folder_id:'',
      old_isdelivery:0
     }),
     users:[],
     order: new window.Form({
       title: "",
       attachment:"",
       id: "",
     }),
   };
 },
 beforeMount() {
   this.getmyorder()
   this.getmybank()
   this.getAllUsers()
 },
 methods: {
  getAllUsers() {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_waiter_users','variable':'users'}, { root: true }).then(() => {
        this.users=this.$store.getters['admin/Getusers']
       
      })
    },
  getDeliveryFee(order){
    var fee=0;
    order.forEach(element => {
      fee+=element.fee*1
    });
    return fee
  },
   PrintData(){
     this.printing=1;
     var toprint=document.getElementById('print-card')
     var newwindow=window.open('','printwindow')
     newwindow.document.open()
     newwindow.document.write('<html><head><style>#footer_display{font-size: 1.7em !important;text-align: center !important;}img{height: 100px;width:100px;}h3{font-size: 2em !important;margin-bottom:0px !important;}table th{text-align: start !important;padding-left:50px !important;color:black !important;background: #e7c21e ;border-top: 1px solid black !important;border-left: 1px solid black !important;border-bottom: 1px solid black !important;font-size: 2em !important;}.last{  border-right: 1px solid black !important;}table td{    font-size: 2em !important;border-left: 1px solid black !important;    border-bottom: 1px solid black !important;}</style></head><body onload="window.print()" style="margin:0;">'+toprint.innerHTML+'</body></html>')
     newwindow.document.close()
     setTimeout(function(){newwindow.close();},500)
     this.printing=0

   },
   payed(id){
     if(!this.type_bank)
     this.bank=0
     window.Swal.fire({
           title: 'are you sure',
           text: 'this table paid',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Yes'
         }).then((result) => {
           if (result.value) {
             this.$store.dispatch('admin/AdminController',{'data':{bank:this.bank,bank_money:this.bank_money},'method':'post','api':'admin_payed_order','variable':'status','params':id}, { root: true }).then(() => {
       this.orders.data.splice(this.active_index,1)
       this.active_index=-1
       this.$bvModal.hide("post-update-modal-order");
       window.Toast.fire({
           icon: "success",
           title:'order Paid successfuly',
         });
     })
           }
    });
    
   },
   changeOrder(e){
     this.order_holder={}
     this.orders={data:[]}
     this.active_order=(e.target.value=='w'?0:(e.target.value=='f'?1:2))
     this.active_order=(e.target.value=='wd'?3:(e.target.value=='dd'?4:this.active_order))
     this.getmyorder()
   },
   changeconfig(){
    this.$store.dispatch('admin/AdminController',{'data':this.configform,'method':'post','api':'admin_change_config','variable':'order'}, { root: true }).then(() => {
       this.orders.data[this.active_index]=this.$store.getters['admin/Getorder']
       this.configform.reset()
       this.active_index=-1
       this.$bvModal.hide('post-update-modal-order-config')
       this.getmyorder()
     }).catch((err) => {
          this.configform.errors.errors = err;
        });
   },
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
   getmybank() {
     this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_bank','variable':'bank'}, { root: true }).then(() => {
       this.banks=this.$store.getters['admin/Getbank'].data
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
   changeconfigEdit(order,index){
    this.active_index=index
    this.configform.waiter=(order.waiter?order.waiter.id:'')
    this.configform.isdelivery=order.isdelivery
    this.configform.old_isdelivery=order.isdelivery
    this.configform.fee=order.fee
    this.configform.table_name=order.table_name
    this.configform.folder_id=order.folder_id
   },
   ViewOrder(order,index) {
    this.active_index=index
    this.active_order_holder=order
    this.bank=''
    this.bank_money=0
    this.type_bank=false
    console.log(this.active_index)
     this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_view_order','variable':'order','params':order.folder_id}, { root: true }).then(() => {
       this.order_holder=this.$store.getters['admin/Getorder']
       if(this.order_holder.length)
       this.order_holder[0].phone_number=order.phone_number
     })
   },
   getmyorder(page=1) {
     this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_order','variable':'order','params':this.active_order+'/order?page='+page}, { root: true }).then(() => {
       this.orders=this.$store.getters['admin/Getorder']
     })
   },
   addOrder(order){
     localStorage.setItem('order_id',order.folder_id)
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