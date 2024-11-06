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
             
            </div>
             </div>
             <div style="color:black;" v-if="false">
             <input type="checkbox" @change="filterbyGroup"> Group
             </div>
          <div class="panel-body table-responsive" v-if="!isgrouped">
          <datatable
            :columns="columns"
            :data="orders.data"
            class="table text-nowrap"
            style="text-align:center;color:black;"
          >
            <template slot-scope="{row,index}">
              <tr :style="row.isflaged==1?'background:tomato !important;color:black;':'color:black;'">
                <td style="vertical-align: middle">{{ index + 1 }}</td>
                <td style="vertical-align: middle">{{ row.menu!=null?row.menu.name:row.orders }}</td>
                <td style="vertical-align: middle"><span v-if="row.waiter">{{ row.waiter.first_name+row.waiter.middle_name+row.waiter.last_name }}</span></td>
                <td style="vertical-align: middle">{{ row.menu!=null?(row.menu.time?row.menu.time+' M':'None'):'None' }}</td>
                <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
                
              </tr>
            </template>
          </datatable>
          <div style="float:right;">
             <pagination :data="orders" @pagination-change-page="getmyorder">
                </pagination>
          </div>
          </div>
          <div class="panel-body table-responsive" v-if="isgrouped">
            <datatable
              :columns="columnsgrouped"
              :data="orders_grouped"
              class="table text-nowrap"
              style="text-align:center;color:black;"
            >
              <template slot-scope="{row,index}">
                <tr :style="row.isflaged==1?'background:tomato !important;color:black;':'color:black;'">
                  <td style="vertical-align: middle">{{ index + 1 }}</td>
                  <td style="vertical-align: middle">{{ row.menu!=null?row.menu.name:row.orders }}</td>
                  <td style="vertical-align: middle">{{row.waiter.first_name+' '+row.waiter.middle_name+' '+row.waiter.last_name}}</td>
                  <td style="vertical-align: middle">{{row.remark}}</td>
                  <td style="vertical-align: middle">{{row.total}}</td>
                
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
      columnsgrouped: [
        { label: "#" },
        { label: "Order", field: "order" },
        { label: "Waiter", field: "waiter" },
        { label: "Remark", field: "remark" },
        { label: "Total", field: "total" }
      ],
      columns: [
        { label: "#" },
        { label: "Order", field: "order" },
        { label: "time", field: "menu.time" },
        { label: "Waiter", field: "waiter.first_name" },
         { label: "Created At", field: "created_at" }
      ],
      printing:0,
      id_holder:'',
      isgrouped:1,
      orders: {data:[]},
      orders_grouped:[],
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
      type:0,
      order: new window.Form({
        title: "",
        attachment:"",
        id: "",
      }),
    };
  },
  beforeMount() {
    this.getGroupedOrderers()
  },
  mounted() {
    
  },
  methods: {
    filterbyGroup(){
      if(this.isgrouped){
        this.isgrouped=!this.isgrouped
      }else{
        this.isgrouped=!this.isgrouped
        this.getGroupedOrderers()
      }
    },
    PrintData(){
      this.printing=1;
      var toprint=document.getElementById('print-card')
      var newwindow=window.open('','printwindow')
      newwindow.document.open()
      newwindow.document.write('<html><head><style>#footer_display{font-size: 1.5em !important;text-align: center !important;}img{height: 100px;width:100px;}h3{font-size: 2em !important;}table th{text-align: start !important;padding-left:50px !important;color:black !important;background: #e7c21e ;border-top: 1px solid black !important;border-left: 1px solid black !important;border-bottom: 1px solid black !important;font-size: 2em !important;}.last{  border-right: 1px solid black !important;}table td{    font-size: 2em !important;border-left: 1px solid black !important;    border-bottom: 1px solid black !important;}</style></head><body onload="window.print()" style="margin:0;">'+toprint.innerHTML+'</body></html>')
      newwindow.document.close()
      setTimeout(function(){newwindow.close();},500)
      this.printing=0

    },
    payed(id){
      window.Swal.fire({
            title: 'are you sure',
            text: 'this Order Done',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_done_all_order','variable':'status','params':id}, { root: true }).then(() => {
        this.orders.data.splice(this.active_index,1)
        this.active_index=-1
        this.$bvModal.hide("post-update-modal-order");
        window.Toast.fire({
            icon: "success",
            title:'order All Done successfuly',
          });
      })
            }
     });
     
    },
    DoneOrder(id,index){
      window.Swal.fire({
            title: 'are you sure',
            text: 'this Order Done',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_done_order','variable':'status','params':id}, { root: true }).then(() => {
                this.orders.data.splice(index,1)
        window.Toast.fire({
            icon: "success",
            title:'order is done successfuly',
          });
      })
            }
     });
     
    },
    DoneOrderAll(id,index){
      window.Swal.fire({
            title: 'are you sure',
            text: 'this Order Done',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/AdminController',{'data':{ids:id},'method':'post','api':'admin_done_order_all','variable':'status'}, { root: true }).then(() => {
                this.orders_grouped.splice(index,1)
        window.Toast.fire({
            icon: "success",
            title:'order is done successfuly',
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
    attachmentprocessor (e) {
      var photos = e.target.files[0]
      var file = photos
      var limit = 1024 * 1024 * 2
      if (file.size <= limit) {
        this.order.attachment = file
        this.url = URL.createObjectURL(file)
      }
    },
    getGroupedOrderers(){
       this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_Grouped_order_done','variable':'ordergroup'}, { root: true }).then(() => {
        this.orders_grouped=this.$store.getters['admin/Getordergroup']
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
    getmyorder(page=1) {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_order_chef','variable':'order','params':'/order?page='+page}, { root: true }).then(() => {
        if (this.id_holder != this.$store.getters['admin/Getorder']) {
   
    const audio = new Audio('Two-Tone.mp3');
              audio.play();

if(this.id_holder){
  this.getGroupedOrderers()
}
        this.id_holder=this.$store.getters['admin/Getorder']
      }
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