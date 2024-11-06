<template>
  <div style="width:100%;">
    <h2 style="text-align: left;"><strong>Dashboard</strong></h2>
    <b-row no-gutters style="margin:0;width:100%;padding-left: 50px;" class="main" >
      <b-col md="12" style="margin-top:0px;">
        <h2 style="color:black;text-align:left;"><b>Store Report</b></h2>
        <div>
        <div style="display:flex;justify-content: start;margin-bottom:20px;overflow-x: auto;color:black;" v-if='$store.getters.role=="Admin"'>
                 <div class="custom-control custom-checkbox">
  <input type="checkbox" class="custom-control-input" id="customCheck1" v-model="isreported" @change="getREportFolder">
  <label class="custom-control-label" for="customCheck1">do you want to filter including the Reported reportes ?</label>
</div>
               </div>
               <div v-if='isreported&&$store.getters.role=="Admin"'>
                 <div style="display:flex;justify-content: start;margin-bottom:20px;overflow-x: auto;color:black;">
                 <select name="" id=""  v-model="reported_date" class="form-control">
                   <option value="">Select Reported Date</option>
                   <option :value="report.date" v-for="report in report_folder" :key="report.id">{{report.created_at|myDate}}</option>
                 </select>
                 </div>
               </div>
              <p style="color:black;text-align:left;" >Store Report With preference</p>
              <div  >
                <div style="display:flex;justify-content: space-between;">
                <select name="" id="" @change="GetReportInit" style="float: left;">
                  <option value="t">Today</option>
                  <option value="w">Weekly</option>
                  <option value="m">Monthly</option>
                  <option value="m">Yearly</option>
                </select>
             <!-- <div class="btn-group" role="group" aria-label="Basic example">
  <button type="button" class="btn btn-outline-danger selected" style="margin-right:5px;width:200px;color:black;" @click="GetReport(1)" :class="active==1?'btn btn-danger':''">Today</button>
  <button type="button" class="btn btn-outline-danger" style="margin-right:5px;width:200px;color:black;" @click="GetReport(2)" :class="active==2?'btn btn-danger':''">Weakly</button>
  <button type="button" class="btn btn-outline-danger" style="margin-right:5px;width:200px;color:black;" @click="GetReport(3)" :class="active==3?'btn btn-danger':''">Monthly</button>
  <button type="button" class="btn btn-outline-danger" style="margin-right:5px;width:200px;color:black;" @click="GetReport(4)" :class="active==4?'btn btn-danger':''">Yearly</button>
</div> -->
<div >
    <b-row no-gutters  >
      <b-col md='auto' >
        <div style="float:left;">
 <label  for="" style="color:black;padding-top:4px;margin-right:5px;">From :</label> <input type="date" name="" id="" placeholder="from" v-model="from" style="border:0.5px solid grey;border-radius: 5px;">
 </div>
      </b-col>
      <b-col md='auto'>
        <div style="float:left;">
 <label for="" style="color:black;padding-top:4px;margin-right:30px;margin-left:20px;">to :</label><input type="date" name="" id="" placeholder="to" v-model="to" style="border:0.5px solid grey;border-radius: 5px;">
        </div>
      </b-col>
      <b-col md="auto">
        <div >
 <b-button class="btn btn-success btn-sm" @click="GetReportByDate" style="margin-left:10px;"> Filter</b-button>
        </div>
      </b-col>
    </b-row>
 </div>
</div>
</div>
<div >
  </div>
 
  
  <div style="display:flex;justify-content: start;margin-bottom:5px;margin-top:5px;" v-if="$store.getters.role=='Admin'">
    <div >
      <select name="" id="" @change="GetReportprintInit">
        <option value="">Export</option>
                  <option value="2">Items Log</option>
                  <option value="3">All Items Report</option>
                </select>
            <export-excel :data="json_data" :fields="json_fields" workseet="My worksheet" name='Report.xls' type="csv" class="btn" id="export-dash-to-xl" v-show="false">
     </export-excel>
       <export-excel :data="json_data" :fields="json_fields_log" workseet="My worksheet" :name='filename' type="csv" class="btn" id="export-item-log-to-xl" v-show="false">
     </export-excel>
     <export-excel :data="json_data" :fields="json_fields_items" workseet="My worksheet" :name='filename' type="csv" class="btn" id="export-item-items-to-xl" v-show="false">
     </export-excel>
     <export-excel :data="json_data" :fields="json_fields_items" workseet="My worksheet" :name='filename' type="csv" class="btn" id="export-item-items-to-xl-all" v-show="false">
     </export-excel>
     </div>
</div>
</div>
               <b-card
        no-body
          class="overflow-auto card"
          style="padding:0;height:600px;padding:0px;padding-top:25px;border:0;background:transparent;"
        >
        <b-row no-gutters>
            <b-col md='3' style="margin-bottom:10px;">
              <b-card style="border:0;border-radius: 10px;background-color: #faaeaf34;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-transfer-up" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL Transfered By Date</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.report.total}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total items transfered</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col>
           <b-col md='3' style="margin-bottom:10px;" v-if="$store.getters.role=='Admin'">
            <b-card style="border:0;border-radius: 10px;background-color: #eaeaf7;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL Cash By Date</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.report.totalCash}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total money</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col>
          <b-col md='3' style="margin-bottom:10px;">
            <b-card style="border:0;border-radius: 10px;background-color:#faaeaf34;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-transfer-up" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL Transfered</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.items.total.sold}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total transfered items</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col>
           <b-col md='3' style="margin-bottom:10px;">
            <b-card style="border:0;border-radius: 10px;background-color: #eaeaf7;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-stocking" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL In Stock</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.items.total.inStock}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total in stock items</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col>
           <b-col md='3' style="margin-bottom:10px;">
            <b-card style="border:0;border-radius: 10px;background-color: #ddfcdac4;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-food" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL Category</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.category.total.total}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total category items</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col>
           <b-col md='3' style="margin-bottom:10px;">
            <b-card style="border:0;border-radius: 10px;background-color:#e8f8e7 ;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-food" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL Item Group</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.item.total.total}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total item groups</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col>
           <b-col md='3' style="margin-bottom:10px;">
            <b-card style="border:0;border-radius: 10px;background-color: #ddfcdac4;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-food" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL Out of Stock Items</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.items.outofStock.total}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total out of stock items</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col>
           <b-col md='3' style="margin-bottom:10px;">
            <b-card style="border:0;border-radius: 10px;background-color: #eaeaf7;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-food" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL Disabled Items</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.items.disabled.total}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total disabled items</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col>
           <b-col md='3' style="margin-bottom:10px;">
            <b-card style="border:0;border-radius: 10px;background-color: #ddfcdac4;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-food" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL Disabled Item Groups</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.item.disabled.total}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total disabled item group</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col>
           <b-col md='3' style="margin-bottom:10px;">
            <b-card style="border:0;border-radius: 10px;background-color: #e8f8e7;margin-right:10px;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-food" style="font-size: 2em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;"><strong>TOTAL Disabled Categories</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{report.category.disabled.total}}</strong></h5>
                    <p style="text-align: left;font-size: 0.9em;margin-bottom:-10px;">total disabled Categories</p>
                  </b-col>
                </b-row>
              </b-card>
          </b-col> <b-col md='3' style="margin-bottom:10px;" v-if="false">
            <b-card style="height:167px;background:rgb(96,112,228);padding-top:5px;width:95%;" class="report-card-3">
              <div style="color:black;text-align:left;">
                <h5><strong>TOTAL Branches</strong></h5>
                <h1><strong>{{report.b_holder.total}}</strong></h1>
                <h6 style="margin-top:0px;">See Details</h6>
              </div>
            </b-card>
          </b-col> <b-col md='3' style="margin-bottom:10px;" v-if="false">
            <b-card style="height:167px;background:rgb(96,112,228);padding-top:5px;width:95%;" class="report-card-2">
              <div style="color:black;text-align:left;">
                <h5><strong>TOTAL Disabled Branches</strong></h5>
                <h1><strong>{{report.b_holder.disabled}}</strong></h1>
                <h6 style="margin-top:0px;">See Details</h6>
              </div>
            </b-card>
          </b-col>
        </b-row>
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
    <!-- <div style="background:transparent;padding-top:40px;padding:20px;" >
        <div no-body>
          <b-card>
            <input type="date" v-model="from"> From
            <input type="date" v-model="to"> to
            <b-button @click="GetReport">amanuel</b-button>
            <div>
               <div class="form-group row">
          <label>Item Group</label>
          <select
          required
            v-model="itemid"
            @change="filterByItem"
            name="item"
            placeholder="item"
            class="form-control"
          >
          <option value="">Select Item Group</option>
          <option v-for='item in item_group' :value="item.id" :key='item.id'>{{item.title}}</option>
          </select>
        </div>
            </div>
          </b-card>
        </div>
    </div> -->
</template>
<script>
export default {
    data(){
      return {
         json_fields_log:{
        'Item':'item.title',
        'Price':'price',
        'sale Price':'sell_price',
        'Amount':'amount',
        'Type':{
                callback: (value) => {
                    return ` ${value.type?'Store':'Shope'}`;
                }
            },
        'Created At':'created_at'
      },
      json_fields_items:{
        'Item':'item.title',
        'Price':'price',
        'sale Price':'sell_price',
        'Amount':{
                callback: (value) => {
                    return ` ${value.amount-value.sold}`;
                }
            },
        'Type':{
                callback: (value) => {
                    return ` ${value.type?'Store':'Shope'}`;
                }
            },
        'Created At':'created_at'
      },
         json_fields:{
        'Item':'item.title',
        'Price':'price',
        'sale Price':'sell_price',
        'Sold':'amount',
        'Type':{
                callback: (value) => {
                    return ` ${value.type?'Store':'Shope'}`;
                }
            },
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
         isreported:false,
      reported_date:'',
      filename:'',
      report_folder:[],
      ismain:true,
      loading:false,
        dashbored:[],
        itemid:'',
        from:'',
        active:1,
        to:'',
        item_group:[],
        report:{items:{total:{sold:0,inStock:0},outofStock:{total:0},disabled:{total:0}},item:{total:{total:0},disabled:{total:0}},category:{total:{total:0},disabled:{total:0}},report:{total:0,totalCash:0},b_holder:{total:0,disabled:0}},
      }
    },
    beforeMount(){
      // this.getmyitem()
      this.GetReport(1)
    },
    methods:{
       getREportFolder(){
         this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_report_folder','variable':'report'}, { root: true }).then(() => {
          this.report_folder=this.$store.getters['admin/GetReport']
      })
      },
        exportex(){
          var form={isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
          var id=this.active
          if(this.from!=''&&this.to!=''){
            form={from:this.from,to:this.to,isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
            id=''
          }
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_get_all_report','variable':'report','params':id}, { root: true }).then(() => {
        this.json_data=this.$store.getters['admin/GetReport']
       setTimeout(()=>{
           document.getElementById('export-dash-to-xl').click()
        this.loading=false
         if(!this.json_data.length){
           window.Toast.fire({
            icon: "warning",
            title:'Report is empity !!',
          });
        }
        },2000) 

      })
    },
    exportexLog(){
      this.filename='ItemsLogReport.xls'
          var form={isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
          var id=this.active
          if(this.from!=''&&this.to!=''){
            form={from:this.from,to:this.to,isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
            id=''
          }
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_get_all_report_tiems_log','variable':'report','params':id}, { root: true }).then(() => {
        this.json_data=this.$store.getters['admin/GetReport']
       setTimeout(()=>{
           document.getElementById('export-item-log-to-xl').click()
        this.loading=false
         if(!this.json_data.length){
           window.Toast.fire({
            icon: "warning",
            title:'items log is empity !!',
          });
        }
        },2000) 

      })
    },
    exportexStock(){
      this.filename='ItemsInShopReport.xls'
          var form={isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
          var id=this.active
          if(this.from!=''&&this.to!=''){
            form={from:this.from,to:this.to,isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
            id=''
          }
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_get_all_report_tiems_stock','variable':'report','params':id}, { root: true }).then(() => {
        this.json_data=this.$store.getters['admin/GetReport']
       setTimeout(()=>{
           document.getElementById('export-item-items-to-xl').click()
        this.loading=false
         if(!this.json_data.length){
           window.Toast.fire({
            icon: "warning",
            title:'items in stock is empity !!',
          });
        }
        },2000) 

      })
    },
    exportexAll(){
      this.filename='AllItemsReport.xls'
          var form={isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
          var id=this.active
          if(this.from!=''&&this.to!=''){
            form={from:this.from,to:this.to,isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
            id=''
          }
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_get_all_report_tiems_data','variable':'report','params':id}, { root: true }).then(() => {
        this.json_data=this.$store.getters['admin/GetReport']
       setTimeout(()=>{
           document.getElementById('export-item-items-to-xl-all').click()
        this.loading=false
         if(!this.json_data.length){
           window.Toast.fire({
            icon: "warning",
            title:'items is empity !!',
          });
        }
        },2000) 

      })
    },
    exportexStore(){
      this.filename='ItemsInStoreReport.xls'
          var form={isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
          var id=this.active
          if(this.from!=''&&this.to!=''){
            form={from:this.from,to:this.to,isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date}
            id=''
          }
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_get_all_report_tiems_store','variable':'report','params':id}, { root: true }).then(() => {
        this.json_data=this.$store.getters['admin/GetReport']
       setTimeout(()=>{
           document.getElementById('export-item-items-to-xl').click()
        this.loading=false
         if(!this.json_data.length){
           window.Toast.fire({
            icon: "warning",
            title:'items in Store is empity !!',
          });
        }
        },2000) 

      })
    },
      
      FilterReport(){
        console.log('asdfsdaf')
      },
      filterByItem(){
 this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_filter_report','variable':'status','params':this.itemid}, { root: true }).then(() => {
      })
      },
      GetReportInit(e){
        switch(e.target.value){
          case 't':
          this.GetReport(1);
          break;
          case 'w':
          this.GetReport(2);
          break;
          case 'm':
          this.GetReport(3);
          break;
          case 'y':
          this.GetReport(4);
          break;
        }
      },
      GetReportprintInit(e){
switch(e.target.value){
          case '1':
          this.exportex();
          break;
          case '2':
          this.exportexLog();
          break;
          case '3':
          this.exportexAll();
          break;
          case '4':
          this.exportexStock();
          break;
        }
      },
       GetReport(id){
        this.active=id
      this.loading=true
        var url='admin_get_report'
        if(this.$store.getters.role=='Store')
        url='admin_get_report'
        this.$store.dispatch('admin/AdminController',{'data':{isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date},'method':'post','api':url,'variable':'report','params':id}, { root: true }).then(() => {
          this.report=this.$store.getters['admin/GetReport']
      this.loading=false
      }).catch(() => {
        this.loading=false;
        });
      },
      GetReportByDate(){
      this.loading=true
        this.$store.dispatch('admin/AdminController',{'data':{from:this.from,to:this.to,isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date},'method':'post','api':'admin_filter_date_report','variable':'report'}, { root: true }).then(() => {
          var report=this.$store.getters['admin/GetReport']
          if(report.totalCash){
            this.report.report.totalCash=report.totalCash
          }
          if(report.sold){
            this.report.report.total=report.sold
          }
      this.loading=false
      }).catch(() => {
        this.loading=false;
        });
      },
       getmyitem() {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_item','variable':'item'}, { root: true }).then(() => {
        this.item_group=this.$store.getters['admin/Getitem']
      })
    },
    }
}
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
.polling_message {
  color: white;
  float: left;
  margin-right: 2%;
}

.view_port {
  background-color: black;
  height: 25px;
  width: 100%;
  overflow: hidden;
}

.cylon_eye {

  -webkit-animation: 1s linear 0s  move_eye;
          animation: 1s linear 0s   move_eye;
}

@-webkit-keyframes move_eye { from { margin-left: -20%; } to { margin-left: 100%; }  }
        @keyframes move_eye { from { margin-left: -20%; } to { margin-left: 100%; }  }
.main{
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.header-sub{
  padding-top:5px;
  font-family: sans-serif;
  font-style: italic;
  font-size: 1.3em;
  letter-spacing: 1px;
}
.header{
  width: 100%;
  padding:30px;
  background: -webkit-gradient(linear, left top, right top, from(#e59811), color-stop(150%, #976e27)) !important;
        background: linear-gradient(to right, #e59811, #976e27 70%) !important;
}
.report-card-4{
   -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#5d5aa8), color-stop(99%, #1a174d )) !important;
        background: linear-gradient(to right, #5d5aa8, #1a174d  99%) !important;
}
.report-card-5{
   -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#5d5aa8), color-stop(99%, #5f5e63 )) !important;
        background: linear-gradient(to right, #5d5aa8, #5f5e63  99%) !important;
}
.report-card-6{
   -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#345031), color-stop(99%, #5f5e63 )) !important;
        background: linear-gradient(to right, #345031, #5f5e63  99%) !important;
}
.report-card-7{
   -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#c21279), color-stop(99%, #b6a6f7 )) !important;
        background: linear-gradient(to right, #c21279, #b6a6f7  99%) !important;
}
.report-card-2{
   -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#8b2530), color-stop(99%, #e55768 )) !important;
        background: linear-gradient(to right, #8b2530, #e55768  99%) !important;
}
.report-card-3{
   -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#956c24), color-stop(99%, #fda100 )) !important;
        background: linear-gradient(to right, #956c24, #fda100  99%) !important;
}
.report-card-1{
   -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#348897), color-stop(99%, #71b4b5 )) !important;
        background: linear-gradient(to right, #348897, #71b4b5  99%) !important;
}
.report-card {
  -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#145751), color-stop(99%, #35a196 )) !important;
        background: linear-gradient(to right, #145751, #35a196  99%) !important;
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
select {
  height: 40px;
  border: 0.5px solid #ccc;
  border-radius: 5px;
  background-color:#0000;
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
</style>