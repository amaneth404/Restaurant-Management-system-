<template>
  <div style="padding-left:1%;">
    <h4 style="text-align: left;"><strong>Manage Order</strong> </h4>
   <p style="text-align: left;">Manage Order</p>
   <b-row style="margin-top:40px;">
    <b-col md="3">
      <div style="margin-left:-13px;">
      <h5 style="text-align: left;color:#5c5c5c9f;font-size: 1em;;">choose</h5>
      <h5 style="text-align: left;"><strong>Category</strong></h5>
    </div>
    <b-row style="padding-left:0px;">
        <b-col md="12">
          <b-card no-body class="cate" style="border:0;height:60px;padding:5px;margin-bottom: 13px;" v-for="cate,index in categorys" :key="cate.id" @click="getMenu(cate,index)" :style="cate.id==active_cate?'background:#F8C655':''">
            <b-row no-gutters>
              <b-col md="auto" style="padding-left: 20px;margin-top:-5px;width:5%;">
                <i class="mdi mdi-food" style="font-size: 2em;padding-bottom:0px;" :style="cate.id==active_cate?'color:white':''" ></i>
              </b-col>
              <b-col md="auto" style="padding-top:3px;padding-left:5px;width:90%;">
                <div style="font-size: 1.1em; text-align: left;text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
  display: inline-block;width:50%;"><strong>{{cate.name}}</strong></div>
                <div style="text-align: left;padding-left: 10%;">{{cate.counter}}</div>
              </b-col>
            </b-row>
          </b-card>
        </b-col>
      </b-row>
    </b-col>
    <b-col md="5">
      <div>
              <b-input-group style="margin-bottom:50px;">
                    <template v-slot:prepend>
                      <i class="fas fa-search" style="color:black;margin-top:6px;background:#EEEEEE;height:35px;margin-top:-3px;padding:10px;border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;"></i>
                    </template>
                                <input
                                  class="form-control search-controller"
                                  v-model="search"
                                  @keyup="FilterMenu"
                                  name="search"
                                  placeholder="search any menu ..."
                                  style="box-shadow:none;background-color:#EEEEEE;height:35px;margin-top:-3px;color:black;border-left: 0;border-top-right-radius: 10px;border-bottom-right-radius: 10px;"
                                >
                 </b-input-group>
                <!-- <input type="text" class="form-control" style="border-radius:10px;border:1px solid transparent;padding:5px;margin-bottom:50px;" placeholder="Search ..." v-model="form.username" @keyup="FilterUser"> -->
              </div>
      <b-row>
        <b-col md="6" v-for='menu,index in (search?filtered_data:menus)' :key="menu.id" style="margin-bottom: 10px;" @click="SelectedMenu(menu,index)">
          <b-card no-body style="border:0;padding:5px;" class="menu" :style="selected_order.indexOf(menu.id)!=-1?'background:#F8C655':''">
            <h5 style="text-align: left;padding-left:10px;"><strong>{{ menu.name }} </strong> 
            
              <span v-if="menu.item" style="float:right;"><strong> {{ menu.item.amount-menu.item.sold }}</strong></span>
            </h5>
            <p style="text-align: left;padding-left:10px;">{{ menu.price }} birr</p>
          </b-card>
        </b-col>
      </b-row>
    </b-col>
    <b-col md="4">
      <b-row no-gutters>
        <b-col md="12">
          <b-card no-body style="padding:30px;border:0;background-color: #F7F7F9;border-radius: 10px;box-shadow: 1px 2px 5px grey;" >
            <h4 style="text-align: left;"  v-b-modal="'post-update-modal-order'"  v-if="order_data.length || id_update"><i class="fas fa-times" style="margin-left:10px;" @click="clearAllData()"></i></h4>
            <!-- <h4 style="text-align: left;"  v-b-modal="'post-update-modal-order'" v-if="order_holder.order.length || order_holder.store.length">View Old Order <i class="fas fa-eye"></i><i class="fas fa-times" style="margin-left:10px;" @click="order_holder={store:[]};table_num=''"></i></h4> -->
          
            <h4 style="text-align: left;"><strong>Other Foods</strong></h4>
          <p style="text-align: left;">Food Not in the menu</p>
          <form
        @submit.prevent="addNewOrder"
      >
      <b-row>
        <b-col md="8">
          <div class="form-group row">
          <input
          required
            v-model="category.name"
            name="name"
            placeholder="name"
            class="form-control"
            :class="{ 'is-invalid': category.errors.has('name') }"
          />
          <has-error :form="category" field="name"></has-error>
        </div>
        </b-col>
        <b-col md="4">
          <div class="form-group row">
          <input
          required
            v-model="category.price"
            name="price"
            type="number"
            placeholder="birr"
            class="form-control"
            :class="{ 'is-invalid': category.errors.has('price') }"
          />
          <has-error :form="category" field="price"></has-error>
        </div>
        </b-col>
        <b-col md="12">
          <div class="form-group row">
            <input
            style="margin-left:10px;margin-right:10px;"
              v-model="category.tokichen"
              name="tokichen"
              type="checkbox"
              :checked="this.category.tokichen?true:false"
              placeholder="tokichen"
            /> to kichen
            <has-error :form="category" field="tokichen"></has-error>
            </div>
        </b-col>
      </b-row>
          <b-button type="submit" class="btn btn-primary" style="margin-top:5px;margin-bottom:10px;width:100%;"> + ADD New Order</b-button>
      </form>
      
      <div style="display: flex;justify-content: space-between;" v-if="!id_update">
        <div style="text-align:left;padding:10px;font-size:1.2em;">
          <input type="checkbox" @change="SetCredit" :checked="credit?true:false" class="input_holder">  <i class="fas fa-credit-card"></i> 
        </div> <div style="text-align:left;padding:10px;font-size:1.2em;">
          <input type="checkbox" @change="Delivery" :checked="isdelivery?true:false" class="input_holder">  <i class="fas fa-truck"></i> 
        </div>
        <div style="text-align:left;padding:10px;font-size:1.2em;">
          <input type="checkbox" @change="Gift" :checked="is_gift?true:false" class="input_holder">  <i class="fas fa-gift"></i> 
        </div>
      </div>
      <div v-if="isdelivery">
        <div class="form-group row">
        <input
        required
          v-model="phone_number"
          name="phone_number"
          type="number"
          placeholder="Phone Number"
          class="form-control"
        />
      </div>
      <div class="form-group row">
        <input
        required
          v-model="fee"
          name="fee"
          type="number"
          placeholder="fee"
          class="form-control"
        />
      </div>
        <div class="form-group row" v-if="false">
        <input
        required
          v-model="box"
          name="box"
          type="number"
          placeholder="box fee"
          class="form-control"
        />
      </div>
      </div>
      <div class="form-group row"  v-if="credit">
        <select  required
        v-model="credit"
        name="credit"
        placeholder="credit"
        class="form-control"
        :class="{ 'is-invalid': category.errors.has('credit') }">
        <option value="">Select User</option>
        <option :value="user.id" v-for="user in allusers" :key="user.id">{{ user.first_name+' '+user.middle_name+' '+user.last_name }}</option>
      </select>
      </div>  
      <div class="form-group row"  v-if="!isdelivery">
        <input  required
          v-model="table_num"
          name="table_num"
          placeholder="Table Name"
          class="form-control"
          :class="{ 'is-invalid': category.errors.has('table_num') }">
        <has-error :form="category" field="table_num"></has-error>
      </div>  
      <div class="form-group row" v-if="$store.getters.role=='Admin'">
        <select  required
          v-model="casher"
          name="casher"
          placeholder="casher"
          class="form-control"
          :class="{ 'is-invalid': category.errors.has('casher') }">
          <option value="">Select Casher</option>
          <option :value="user.id" v-for="user in users_casher" :key="user.id">{{ user.first_name+' '+user.middle_name+' '+user.last_name }}</option>
        </select>
        <has-error :form="category" field="table_num"></has-error>
      </div>
        <div class="form-group row">
          <select  required
            v-model="waiter"
            name="waiter"
            placeholder="Waiter"
            class="form-control"
            :class="{ 'is-invalid': category.errors.has('waiter') }">
            <option value="">Select Waiter</option>
            <option :value="user.id" v-for="user in users" :key="user.id">{{ user.first_name+' '+user.middle_name+' '+user.last_name }}</option>
          </select>
          <has-error :form="category" field="table_num"></has-error>
        </div>
        <h4 style="text-align: left;"><strong>Order</strong></h4>
          <p style="text-align: left;">{{Date()|myDate }}</p>

          <b-row no-gutters>
            <b-col md="12">
              <b-card 
  v-for="(order, index) in order_data" 
  :key="index" 
  :style="order.menu.menu_id == -1 ? 'background:yellow' : ''"  
  no-body 
  style="margin-bottom:15px;padding:10px;padding-top:2px;padding-bottom:2px;background:rgba(202, 202, 202, 0.582);border:0;border-radius:0;">
  <b-row>
    <b-col md="6">
      <div 
        style="text-align:left;font-size:1.3em;text-overflow:ellipsis;white-space:nowrap;overflow:hidden;display:inline-block;width:70%;text-align:left;">
        {{ order.menu.name }}
      </div>
      <p style="text-align:left;margin-bottom:0;">
        {{ order.menu.price }} birr 
        <strong 
          v-if="order.menu.menu_id != -1" 
          @click="iscomment != order.menu.menu_id ? iscomment = order.menu.menu_id : iscomment = 0" 
          style="margin-left:10px;background-color:blue;color:white;font-size:0.8em;padding:3px;border-radius:5px;">
          <i class="fas fa-comment"></i>
        </strong>
      </p>
    </b-col>
    
    <b-col md="6" style="padding-top:10px;">
      <div style="float:right;">
        <i 
          class="fas fa-times" 
          style="color:red;cursor:pointer;" 
          @click="removeFromorder(index, order.menu.menu_id == -1)">
        </i>
      </div>
      <span style="margin-right:20px;font-size:1.6em;cursor:pointer;" @click="orderinc(index)">+</span>
      <span>{{ order.order }}</span>
      <span style="margin-left:20px;font-size:1.9em;cursor:pointer;" @click="orderdic(index)">-</span>
    </b-col>
  </b-row>
  
  <div v-show="iscomment == order.menu.menu_id && iscomment != -1">
    <textarea v-model="order.remark" name="" id="" class="form-control" style="margin-top:10px;"></textarea>
  </div>
</b-card>

            </b-col>
          </b-row>
          <div style="margin-top:20px;">
            <!-- <h5 style="text-align:left;">SubTotal <span style="float:right;">{{ total_cash }}</span></h5> -->
             <!-- <h5 style="text-align:left;">Tax <span style="float:right;">{{ Number(total_cash*0.15).toFixed(2) }}</span></h5> -->
            <h5 style="text-align:left;"><strong>Total</strong> <span style="float:right;">{{total_cash }}</span></h5>
            <!-- <b-button style="width:100%;text-align:left;margin-top:10px;background:rgb(197, 4, 10);border:0;border-radius:10px;padding:15px;" type="button" v-b-modal="'post-update-modal-order'">Print Order<span style="float:right;"><i class="fas fa-print"></i></span></b-button> -->
            <b-button style="width:100%;text-align:left;margin-top:10px;background:rgb(197, 4, 4);border:0;border-radius:10px;padding:15px;" @click="OrderFood" :disabled="isloading?true:false">{{isloading?'Loading..':'Continue To Payment'}}<span style="float:right;"><i class="fas fa-arrow-right"></i></span></b-button>
          </div>
          </b-card>
        </b-col>
      </b-row>
    </b-col>
   </b-row>
   <b-modal :id="'post-update-modal-order'" :size="'lg'">
    <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">View Order <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-order')"></i></span></div>
  <div id="print-card" style="margin:0;padding:0;width:100%;background-color: white;font-size:2em;" v-if="order_holder.order ||order_holder.store">
    <b-card style="color:black;border: 0;border-radius: 0;" v-if="order_holder.order.length">
 
      <div style="display: flex;justify-content: space-between;">
        <div>
          <img src="shege showcase2.png" alt="" style="height: 200px;width:200px;"> 
      <!-- <div class="logo-dis"></div> -->
      <h5> <span><i class="fas fa-print" style="float:right;margin-left:10px;font-size:2em;" @click="PrintData" v-if="printing==0"></i></span></h5>
      <h3 > Casher:<span style="font-size: 1.7em;font-size: bolder;">{{display_name}}</span></h3>
    </div>
    <div>
      <h3 v-if='order_holder.created_at'>Date   :{{order_holder.created_at.split('T')[0]+'/'+order_holder.created_at.split('T')[1].split('.')[0]}}</h3>
      <h3>Order   :{{ order_holder.index_holder }}</h3>
      <h3  v-if="order_holder.waiter">{{!order_holder.isdelivery?'Waiter':'Delivery'}} :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder.waiter.first_name+' '+order_holder.waiter.middle_name+' '+order_holder.waiter.last_name }}</span> </h3>
      <h3  v-if="order_holder.isdelivery">Phone Number :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder.phone_number }}</span> </h3>
      <h3 v-if="order_holder.table_name">Table number : <span class="badge badge-primary" style="font-size: 1.7em;font-size: bolder;">{{ order_holder.table_name }}</span> </h3>
    </div>
    </div>
        <table class="table table-sm table-striped" style="width:100% !important;">
<thead style="background-color: transparent !important;">
<tr>
<!-- <th scope="col" style="text-align: left !important;width: 10% !important;">#</th> -->
<th scope="col" style="text-align: left !important;width: 30% !important;">Order</th>
<th scope="col" style="text-align: left !important;width: 10% !important;" class="last">Amount</th>
</tr>
</thead>
<tbody>
<tr v-for="ord,index in order_holder.order" :key="index">
<!-- <td scope="row">{{ index+1 }}</td> -->
<td>{{ord.menu?ord.menu.name:ord.orders}}</td>
<td class="last">{{ord.order}}</td>
</tr>
</tbody>
</table>
<div style="text-align:center;font-size:1em;margin-top:20px;" id="footer_display">
Developed By AMA
</div>
<div style="text-align:center;font-size:1em;" id="footer_display">
+251943236237
</div>
      </b-card>
<div style="page-break-before: always !important;" v-if="order_holder.store.length"></div>
      <b-card style="color:black;border: 0;border-radius: 0;"   v-if="order_holder.store.length">
<div style="display: flex;justify-content: space-between;">
        <div>
          <img src="shege showcase2.png" alt="" style="height: 200px;width:200px;">
          <h5 v-if="!order_holder.order.length"> <span><i class="fas fa-print" style="float:right;margin-left:10px;font-size:2em;" @click="PrintData" v-if="printing==0"></i></span></h5>
    </div>
    <div>
      <h3 class="casher_name" v-if="!order_holder.store.length"> Casher:<span style="font-size: 1.7em;font-size: bolder;">{{display_name}}</span></h3>
      <h3 v-if='order_holder.created_at'>Date   :{{order_holder.created_at.split('T')[0]+'/'+order_holder.created_at.split('T')[1].split('.')[0]}}</h3>
      <h3>Order   :{{ order_holder.index_holder }}</h3>
      <h3  v-if="order_holder.waiter">{{!order_holder.isdelivery?'Waiter':'Delivery'}} :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder.waiter.first_name+' '+order_holder.waiter.middle_name+' '+order_holder.waiter.last_name }}</span> </h3>
      <h3  v-if="order_holder.isdelivery">Phone Number :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder.phone_number }}</span> </h3>
      <h3 v-if="order_holder.table_name">Table number : <span class="badge badge-primary" style="font-size: 1.7em;font-size: bolder;">{{ order_holder.table_name }}</span> </h3>
    </div>
    </div>
<table class="table table-sm table-striped" >
<thead style="background-color: transparent !important;">
<tr>
<!-- <th scope="col" style="text-align: left !important;width: 10% !important;">#</th> -->
<th scope="col" style="text-align: left !important;width: 30% !important;">Order</th>
<th scope="col" style="text-align: left !important;width: 10% !important;" class="last">Amount</th>
</tr>
</thead>
<tbody>
<tr v-for="ord,index in order_holder.store" :key="index">
<!-- <td scope="row">{{ index+1 }}</td> -->
<td>{{ord.menu?ord.menu.name:ord.orders}}</td>
<td class="last">{{ord.order}}</td>
</tr>
</tbody>
</table>
<div style="text-align:center;font-size:1em;margin-top:20px;" id="footer_display">
Developed By AMA
</div>
<div style="text-align:center;font-size:1em;" id="footer_display">
+251943236237
</div>
      </b-card>


<div style="page-break-before: always !important;" v-if="order_holder.other.length"></div>
      <b-card style="color:black;border: 0;border-radius: 0;"   v-if="order_holder.other.length">
<div style="display: flex;justify-content: space-between;">
        <div>
          <img src="shege showcase2.png" alt="" style="height: 200px;width:200px;">
          <h5 v-if="!order_holder.order.length &&!order_holder.store.length"> <span><i class="fas fa-print" style="float:right;margin-left:10px;font-size:2em;" @click="PrintData" v-if="printing==0"></i></span></h5>
    </div>
    <div>
      <h3 class="casher_name" v-if="!order_holder.other.length"> Casher:<span style="font-size: 1.7em;font-size: bolder;">{{display_name}}</span></h3>
      <h3 v-if='order_holder.created_at'>Date   :{{order_holder.created_at.split('T')[0]+'/'+order_holder.created_at.split('T')[1].split('.')[0]}}</h3>
      <h3>Order   :{{ order_holder.index_holder }}</h3>
      <h3  v-if="order_holder.waiter">{{!order_holder.isdelivery?'Waiter':'Delivery'}} :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder.waiter.first_name+' '+order_holder.waiter.middle_name+' '+order_holder.waiter.last_name }}</span> </h3>
      <h3  v-if="order_holder.isdelivery">Phone Number :<span style="font-size: 1.7em;font-size: bolder;">{{ order_holder.phone_number }}</span> </h3>
      <h3 v-if="order_holder.table_name">Table number : <span class="badge badge-primary" style="font-size: 1.7em;font-size: bolder;">{{ order_holder.table_name }}</span> </h3>
    </div>
    </div>
<table class="table table-sm table-striped" >
<thead style="background-color: transparent !important;">
<tr>
<!-- <th scope="col" style="text-align: left !important;width: 10% !important;">#</th> -->
<th scope="col" style="text-align: left !important;width: 30% !important;">Order</th>
<th scope="col" style="text-align: left !important;width: 10% !important;" class="last">Amount</th>
</tr>
</thead>
<tbody>
<tr v-for="ord,index in order_holder.other" :key="index">
<!-- <td scope="row">{{ index+1 }}</td> -->
<td>{{ord.menu?ord.menu.name:ord.orders}}</td>
<td class="last">{{ord.order}}</td>
</tr>
</tbody>
</table>
<div style="text-align:center;font-size:1em;margin-top:20px;" id="footer_display">
Developed By AMA
</div>
<div style="text-align:center;font-size:1em;" id="footer_display">
+251943236237
</div>
      </b-card>
  </div>
</b-modal>
  
  
  
  
  
    <b-modal :id="'post-update-modal-bank'" :size="'lg'">
      <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">View Order <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-bank')"></i></span></div>
    <b-card style="background-color:white;">
      <form @submit.prevent="bankSubmit">
      <div class="form-group row">
        <select  required
          v-model="bank.user_id"
          name="waiter"
          placeholder="Waiter"
          class="form-control">
          <option value="">Select Waiter</option>
          <option :value="user.id" v-for="user in users" :key="user.id">{{ user.first_name+' '+user.middle_name+' '+user.last_name }}</option>
        </select>
        <has-error :form="category" field="table_num"></has-error>
      </div>
      <div  class="form-group row">
        <select   class="form-control"  v-model="bank.bank_id" required>
          <option value="0">Select Bank</option>
          <option :value="bank.id" v-for="bank in banks" :key="bank.id">{{bank.name}}</option>
        </select>  
      </div>
      <div class="form-group row">
        <input  type="number" class="form-control" placeholder="amount" v-model="bank.money" required>
      </div>
      <div>
    </div>
    <button type="submit" class="btn btn-danger" >Submit</button>
  </form>
</b-card>
</b-modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      search:'',
      iscomment:0,
      filtered_data:[],
      form:new window.Form({
      search:''
            }),
      updateindex:-1,
      columns: [
        // { label: "#" },
        { label: "Category", field: "title" },
        { label: "Image", field: "image" },
         { label: "Created At", field: "created_at" },
        { label: "Manage" },
      ],
      isloading:0,
      waiter:'',
      casher:'',
      money:0,
      phone_number:'',
      fee:'',
      printing:0,
      table_num:'',
      box:'',
      credit:false,
      is_gift:false,
      isdelivery:false,
      active_cate:-1,
      active_menu:-1,
      active_index:-1,
      active_menu_index:-1,
      categorys: [],
      menus:[],
      order_holder:{store:[],order:[],other:[]},
      selected_order:[],
      order_data:[],
      display: false,
      display_menu:false,
      isupdate:false,
      total_cash:0,
      url: "",
      title:'',
      code:'',
      selected_titles:[],
      isedit:-1,
      category: new window.Form({
        table_num:"",
        credit:'',
        name: "",
        icon:"",
        tokichen:1,
        price:0,
        id: "",
      }),
      users:[],
      id_update:'',
      banks:[],
      users_casher:[],
      display_name:'',
      allusers:[],
      menu: new window.Form({
        name: "",
        icon:"",
        from_data:1,
        cate_id:"",
        price:0,
        id: "",
      }),
      bank: new window.Form({
        user_id: "",
        bank_id:0,
        money:0
      }),
    };
  },
  beforeMount() {
    this.getmycategory()
    this.getAllUsers()
    this.getUsers()
    this.getmybank()
  }, 
  mounted(){
   var id=localStorage.getItem('order_id')
   this.id_update=id
   if(id){
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_view_order','variable':'order','params':id}, { root: true }).then(() => {
        this.order_holder=this.$store.getters['admin/Getorder']
        if(this.order_holder.length){
        this.order_holder['store']=[]
        this.order_holder['other']=[]
        this.isupdate=true
        this.total_cash=Number(this.order_holder[0].total_price).toFixed(2);
        this.table_num=this.order_holder[0].table_name
        this.phone_number=this.order_holder[0].phone_number
        this.fee=this.order_holder[0].fee
        this.box=this.order_holder[0].box
        this.isdelivery=this.order_holder[0].isdelivery
        this.waiter=(this.order_holder[0].waiter?this.order_holder[0].waiter.id:'')
        this.order_holder['order']=this.order_holder
       localStorage.removeItem('order_id')
        }
      })
    }
  },
  methods: {
    getmybank() {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_bank','variable':'bank'}, { root: true }).then(() => {
        this.banks=this.$store.getters['admin/Getbank'].data
      })
    },
    PrintData(){
      this.printing=1;
      var toprint=document.getElementById('print-card')
      var newwindow=window.open('','printwindow')
      newwindow.document.open()
      newwindow.document.write('<html><head><style>.casher_name{font-size: 2em !important;}#footer_display{font-size: 1.5em !important;text-align: center !important;}img{height: 100px;width:100px;}h3{font-size: 2em !important;margin-bottom:0px !important;}table th{text-align: start !important;padding-left:50px !important;color:black !important;background: #e7c21e ;border-top: 1px solid black !important;border-left: 1px solid black !important;border-bottom: 1px solid black !important;font-size: 2em !important;}.last{  border-right: 1px solid black !important;}table td{    font-size: 2em !important;border-left: 1px solid black !important;    border-bottom: 1px solid black !important;}</style></head><body onload="window.print()" style="margin:0;">'+toprint.innerHTML+'</body></html>')
     newwindow.document.close()
      setTimeout(function(){newwindow.close();},500)
      this.printing=0
    },
    Delivery(){
      this.credit=false
      this.is_gift=false
      this.isdelivery=!this.isdelivery
      if(this.isdelivery){
        this.waiter=-1
        this.table_num=-1
      }
      this.waiter=''
      this.table_num=''
    },
    SetCredit(){
      if(this.credit!=true){
      this.credit=true
      this.is_gift=false
      this.isdelivery=false
      }else{
        this.credit=false
      }
    },
    Gift(){
      if(this.is_gift!=true){
      this.credit=false
      this.is_gift=true
      this.isdelivery=false
      }else{
        this.is_gift=false
      }
    },
    getAllUsers() {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_waiter_users','variable':'users'}, { root: true }).then(() => {
        this.users=this.$store.getters['admin/Getusers']
       
      })
    },
    getUsers() {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_all_users','variable':'users'}, { root: true }).then(() => {
        this.allusers=this.$store.getters['admin/Getusers'].data
        this.allusers.forEach(element => {
          if(element.role=='Casher')
          this.users_casher.push(element)
        });
      })
    },
    FilterMenu(e){
      var data=e.target.value
      if(data!=''){
      this.filtered_data=[]
      this.menus.forEach(element => {
        if(element.name.toLowerCase().startsWith(data.toLowerCase())){
          this.filtered_data.push(element)
        }
      });
    }else{
      this.filtered_data=this.menus
    }
    },
    clearAllData(){
      this.isloading=0
      this.id_update=''
          this.order_data=[]
          this.selected_order=[]
          this.table_num=''
          this.isdelivery=false
          this.fee=0
          this.waiter=''
          this.phone_number=''
          this.total_cash=0
    },
    bankSubmit(){
      
        this.isloading=1
          this.$store.dispatch('admin/AdminController',{'data':this.bank,'method':'post','api':'admin_update_bank_user','variable':'order'}, { root: true }).then(() => {
            window.Toast.fire({
            icon: "success",
            title:'registered successful !!',
          });
       this.isloading=0
       this.$bvModal.hide("post-update-modal-bank");
       
          this.bank.reset()

      }).catch(() => {
        this.isloading=0
        window.Toast.fire({
            icon: "error",
            title:'No Orders Found By this Waiter',
          });
        });
        
      
      
    },
    OrderFood(){
      if(this.table_num!=''||this.isdelivery){
        if(this.waiter!=''||this.isdelivery){
          if((this.isdelivery&&this.phone_number!=''&&this.fee!='')||!this.isdelivery){
        if(this.order_data.length>0||this.order_holder.id){
          var data={}
         
          data={order:this.order_data,table_name:this.table_num,casher:this.casher,id:this.id_update,waiter:this.waiter,phone_number:this.phone_number,box:this.box,fee:this.fee,isdelivery:(this.isdelivery==true?1:0),credit:(this.credit?this.credit:0),is_gift:(this.is_gift?1:0)}
          var api='admin_order'
          if(this.id_update)
          api='admin_update_order'
        this.isloading=1
          this.$store.dispatch('admin/AdminController',{'data':data,'method':'post','api':api,'variable':'order'}, { root: true }).then(() => {
            this.category.reset();
            window.Toast.fire({
            icon: "success",
            title:'your order is successful !!',
          });
          const checkholder=document.querySelectorAll('.input_holder')
        checkholder.forEach(element => {
          element.checked=false
        });
        this.is_gift=false
        this.credit=false
        this.id_update=''
        if(!this.casher)
        this.casher=this.$store.getters.getUserID
        this.users_casher.forEach(element => {
          if(element.id==this.casher){
            this.display_name=element.first_name+' '+element.middle_name+' '+element.last_name
          }
        });
        this.casher=''
          this.isloading=0
          this.casher=0
          this.$bvModal.show("post-update-modal-order");
          this.order_data=[]
          this.selected_order=[]
          this.table_num=''
          this.isdelivery=false
          this.fee=0
          this.waiter=''
          this.phone_number=''
          this.total_cash=0
          this.isloading=0
          this.id_update=''
          this.order_data=[]
          this.selected_order=[]
          this.table_num=''
          this.isdelivery=false
          this.fee=0
          this.waiter=''
          this.phone_number=''
          this.total_cash=0
          this.casher=''
          this.order_holder=this.$store.getters['admin/Getorder']
          this.getMenu({id:this.active_cate},this.active_index)


      }).catch((err) => {
        this.isloading=0
          this.category.errors.errors = err;
        });
        }else{
          window.Toast.fire({
            icon: "error",
            title:'you have to add one or more orders to submit !!',
          });
        }
      }else{
        window.Toast.fire({
            icon: "error",
            title:'you have to add Order Phone Number and delivery fee first !!',
          });
      }
      }else{
        window.Toast.fire({
            icon: "error",
            title:'you have to select Waiter first !!',
          });
      }
      }else{
        window.Toast.fire({
            icon: "error",
            title:'you have to select Table Number first !!',
          });
      }
      
    },
    orderinc(index){
      this.order_data[index].order++;
      this.total_cash=this.total_cash*1+this.order_data[index].menu.price*1
    },
    orderdic(index){
      if(this.order_data[index].order>1){
        this.order_data[index].order--;
      this.total_cash=this.total_cash*1-this.order_data[index].menu.price*1
      }
    }, 
    removeFromorder(index,type){
      this.total_cash=this.total_cash*1-this.order_data[index].menu.price*this.order_data[index].order
      this.order_data.splice(index,1)
      if(!type){
        this.selected_order.splice(index,1)
      }
    },
    addNewOrder(){
      this.order_data.push({'menu':{name:this.category.name,'price':this.category.price,'menu_id':-1,'tokichen':this.category.tokichen},'order':1,remark:''})
      this.total_cash=this.total_cash*1+ this.category.price*1
      this.category.reset()
    },
    getMenu(cate,index){
      this.active_cate=cate.id
      this.display=true
      this.active_index=index
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_menu','variable':'category','params':cate.id}, { root: true }).then(() => {
        this.menus=this.$store.getters['admin/Getcategory']
      })
    },
    SelectedMenu(menu){
      if(this.selected_order.indexOf(menu.id)!=-1){
      this.total_cash=this.total_cash*1-menu.price*this.order_data[this.selected_order.indexOf(menu.id)].order
      this.order_data.splice(this.selected_order.indexOf(menu.id),1)
      this.selected_order.splice(this.selected_order.indexOf(menu.id),1)
      }else if(this.selected_order.indexOf(menu.id)==-1){
      this.selected_order.push(menu.id)
        this.order_data.push({'menu':{name:menu.name,'price':menu.price,'menu_id':menu.id,'item_id':menu.item_id,'tokichen':menu.tokichen,'from_data':menu.from_data},'order':1,remark:''})
      this.total_cash=this.total_cash*1+menu.price
      }
    },
    refreshmenu(){
      this.menu.reset();
      this.menu.from_data=1
      this.display_menu=false
      this.active_menu=-1
      this.active_menu_index=-1
    },
    refreshCategory(){
      this.category.reset();
      this.display=false
    },
    attachmentprocessor (e) {
      var photos = e.target.files[0]
      var file = photos
      var limit = 1024 * 1024 * 2
      if (file.size <= limit) {
        this.category.attachment = file
        this.url = URL.createObjectURL(file)
      }
    },
    FilterCategory(){
       this.$store.dispatch('admin/AdminController',{'data':{search:this.form.search},'method':'post','api':'admin_search_menu_category','variable':'category'}, { root: true }).then(() => {
        this.categorys=this.$store.getters['admin/Getcategory']
      })
    },
    closemoadl(val){
      this.$bvModal.hide(val)
    },
    resetform() {
      this.category.reset();
      this.url = "";
      this.display=false
      this.updateindex=-1
    },
    getimage(image){
      return window.image_url+image
    },
    Editcategory(category,index) {
      this.category.fill(category);
      this.url=window.image_url+category.attachment
      this.category.attachment=''
      this.display = true;
      this.updateindex=index
    },
    getmycategory() {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_menu_category','variable':'category'}, { root: true }).then(() => {
        this.categorys=this.$store.getters['admin/Getcategory']
        this.getMenu(this.categorys[0],0)
      })
    },
    deletecategory() {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to delete this category',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'delete','api':'admin_delete_menu_category','variable':'status','params':this.active_cate}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'category deleted successfuly',
          });
        this.categorys.splice(this.active_index,1)
      }).catch(()=>{
         window.Toast.fire({
            icon: "error",
            title:"category can't be deleted !!",
          });
      })
            }
     });
    },
    deletemenu() {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to delete this menu',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'delete','api':'admin_delete_menu','variable':'status','params':this.active_menu}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'Menu deleted successfuly',
          });
        this.menus.splice(this.active_menu_index,1)
        this.categorys[this.active_index].counter-=1;
        this.refreshmenu()
      }).catch(()=>{
         window.Toast.fire({
            icon: "error",
            title:"Menu can't be deleted !!",
          });
      })
            }
     });
    },
    updateMenu() {
      this.menu.cate_id=this.active_cate
     var form =window.VFToFD(this.menu);
      this.$store
        .dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_update_menu','variable':'category'}, { root: true })
        .then(() => {
          var data = this.$store.getters["admin/Getcategory"];
          this.menus[this.active_menu_index].id=data.id
          this.menus[this.active_menu_index].name=data.name
          this.menus[this.active_menu_index].icon=data.icon
          this.menus[this.active_menu_index].from_data=data.from_data
          this.menus[this.active_menu_index].item_id=data.item_id
          this.menus[this.active_menu_index].price=data.price
          this.menus[this.active_menu_index].created_at=data.created_at
          this.display = false;
          this.menu.reset();
          this.menu.from_data=1
          this.$bvModal.hide("post-update-modal-category");
           window.Toast.fire({
            icon: "success",
            title:'Menu updated successfuly',
          });
        })
        .catch((err) => {
          this.menu.errors.errors = err;
        });
    },
    update() {
     var form =window.VFToFD(this.category);
      this.$store
        .dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_update_menu_category','variable':'category'}, { root: true })
        .then(() => {
          var data = this.$store.getters["admin/Getcategory"];
          this.categorys[this.active_index].id=data.id
          this.categorys[this.active_index].name=data.name
          this.categorys[this.active_index].icon=data.icon
          this.categorys[this.active_index].from=data.from
          this.categorys[this.active_index].created_at=data.created_at
          this.display = false;
          this.$bvModal.hide("post-update-modal-category");
           window.Toast.fire({
            icon: "success",
            title:'category updated successfuly',
          });
        })
        .catch((err) => {
          this.category.errors.errors = err;
        });
    },
    createMenu() {
      this.menu.cate_id=this.active_cate
      var form =window.VFToFD(this.menu);
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_create_menu','variable':'category'}, { root: true }).then(() => {
          this.menus.unshift(this.$store.getters["admin/Getcategory"]);
          this.categorys[this.active_index].counter+=1;
          this.menu.reset();
          this.menu.from_data=1
          this.display = false;
          this.$bvModal.hide("post-update-modal-category");
          window.Toast.fire({
            icon: "success",
            title:'Menu created successfuly',
          });
        })
        .catch((err) => {
          this.menu.errors.errors = err;
        });
    },
    create() {
      var form =window.VFToFD(this.category);
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_create_menu_category','variable':'category'}, { root: true }).then(() => {
          this.categorys.unshift(this.$store.getters["admin/Getcategory"]);
          this.display = false;
          this.$bvModal.hide("post-update-modal-category");
          window.Toast.fire({
            icon: "success",
            title:'category created successfuly',
          });
        })
        .catch((err) => {
          this.category.errors.errors = err;
        });
    }
  },
};
</script>
<style scoped>
.cate{
  background-color: #F8F8F8;
  border-radius: 10px;
}
.cate:hover{
  background-color: #F8C655;
}
.menu{
  background-color: rgba(185, 185, 185, 0.507);
  border-radius: 10px;
}
.menu:hover{
  background-color: #F8C655;
}
@media (min-width:600px){
  body {
    font-size: 0.5rem !important;
  }
 }
 @media (min-width:900px){
  body {
    font-size: 0.6rem !important;
  }
 }
 .container {
  display: flex;
  flex-wrap: wrap; /* Allows items to wrap onto multiple lines */
  justify-content: space-between; /* Space between items */
  padding: 20px; /* Padding around the container */
}

.card {
  background-color: #f8f9fa; /* Card background color */
  border: 1px solid #ccc; /* Card border */
  border-radius: 5px; /* Rounded corners */
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Card shadow */
  transition: all 0.3s ease; /* Smooth transition for hover effect */
  flex: 0 1 calc(33.33% - 20px); /* Responsive card width (3 cards per row) */
  margin: 10px; /* Space around each card */
  min-width: 220px; /* Minimum width for card */
}



.card.selected {
  background-color: #007bff; /* Selected card background color */
  color: white; /* Text color for selected card */
  border: 1px solid #0056b3; /* Border color for selected card */
}

.card-title, .card-text {
  font-size: 1.1em; /* Base font size */
  margin: 10px; /* Spacing around text */
}

/* Icon Styles */
.card-icon {
  color: black; /* Set icon color to black */
  font-size: 1.5em; /* Smaller size for icons */
  margin: 10px; /* Margin around icons */
}

/* Responsive Icon Size */
@media (max-width: 768px) {
  .card-icon {
      font-size: 1.2em; /* Smaller icon size on medium screens */
  }

  .card-title, .card-text {
      font-size: 1em; /* Smaller font size on mobile */
  }

  .card {
      flex: 0 1 calc(50% - 20px); /* 2 cards per row on medium screens */
  }
}

@media (max-width: 480px) {
  .card {
      flex: 0 1 100%; /* Full width on small screens */
  }

  .card-icon {
      font-size: 1em; /* Even smaller icon size on small screens */
  }

  .card-title, .card-text {
      font-size: 0.9em; /* Smaller font size on small screens */
  }

  .add-card {
      width: calc(100% - 20px); /* Full width for button on small screens */
  }
}

.add-card {
  background-color: #28a745; /* Button background color */
  color: white; /* Button text color */
  padding: 10px 20px; /* Padding inside button */
  border: none; /* Remove border */
  border-radius: 5px; /* Rounded corners */
  cursor: pointer; /* Pointer on hover */
  transition: background-color 0.3s ease; /* Smooth transition */
  margin: 20px; /* Space around the button */
  width: calc(100% - 40px); /* Full width for button with padding */
}

</style>