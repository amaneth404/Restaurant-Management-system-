<template>
  <div>
   <b-row no-gutters style="display: flex;align-items: center;justify-content: center;">
     <b-col md="11" style="margin-top:5px;">
    
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
               >All Requested Items</strong
             >
              <div>
             </div>
           </div>
            </div>
         <div >
         <datatable
           :columns="columns"
           :data="items.data"
           class="table text-nowrap overflow-auto table-striped"
           style="text-align: center;color:black;"
           
         >
           <template slot-scope="{row,index}">
             <tr  >
               <td style="vertical-align: middle">{{ index + 1 }}</td>
               <td style="vertical-align: middle"><span class="badge badge-pill btn-primary" v-if='row.state==1'>Aproved</span><span class="badge badge-pill badge-danger" v-if='row.state==-1'>On Progress</span><span class="badge badge-pill badge-info" v-if='row.state==0'>Sold</span></td>
               <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
               <td>
                 <b-dropdown>
                   <template v-slot:button-content >
                     <i class="fas fa-ellipsis-v" style="color:grey;"></i>
                            </template>
                   <b-dropdown-item>
                 <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-request'"  @click='Edititem(row,index)' ><i class="fa fa-edit" style="color:grey;margin-right:10px;"></i>View</span>
                   </b-dropdown-item>
                 </b-dropdown>
               </td>
             </tr>
           </template>
         </datatable>
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
   <b-modal :id="'post-update-modal-request'" :size="'lg'">
         <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">Requests<span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-request')"></i></span></div>
   <div>
    <b-card style="border:0;border-radius: 0;color:black;">
      <b-row>
        <b-col v-for="data in selected_item.store_request" :key="data.id" md="12" style="background:silver;margin-top:10px;margin-bottom: 10px;padding:10px;">
        <h5>{{ data.item.item.title }} <span style="float:right;">{{ data.amount }}</span></h5>
        </b-col>
      </b-row>
      <b-button @click="SubmitAproval(selected_item.id,selected_index)" style="width:100%;" class="btn btn-primary">Submit</b-button>
    </b-card>
   </div>
   </b-modal>
 </div>
</template>
<script>
export default {
 data() {
   return {
     updateindex:-1,
     itemid:'',
     folder_id:'',
     holder:{},
     requestedItems:[],
     items:{data:[]},
     selected_item:[],
     selected_index:-1,
     name:'',
     form:new window.Form({
      search:''
     }),
     columns: [
       { label: "#" },
       { label: "State", field: "state" },
        { label: "Created At", field: "created_at" },
       { label: "Manage" },
     ],
   };
 },
 beforeMount() {
   this.getmyitem()
 },
 methods: {
  SubmitAproval(id,index){
    this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_items_requested_approve','variable':'status','params':id}, { root: true }).then(() => {
        this.items.data.splice(index,1);
        this.selected_index=-1;
        this.selected_item={}
        this.$bvModal.hide('post-update-modal-request')
        window.Toast.fire({
            icon: "success",
            title:'item Aproved SucceFully !!',
          });
      })
  },
  FilterItem(){
       this.$store.dispatch('admin/AdminController',{'data':{search:this.form.search},'method':'post','api':'admin_search_items_requested','variable':'items'}, { root: true }).then(() => {
        this.items=this.$store.getters['admin/Getitems']
      })
    },
  Edititem(data,index){
    this.name=data.name
    this.selected_index=index
        this.folder_id=data.id
        this.holder=data
    this.selected_item=data
  },
  Print(data){
    this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'users_get_request_items_detail','variable':'items','params':data.id}, { root: true }).then(() => {
          localStorage.setItem('data_requested_print',JSON.stringify({'data':data,'selected':this.$store.getters['admin/Getitems']}))
          this.$router.push('/print')
      })
  },
  UpdateName(){
    this.$store.dispatch('admin/AdminController',{'data':{name:this.name},'method':'post','api':'users_update_request_name','variable':'items','params':this.folder_id}, { root: true }).then(() => {
        this.items.data[this.selected_index].name=this.name
      })
  },
   removefromrequest(id,index){
           this.$store.dispatch('admin/AdminController',{'data':{},'method':'delete','api':'users_delete_request_items','variable':'status','params':id}, { root: true }).then(() => {
            this.selected_item.splice(index,1)
            this.$bvModal.hide("post-update-modal-request");
         window.Toast.fire({
            icon: "success",
            title:'item removed',
          });

      })
   },
   Seal(row,index){
    this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'users_seal_request_items','variable':'status','params':row.id}, { root: true }).then(() => {
            this.items.data[index].state=0;
            this.$bvModal.hide("post-update-modal-request");
         window.Toast.fire({
            icon: "success",
            title:'item sold',
          });
      })
   },
   Aprove(){
           this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_approve_item','variable':'status','params':this.folder_id}, { root: true }).then(() => {
            this.items.data[this.selected_index].state=1;
            this.folder_id=''
            this.$bvModal.hide("post-update-modal-request");
         window.Toast.fire({
            icon: "success",
            title:'item Aproved',
          });
      })
   },
   closemoadl(val){
      this.$bvModal.hide(val)
    },
   removefromrequestFolder(){
           this.$store.dispatch('admin/AdminController',{'data':{},'method':'delete','api':'users_delte_folder_request_items','variable':'status','params':this.folder_id}, { root: true }).then(() => {
            this.items.data.splice(this.selected_index,1)
            this.folder_id=''
            this.$bvModal.hide("post-update-modal-request");
         window.Toast.fire({
            icon: "success",
            title:'deleted',
          });
      })
   },
   getmyitem(page=1) {
      var api='users_get_request_items'
      if(this.$store.getters.role=='manager'){
         api='users_get_request_items'
      }
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':api,'variable':'items','params':'?page='+page}, { root: true }).then(() => {
        this.items=this.$store.getters['admin/Getitems']
      })
    },
 },
};
</script>
<style scoped>
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
   color:whitesmoke;
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