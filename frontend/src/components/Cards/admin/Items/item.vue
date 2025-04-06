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
                >All Items</strong
              >
               <div>
                <multiselect   name="studio" v-model="itemid" track-by="id" :close-on-select="true" label="title" :multiple="false" :search="true"  :options="item_group"></multiselect>
              </div>
              
              <b-button
                class="btn  bg-gradient-danger" 
                style="padding:0;"
                v-b-modal="'post-update-modal-item'"
                @click="resetform()"
                >
                <div class="top-1"></div>
                <span style="padding-left:10px;padding-right:10px;"><i class="fas fa-gamepad" style="margin-right: 10px"></i
                >Add Item</span>
                <div class="top-2"></div>
                </b-button
              >
            </div>
             </div>
             <div class="panel-body table-responsive">
          <datatable :columns="columns" :data="items.data" class="table text-nowrap overflow-auto table-striped" style="text-align: center;color:black;">
            <template slot-scope="{row,index}">
              <tr :style='row.sold==row.amount?"background:tomato;color:black;":""' >
                <td style="vertical-align: middle">{{ index + 1 }}</td>
                <td style="vertical-align: middle">{{ row.item.title }}</td>
                <td style="vertical-align: middle">{{ row.item.unit?row.item.unit:'' }}</td>
                <!-- <td style="vertical-align: middle"><i class="fas fa-check-circle" v-if='row.isreturn' style="color:green;"></i><i class="fas fa-times-circle" v-if='!row.isreturn' style="color:black;"></i></td> -->
                <td style="vertical-align: middle">{{ row.price }}</td>
                <td style="vertical-align: middle">{{ row.sell_price }}</td>
                <td style="vertical-align: middle">{{ row.amount*1-row.sold*1 }}</td>
                <td style="vertical-align: middle"><span class="badge badge-pill btn-primary" v-if='row.state==1'>Enabled</span><span class="badge badge-pill badge-danger" v-if='row.state!=1'>Disabled</span></td>
                <td style="vertical-align: middle"><span class="badge badge-pill btn-primary" v-if='row.linked==1'>Linked</span><span class="badge badge-pill badge-danger" v-if='row.linked!=1'>Not Linked</span></td>
                 <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
                <td>
                  <b-dropdown>
                    <template v-slot:button-content >
                      <i class="fas fa-ellipsis-v" style="color:grey;"></i>
                             </template>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-item'" v-if="$store.getters.role=='Admin'" @click='Edititem(row,index)' ><i class="fa fa-edit" style="color:grey;margin-right:10px;"></i>Edit</span>
                    </b-dropdown-item>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-transfer'"  @click='TransferInit(row,index)' ><i class="fa fa-edit" style="color:grey;margin-right:10px;"></i>Transfer</span>
                    </b-dropdown-item>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-link-to-menu'" v-if="$store.getters.role=='Admin'&&row.linked==0" @click='Link(row,index)' ><i class="fa fa-edit" style="color:grey;margin-right:10px;"></i>Link</span>
                    </b-dropdown-item>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  v-if="$store.getters.role=='Admin'&&row.linked==1" @click='UnLink(row,index)' ><i class="fa fa-edit" style="color:grey;margin-right:10px;"></i>Unlink</span>
                    </b-dropdown-item>
                       <b-dropdown-item v-if='row.state==1'>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='disable(row.id,index)' v-if='$store.getters.role=="Admin"'><i class="fas fa-user-alt-slash" style="color:grey;margin-right:10px;"></i> Disable</span>
                    </b-dropdown-item>
                     <b-dropdown-item v-if='row.state!=1'>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='enable(row.id,index)'  v-if='$store.getters.role=="Admin"'><i class="fas fa-user-check" style="color:grey;margin-right:10px;"></i> Enable</span>
                    </b-dropdown-item>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='deleteitem(row.id,index)'  v-if='$store.getters.role=="Admin"'><i class="fas fa-trash-alt" style="color:grey;margin-right:10px;"></i> Delete</span>
                    </b-dropdown-item>
                  </b-dropdown>
                </td>
              </tr>
            </template>
          </datatable>
          </div>
        </div>
            <div>
              <pagination :data="items" @pagination-change-page="getmyitem">
                </pagination>
          </div>
           
        </b-card>
      </b-col>
    </b-row>
    <b-modal :id="'post-update-modal-item'" :size="'md'">
          <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">{{display?'Update Item':'Add Item'}} <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-item')"></i></span></div>
      <form
         style="padding:30px;padding-top:15px;margin-bottom:35px;"
        @submit.prevent="display ? update() : create()"
      >
        <div class="form-group row">
          <label>Item Group</label>
          <multiselect   name="studio" v-model="item.item" track-by="id" :close-on-select="true" label="title" :multiple="false" :search="true"  :options="item_group"></multiselect>
          <has-error :form="item" field="item"></has-error>
        </div>
        <div class="form-group row">
          <label>amount</label>
          <input
          required
            v-model="item.amount"
            name="amount"
            type="number"
            placeholder="amount"
            class="form-control"
            :class="{ 'is-invalid': item.errors.has('amount') }"
          />
          <has-error :form="item" field="amount"></has-error>
        </div>
         <div class="form-group row">
          <label>price</label>
          <input
          required
            v-model="item.price"
            name="price"
            placeholder="price"
            class="form-control"
            :class="{ 'is-invalid': item.errors.has('price') }"
          />
          <has-error :form="item" field="price"></has-error>
        </div>
        <div class="form-group row">
          <label>Sale price</label>
          <input
          required
            v-model="item.sell_price"
            name="Sale price"
            placeholder="sale price"
            class="form-control"
            :class="{ 'is-invalid': item.errors.has('sell_price') }"
          />
          <has-error :form="item" field="sell_price"></has-error>
        </div>
      
        <div  style='border-top:1px solid rgba(0.5,0.5,0.5,0.3);padding-top:10px;margin-top:15px;'>
        <div class="float-right">
          <b-button type="submit"  class="btn-primary" v-if="!display">
            Save
          </b-button>
          <b-button type="submit" class="btn-primary" v-if="display">
            Update
          </b-button>
        </div>
        </div>
      </form>
    </b-modal>
    <b-modal :id="'post-link-to-menu'" :size="'md'">
          <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">Link Item <span></span> <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-link-to-menu')"></i></span></div>
      <div>
        <div v-for="menu in menu_items" :key="menu.id" class="no-body" style="padding:5px;margin-bottom:3px;background-color: transparent;">
         <div style="color:white;padding:5px;border-radius: 3px;padding:15px;" class="menu" @click="selected_menu=menu.id" :style="selected_menu==menu.id?'background:#e7c21e;color:black;':''">{{ menu.name }}</div> 
        </div>
        <div style="margin-bottom: 10px;margin-left:20px;">
            <b-button class="btn btn-primary" @click="submitLinking">
               <i class="fas fa-check"  style="margin-left:10px;"></i> Link
            </b-button>
          </div>
      </div>
    </b-modal>
    <b-modal :id="'post-transfer'" :size="'md'">
          <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">Transfer <span></span> <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-transfer')"></i></span></div>
      <div>
       <div>
        <b-form @submit.prevent="TransferItem" style="padding:10px;">
          Total Amount : <strong>{{ active_link.amount-active_link.sold }}</strong>
          <input type="number" placeholder="amount" class="form-control" v-model="transfer.amount" style="margin-bottom: 10px;margin-top:10px;">
          <select name="" id="" class="form-control" v-model="transfer.to">
            <option value="">Select Transfer Location</option>
            <option value="0">kitchen</option>
            <option value="1">Casher</option>
            <option value="2">Damaged</option>
          </select>
          <div style="margin:10px;">
            <b-button class="btn btn-primary" @click="TransferItem">
              <i class="fas fa-check"  style="margin-left:10px;"></i>
              Transfer
            </b-button>
          </div>
        </b-form>

       </div>

      </div>
    </b-modal>
  </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<script>
import Multiselect from 'vue-multiselect'
export default {
  components:{
            Multiselect
        },
  data() {
    return {
      total:0,
      selected_menu:-1,
      transfer:new window.Form({
        to:0,
        amount:0
      }),
      issingle:true,
      updateindex:-1,
      itemid:'',
      columns: [
        { label: "#" },
        { label: "Item", field: "title" },
        { label: "Unit", field: "unit" },
        { label: "Price", field: "price" },
        { label: "Sale Price", field: "sell_price" },
        { label: "Amount", field: "amount" },
        { label: "State", field: "state" },
        { label: "Linked", field: "linked" },
         { label: "Created At", field: "created_at" },
        { label: "Manage" },
      ],
      items: {data:[]},
      item_group:[],
      itemholder:[],
      display: false,
      url: "",
      title:'',
      fee:'',
      code:'',
      branchs:[],
      selected_titles:[],
      isedit:-1,
      requestedItems:[],
      requested_holder:[],
      item_data:{item:{}},
      active_link:-1,
      menu_items:[],
      item: new window.Form({
        item: "",
        price:"",
        type:0,
        sell_price:"",
        amount:"",
        branch:"",
        id: "",
      }),
      name:''
    };
  },
  watch:{
    itemid:function(val){
      if(val!=null){
      this.filterByItem(1)
      }else{
        this.getmyitem()
      }
    }
  },
  beforeMount() {
    this.getmyitem()
    this.getmyitemGroup()
  },
  methods: {
    TransferInit(row,index){
      this.active_link=row
      this.updateindex=index
    },
    TransferItem(){
     var form =window.VFToF(this.transfer);
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_transfer_item','variable':'status','params':this.active_link.id}, { root: true }).then(() => {
        this.items.data[this.updateindex].sold=this.items.data[this.updateindex].sold*1+this.transfer.amount*1
      this.$bvModal.hide("post-transfer");
        this.updateindex=-1
      this.active_link=-1
      window.Toast.fire({
            icon: "success",
            title:'Transfered successfuly',
          });
      })
    },
    submitLinking(){
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_item_link','variable':'status','params':this.active_link.id+'/'+this.selected_menu}, { root: true }).then(() => {
          this.items.data[this.updateindex].linked=1
        this.updateindex=-1
      this.active_link=-1
      window.Toast.fire({
            icon: "success",
            title:'Linked  successfuly',
          });
      this.$bvModal.hide("post-link-to-menu");
      })
    },
    Link(row,index){
      this.updateindex=index
      this.active_link=row
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_menu_for_linking','variable':'items'}, { root: true }).then(() => {
        this.menu_items=this.$store.getters['admin/Getitems']
      })
    },
    UnLink(row,index){
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_item_unlink','variable':'status','params':row.id}, { root: true }).then(() => {
        this.items.data[index].linked=0;
        window.Toast.fire({
            icon: "success",
            title:'UNLinked  successfuly',
          });
      })
    },
    filterByItem(page=1){
       var api='admin_filter_items'
      if(this.$store.getters.role=='manager'){
         api='users_filter_items'
      }
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':api,'variable':'items','params':this.itemid.id+'?page='+page}, { root: true }).then(() => {
        var holder=this.$store.getters['admin/Getitems']
        this.items=holder.items
        this.total=holder.total
      })
    },
    closemoadl(val){
      this.$bvModal.hide(val)
    },
    resetform() {
      this.item.reset();
      this.url = "";
      this.display=false
      this.updateindex=-1
    },
    Edititem(item,index) {
      this.item.fill(item);
      this.item.item=item.item
      this.item.branch=item.branch.id
      this.display = true;
      this.updateindex=index
    },
    getmyitem(page=1) {
      var api='admin_get_items'
      if(this.$store.getters.role=='manager'){
         api='users_get_items'
      }
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':api,'variable':'items','params':'?page='+page}, { root: true }).then(() => {
        this.items=this.$store.getters['admin/Getitems']
      })
    },
    getmyitemGroup() {
       var api='admin_get_for_filter_item'
      if(this.$store.getters.role=='manager'){
         api='users_get_for_filter_item'
      }
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':api,'variable':'item'}, { root: true }).then(() => {
        this.item_group=this.$store.getters['admin/Getitem']
      })
    },
     getmyBranchs() {
        var api='admin_get_branch'
      if(this.$store.getters.role=='manager'){
         api='users_get_branch'
      }
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':api,'variable':'branch'}, { root: true }).then(() => {
        this.branchs=this.$store.getters['admin/GetBranch']
      })
    },
    deleteitem(id,index) {
       var api='admin_delete_items'
      if(this.$store.getters.role=='manager'){
         api='users_delete_items'
      }
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to delete this item',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'delete','api':api,'variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'item deleted successfuly',
          });
        this.items.data.splice(index,1)
      })
            }
     });
    },
     disable(id,index) {
        var api='admin_disable_items'
      if(this.$store.getters.role=='manager'){
         api='users_disable_items'
      }
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to disable this Items',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'put','api':api,'variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'Items disabled successfuly',
          });
           this.items.data[index].state=-1
      })
            }
     });
    },
      enable(id,index) {
        var api='admin_enable_items'
      if(this.$store.getters.role=='manager'){
         api='users_enable_items'
      }
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to enable this Items',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'put','api':api,'variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'Items enabled successfuly',
          });
          this.items.data[index].state=1
      })
            }
     });
    },
   
    update() {
      var api='admin_update_items'
      if(this.$store.getters.role=='manager'){
         api='users_update_items'
      }
      var id=this.item.id
      this.item.item=this.item.item.id
     var form =window.VFToF(this.item);
      this.$store
        .dispatch('admin/AdminController',{'data':form,'method':'put','api':api,'variable':'item','params':id}, { root: true })
        .then(() => {
          var data = this.$store.getters["admin/Getitem"];
          this.items.data[this.updateindex].id=data.id
          this.items.data[this.updateindex].title=data.title
          this.items.data[this.updateindex].price=data.price
          this.items.data[this.updateindex].sell_price=data.sell_price
          this.items.data[this.updateindex].amount=data.amount
          this.items.data[this.updateindex].created_at=data.created_at
          this.display = false;
          this.$bvModal.hide("post-update-modal-item");
           window.Toast.fire({
            icon: "success",
            title:'item updated successfuly',
          });
        })
        .catch((err) => {
          this.item.errors.errors = err;
        });
    },
    create() {
      this.item.item=this.item.item.id
      var form =window.VFToF(this.item);
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_create_items','variable':'item'}, { root: true }).then(() => {
          // this.items.data.unshift(this.$store.getters["admin/Getitem"]);
          this.getmyitem()
          this.display = false;
          this.$bvModal.hide("post-update-modal-item");
          window.Toast.fire({
            icon: "success",
            title:'item created successfuly',
          });
        })
        .catch((err) => {
          this.item.errors.errors = err;
        });
    }
  },
};
</script>

<style scoped>
.menu{
  background-color: black;
  cursor: pointer;
}
.menu:hover{
  background-color: gray;
}
.search-controller{
  border:0;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
}
::placeholder {
 color: silver;
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
table th{
  text-align: center !important;
}
.card {
  position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
   background-color: white;
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