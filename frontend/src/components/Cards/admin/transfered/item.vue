<template>
   <div>
    <b-row>
      <b-col md="11" style="margin-left:2%;margin-top:5px;">
     
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
                >All Transfer History</strong
              >
               <div>
                <multiselect   name="studio" v-model="itemid" track-by="id" :close-on-select="true" label="title" :multiple="false" :search="true"  :options="item_group"></multiselect>
                
              </div>
              
            <export-excel :data="json_data" :fields="json_fields" workseet="My worksheet" name='TransferHistoryReport.xls' type="csv" class="btn" id="export-log-to-xl" v-show="false">
     </export-excel>
            </div>
             </div>
          <div class="panel-body table-responsive">
          <datatable
            :columns="columns"
            :data="itemid!=''? filtered_items.data:items.data"
            class="table text-nowrap overflow-auto table-striped"
            style="text-align: center;color:black;"
          >
            <template slot-scope="{row,index}">
              <tr :style='row.sold==row.amount?"background:tomato;color:black;":""' >
                <td style="vertical-align: middle">{{ index + 1 }}</td>
                <td style="vertical-align: middle">{{ row.item.title }}</td>
                <td style="vertical-align: middle">{{ row.item.unit?row.item.unit:'' }}</td>
                <td style="vertical-align: middle">{{ row.price }}</td>
                <td style="vertical-align: middle">{{ row.sell_price }}</td>
                <td style="vertical-align: middle">{{ row.amount+' / '+(row.item.unit?row.item.unit:'') }}</td>
                <td style="vertical-align: middle"><span class="badge badge-pill btn-primary" v-if='row.type==0'>kitchen</span><span class="badge badge-pill badge-success" v-if='row.type==1'>Casher</span><span class="badge badge-pill badge-danger" v-if='row.type==2'>Damaged</span> </td>
                <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
              </tr>
            </template>
          </datatable>
          </div>
        </div>
            <div  v-if="itemid==''">
            <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item" :class="items.currentPage==1?'disabled':''" @click="items.currentPage==1?'':getmyitem(items.currentPage-1)">
      <a class="page-link"  tabindex="-1" aria-disabled="true" >Previous</a>
    </li>
    <li class="page-item" v-for='i in items.lastPage' :key='i' :class="items.currentPage==i?'active':''" @click="getmyitem(i)"><a class="page-link" >{{i}}</a></li>
    <li class="page-item" :class="items.currentPage==items.lastPage?'disabled':''" @click="items.currentPage==items.lastPage?'':getmyitem(items.currentPage+1)">
      <a class="page-link">Next</a>
    </li>
  </ul>
</nav>
          </div>
              <div v-if="itemid!=''">
            <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item" :class="filtered_items.currentPage==1?'disabled':''" @click="filtered_items.currentPage==1?'':filterByItem(filtered_items.currentPage-1)">
      <a class="page-link"  tabindex="-1" aria-disabled="true" >Previous</a>
    </li>
    <li class="page-item" v-for='i in filtered_items.lastPage' :key='i' :class="filtered_items.currentPage==i?'active':''" @click="filterByItem(i)"><a class="page-link" >{{i}}</a></li>
    <li class="page-item" :class="filtered_items.currentPage==filtered_items.lastPage?'disabled':''" @click="filtered_items.currentPage==filtered_items.lastPage?'':filterByItem(filtered_items.currentPage+1)">
      <a class="page-link">Next</a>
    </li>
  </ul>
</nav>
          </div>
        </b-card>
      </b-col>
    </b-row>
       <div class="loading " v-if='loading'>
      <div class="d-flex justify-content-center">
     <div  type="button" disabled style="margin-top:20%;">
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  Loading...
</div>
</div>
    </div>
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
      json_fields:{
        'Item':'item.title',
        'Price':'price',
        'sale Price':'sell_price',
        'Amount':'amount',
        'Transfered To':{
                callback: (value) => {
                    return ` ${value.type==1?'Casher':'Kitchen'}`;
                }
            },
        'Location':'branch.location',
        'Created At':'created_at'
      },
      json_data:[],
      json_meta:[
        [
          {
            'key':'charset',
            'value':'utf-8'
          }
        ]
      ],
      loading:false,
      updateindex:-1,
      itemid:'',
      columns: [
        { label: "#" },
        { label: "Item", field: "item.title" },
        { label: "Unit", field: "item.unit" },
        { label: "Price", field: "price" },
        { label: "Sale Price", field: "sell_price" },
        { label: "Amount", field: "amount" },
        { label: "Transfered To", field: "type" },
         { label: "Created At", field: "created_at" },
      ],
      items: {data:[]},
      item_group:[],
      display: false,
      url: "",
      title:'',
      fee:'',
      code:'',
      selected_titles:[],
      filtered_items:{data:[]},
      isedit:-1,
      item_data:{item:{}},
    };
  },
  watch:{
    itemid:function(){
      this.filterByItem(1)
    }
  },
  beforeMount() {
    this.getmyitem()
    this.getmyitemGroup()
  },
  methods: {
      exportex(){
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_all_transfered','variable':'items'}, { root: true }).then(() => {
        this.json_data=this.$store.getters['admin/Getitems']
        setTimeout(()=>{
           document.getElementById('export-log-to-xl').click()
        this.loading=false
         if(!this.json_data.length){
           window.Toast.fire({
            icon: "warning",
            title:'Items Log is empity !!',
          });
        }
        },2000) 
      })
    },
    filterByItem(page=1){
       var api='admin_filter_items_transfered'
      if(this.$store.getters.role=='manager'){
         api='users_filter_items_transfered'
      }
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':api,'variable':'items','params':this.itemid.id+'?page='+page}, { root: true }).then(() => {
        this.filtered_items=this.$store.getters['admin/Getitems']
      })
    },
    getmyitem(page=1) {
      var api='admin_get_items_filtered'
      if(this.$store.getters.role=='manager'){
         api='users_get_items_filtered'
      }
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':api,'variable':'items','params':'?page='+page}, { root: true }).then(() => {
        this.items=this.$store.getters['admin/Getitems']
      })
    },
    getmyitemGroup() {
       var api='admin_get_item'
      if(this.$store.getters.role=='manager'){
         api='users_get_item'
      }
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':api,'variable':'item'}, { root: true }).then(() => {
        this.item_group=this.$store.getters['admin/Getitem']
      })
    },
  },
};
</script>
<style scoped>
.loading{
  color:black;
  font-size:1.3em;
  position: fixed;
  bottom: 0;
  top: 0;
  left: 0;
  z-index: 3000;
  right: 0;
  background: rgba(0.5,0.5,0.5,0.5);
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
    /* border: 0 solid #d9dee3; */
    border-radius: 0.5rem;
    border: 0;
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