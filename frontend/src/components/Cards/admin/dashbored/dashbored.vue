<template>
  <div style="color:black;">
    <div v-if="$store.getters.role=='Admin'">
      <b-button
                class="btn btn-success btn-sm" 
                style="margin-left:10px;float: right;"
                @click="Reported">
                <div class="top-1"></div>
                <span style="padding-left:10px;padding-right:10px;">
 Reported <i class="fas fa-file-signature"></i>
     </span>
                <div class="top-2"></div>
                </b-button> 
      <div class="custom-control custom-switch" style="float: right;">
  <input type="checkbox" class="custom-control-input" id="customSwitch1" @click="casher=!casher">
  <label class="custom-control-label" for="customSwitch1">{{casher?'Casher Dashboard':'Store Dashboard'}}</label>
</div>
    </div>
  <div style="padding:10px;" v-if="(casher||$store.getters.role=='Casher')&&$store.getters.role!='Store'&&$store.getters.role!='Chef'">
    <h5 style="text-align: left;"><strong>Dashboard</strong></h5>
    <b-row>
      <b-col md="12" style="padding-left: 10px;">
        <h5 style="color:black;text-align:left;"><b>Casher Report</b></h5>
        <div>
        <div style="display:flex;justify-content: start;margin-bottom:20px;overflow-x: auto;color:black;" v-if='$store.getters.role=="Admin"'>
                 <div class="custom-control custom-checkbox">
  <input type="checkbox" class="custom-control-input" id="customCheck1" v-model="isreported" @change="getREportFolder">
  <label class="custom-control-label" for="customCheck1">do you want to filter including the Reported reportes ?</label>
</div>
               </div>
               
               <div v-if='isreported&&$store.getters.role=="Admin"'>
                 <div style="display:flex;justify-content: start;margin-bottom:20px;overflow-x: auto;color:black;">
                 <select name="" id=""  v-model="reported_date" @change="GetReportInit" class="form-control">
                   <option value="">Select Reported Date</option>
                   <option :value="report.date" v-for="report in report_folder" :key="report.id">{{report.created_at|myDate}}</option>
                 </select>
                 </div>
               </div>
              <p style="color:black;text-align:left;" v-if="$store.getters.role=='Admin'&&!isreported">Store Report With preference</p>
              <div  v-if="$store.getters.role=='Admin'&&!isreported">
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
<div v-if="!isreported">
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
                  <option value="1">Paid Orders</option>
                  <option value="2">Flagged Orders</option>
                  <option value="3">Delivery Orders</option>
                  <option value="4">Flaged Delivery</option>
                  <option value="5">Export Expense</option>
                </select>
                <export-excel :data="json_data_payed" :fields="json_fields_payed" workseet="My worksheet" name='PayedOrders.xls' type="csv" class="btn" id="export-log-to-xl" v-show="false">
     </export-excel>
     <export-excel :data="json_data_payed" :fields="json_fields_payed" workseet="My worksheet" name='FlagedOrders.xls' type="csv" class="btn" id="export-log-flagged-to-xl" v-show="false">
     </export-excel>
     <export-excel :data="json_data_payed" :fields="json_fields_payed_delivery" workseet="My worksheet" name='deliveryorder.xls' type="csv" class="btn" id="export-log-delivery-to-xl" v-show="false">
     </export-excel>
     <export-excel :data="json_data_payed" :fields="json_fields_payed_delivery" workseet="My worksheet" name='flageddelivery.xls' type="csv" class="btn" id="export-flaged-delivery-to-xl" v-show="false">
     </export-excel>

     <export-excel :data="json_data_payed" :fields="json_expense" workseet="My worksheet" name='Expense.xls' type="csv" class="btn" id="export-expense-to-xl" v-show="false">
    </export-excel>
          
     </div>
</div>
</div>
         <b-row>
          <b-col md="3" style="margin-bottom: 20px;">
            <b-card style="border:0;border-radius: 10px;background-color: #faaeaf34;">
              <b-row>
                <b-col md="3">
                  <i class="mdi mdi-food" style="font-size: 1.5em;"></i>
                </b-col>
                <b-col md="9">
                  <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Bank</strong></div>
                  <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.bank}}</strong></h5>
                  <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">total money <span style="color:blue;font-size:1.3em;"  v-b-modal="'post-update-modal-bank'" @click="loadMoneyBank()">View</span></p>
                </b-col>
              </b-row>
            </b-card>
          </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color: #faaeaf34;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-food" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Orders</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.order}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">number of orders</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#eaeaf7 ;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-flag" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Void Delivery</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.dflagged}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">number of orders</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#eaeaf7 ;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-flag" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total  Delivery</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.dorder}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">number of orders</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#eaeaf7 ;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-flag" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Delivery Fee</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.dfee}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">Money</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#eaeaf7 ;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-flag" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Delivery Void Fee</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.dfee_f}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">Money</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#eaeaf7 ;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-flag" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Delivery Cash</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.dcash}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">Money</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#eaeaf7 ;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-flag" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Regular Cash</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.cash}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">Money</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#eaeaf7 ;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-flag" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Delivery Void Cash</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.dflagged_cash}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">Money</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#eaeaf7 ;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-flag" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Void Orders</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.flagged}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">number of orders</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#f1fcf0c4;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Cash</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.cash+dashbored.dcash-dashbored.gift}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">amount of money</p>
                  </b-col>
                </b-row>
              </b-card>
             
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#f1fcf0c4;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Cash Registerd</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.totalcash}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">amount of money</p>
                  </b-col>
                </b-row>
              </b-card>
             
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;">
              <b-card style="border:0;border-radius: 10px;background-color:#e8f8e7 ;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database-remove" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Void Cash</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.flagged_cash*1+dashbored.dflagged_cash*1}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">amount of money</p>
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;" v-if="$store.getters.role=='Admin'">
              <b-card style="border:0;border-radius: 10px;background-color:#f1fcf0c4;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database-remove" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Expense</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.expense}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">money</p>
                  </b-col>
                </b-row>
              </b-card>
             
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;" v-if="$store.getters.role=='Admin'">
              <b-card style="border:0;border-radius: 10px;background-color:#f1fcf0c4;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database-remove" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Salary</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.salary}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">money</p>
                  </b-col>
                </b-row>
              </b-card>
             
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;" v-if="$store.getters.role=='Admin'">
              <b-card style="border:0;border-radius: 10px;background-color:#f1fcf0c4;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database-remove" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Gift</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.gift}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">money</p>
                  </b-col>
                </b-row>
              </b-card>
             
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;" v-if="$store.getters.role=='Admin'">
              <b-card style="border:0;border-radius: 10px;background-color:#f1fcf0c4;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database-remove" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Credit</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.credit}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">money</p>
                  </b-col>
                </b-row>
              </b-card>
             
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;" v-if="$store.getters.role=='Admin'">
              <b-card style="border:0;border-radius: 10px;background-color:#f1fcf0c4;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database-remove" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total User Credit Filtered</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.credit_report}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">money</p>
                  </b-col>
                </b-row>
              </b-card>
             
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;" v-if="$store.getters.role=='Admin'">
              <b-card style="border:0;border-radius: 10px;background-color:#f1fcf0c4;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database-remove" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total User Credit</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.user_credit}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">money</p>
                  </b-col>
                </b-row>
              </b-card>
             
            </b-col>
            <b-col md="3" style="margin-bottom: 20px;" v-if="$store.getters.role=='Admin'">
              <b-card style="border:0;border-radius: 10px;background-color:#f1fcf0c4;">
                <b-row>
                  <b-col md="3">
                    <i class="mdi mdi-database-remove" style="font-size: 1.5em;"></i>
                  </b-col>
                  <b-col md="9">
                    <div style="text-align: left;color:black;font-size:0.7em;"><strong>Total Profit</strong></div>
                    <h5 style="text-align: left;color:black;font-size:1em;"><strong>{{dashbored.dcash*1+dashbored.cash*1-dashbored.expense*1-dashbored.salary*1-dashbored.credit*1-dashbored.gift*1-dashbored.credit_report*1}}</strong></h5>
                    <p style="text-align: left;font-size: 0.6em;margin-bottom:-10px;">money</p>
                  </b-col>
                </b-row>
              </b-card>
             
            </b-col>
         </b-row>
      </b-col>
      <b-col md="12" style="padding-left: 50px;" v-if="$store.getters.role=='Admin'">
        <div style="color:black;font-weight: bold;font-size:1.2em;text-align: left;;">
                <strong>Total Revenu</strong><br>
                Bar graph report income
              </div>
            <div class="card" style="border:1px;width:100%;">
        <div class="canvas-holder half" style="padding:10px;">
	<canvas style="width: 100%;" id="can-1"></canvas>
</div>
</div>
      </b-col>
    </b-row>
    <div v-if="$store.getters.role=='Admin'">
      <h3>Detailed Report</h3>
      <div class="panel-body table-responsive">
        <datatable
          :columns="columns_report"
          :data="dashbored.detailed_report"
          class="table text-nowrap table-striped"
          style="text-align:center;color:black;"
        >
          <template slot-scope="{row,index}">
            <tr>
              <td style="vertical-align: middle">{{ index + 1 }}</td>
              <td style="vertical-align: middle">{{ row.name }}</td>
              <td style="vertical-align: middle">{{ getorders(row.menu) }}</td>
              <td style="vertical-align: middle">{{ getMoney(row.menu) }}</td>
              <td style="vertical-align: middle">
                <b-dropdown>
                  <template v-slot:button-content >
                    <i class="fas fa-ellipsis-v" style="color:grey;"></i>
                           </template>
                  <b-dropdown-item>
                <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-orders'" @click='viewDetaile(row,index)'><i class="fa fa-edit" style="color:grey;margin-right:10px;"></i>Detail</span>
                  </b-dropdown-item>
                 
                </b-dropdown>
              </td>
            </tr>
          </template>
        </datatable>
        </div>
    </div>
  </div>
  <div style="padding:20px;width:100%;" v-if="(!casher||$store.getters.role=='Store')&&$store.getters.role!='Casher'&&$store.getters.role!='Chef'">
    <StoreDashbored/>
  </div>
  <div v-if="$store.getters.role=='Chef'">

  </div>
  <div class="loading " v-if='loading'>
      <div class="d-flex justify-content-center">
     <div  type="button" disabled style="margin-top:20%;">
  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  Loading...
</div>
</div>
    </div>

    <b-modal :id="'post-update-modal-bank'" :size="'md'">
      <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">Banks<span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-bank')"></i></span></div>
      <div class="panel-body table-responsive">
        <datatable
          :columns="columns"
          :data="banks"
          class="table text-nowrap table-striped"
          style="text-align:center;color:white;"
        >
          <template slot-scope="{row,index}">
            <tr>
              <td style="vertical-align: middle">{{ index + 1 }}</td>
              <td style="vertical-align: middle">{{ row.bank.name }}</td>
              <td style="vertical-align: middle">{{ row.price }}</td>
            </tr>
          </template>
        </datatable>
        </div>
</b-modal>
    <b-modal :id="'post-update-modal-orders'" :size="'lg'">
      <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">Orderes<span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-orders')"></i></span></div>
      <div class="panel-body table-responsive">
        <datatable
          :columns="columns_report_order"
          :data="detail_holder"
          class="table text-nowrap table-striped"
          style="text-align:center;color:white;"
        >
          <template slot-scope="{row,index}">
            <tr>
              <td style="vertical-align: middle">{{ index + 1 }}</td>
              <td style="vertical-align: middle">{{ row.name }}</td>
              <td style="vertical-align: middle">{{ row.casher.length }}</td>
              <td style="vertical-align: middle">{{ row.casher.length*row.cost }}</td>
              <td style="vertical-align: middle">{{ getMoney([row]) }}</td>
              <td style="vertical-align: middle">{{ getMoney([row])-row.casher.length*row.cost }}</td>
            </tr>
          </template>
        </datatable>
        </div>
</b-modal>


</div>
</template>
<script>
import Chart from 'chart.js/auto'
import StoreDashbored from './dashbored copy.vue'
export default {
  components:{StoreDashbored},
  data() {
    return {
      columns_report: [
        { label: "#" },
        { label: "Name", field: "name" },
        { label: "Orders", field: "total" },
        { label: "Money", field: "money" },
        { label: "Manage", field: "manage" }
      ],
      columns_report_order: [
        { label: "#" },
        { label: "Name", field: "name" },
        { label: "Orders", field: "total" },
        { label: "Cost", field: "cost" },
        { label: "Gross", field: "money" },
        { label: "Net", field: "net" }
      ],
      columns: [
        { label: "#" },
        { label: "Bank", field: "name" },
        { label: "Money", field: "money" }
      ],
json_fields_payed:{
        'Table':'table_name',
        'Waiter':{
                callback: (value) => {
                    return ` ${value.waiter?(value.waiter.first_name+' '+value.waiter.middle_name+' '+value.waiter.last_name):''}`;
                }
            },
        'Price':'price',
        'Orders':'orders',
      },
      json_expense:{
        'Description':{
                callback: (value) => {
                    return ` ${value.expense?(value.expense.title):''}`;
                }
            },
        'Title':'title',
        'Amount':'amount',
        'created_at':'created_at',
      },
      json_fields_payed_delivery:{
        'Table':'table_name',
        'waiter':{
                callback: (value) => {
                    return ` ${value.waiter?(value.waiter.first_name+' '+value.waiter.middle_name+' '+value.waiter.last_name):''}`;
                }
            },
          'Fee':'fee',
          'Phone Number':'phone_number',
        'Price':'price',
        'Orders':'orders',
      },
      json_data_payed:[],
      json_meta:[
        [
          {
            'key':'charset',
            'value':'utf-8'
          }
        ]
      ],
      loading:false,
      reported_date:'',
      filename:'',
      banks:[],
      dashbored:{
        gift:0,
        order:0,
        flagged:0,
        bank:0,
        cash:0,
        flagged_cash:0,
        dorder:0,
        dflagged:0,
        dcash:0,
        dflagged_cash:0,
        dfee:0,
        dfee_f:0,
        salary:0,
        credit:0,
        totalcash:0,
        user_credit:0,
        credit_report:0,
        expense:0,
        detailed_report:[],
      },
      detail_holder:[],
      isreported:false,
      report_folder:[],
      casher:true,
      casher_report:[],
      store_report:[],
      casher_graph:[],
      store_graph:[],
      cahrt_holder:null,
      from:'',
        to:'',
      dataset:{labels: [],
	datasets: [
		{
			label: "Revenu",
			fillColor: "#6f54a4",
			strokeColor: "#6f54a4",
			highlightFill: "#ea4a9b",
			highlightStroke: "#ea4a9b",
      backgroundColor:"#ea4a9b",
      borderColor:"#6f54a4",
			data: []
		}
	]},
          data1 : [
],
    };
  },
  beforeMount() {
  },
  mounted(){
  if(this.$store.getters.role!='Chef'){
    this.getgraph()
    this.GetReport(1)
  }
  },
  methods: {
    getorders(orders){
    console.log(orders,'aman')
      var holder=0
      orders.forEach(element => {
        holder+=element.casher.length*1
      });
      return holder
    },
    viewDetaile(row){
      this.detail_holder=[]
      row.menu.forEach(element => {
        if(element.casher.length)
      this.detail_holder.push(element)
      });
    },
    getMoney(menus){
      var total_money=0;
      menus.forEach(element => {
        element.casher.forEach(casher => {
          total_money+=casher.order_price*1;
        });
      });
      return total_money;
    },
    loadMoneyBank(){
      this.$store.dispatch('admin/AdminController',{'data':{from:this.from,to:this.to,isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date},'method':'post','api':'admin_filter_date_casher_report_bank','variable':'report','params': this.active}, { root: true }).then(() => {
        this.banks=this.$store.getters['admin/GetReport']
      })
    },
    closemoadl(val){
      this.$bvModal.hide(val)
    },
    GetReportprintInit(e){
        if(e.target.value==1){
          this.exportexpayed()
        }else if(e.target.value==2){
          this.exportexflaged()
        }else if(e.target.value==3){
            this.exportexpayedDelivery()
        }else if(e.target.value==4){
          this.exportexflagedDelivery()
        }else if(e.target.value==5){
          this.exportexExpense()
        }
    },
    exportexpayed(){
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_all_payed_orders','variable':'items'}, { root: true }).then(() => {
        this.json_data_payed=this.$store.getters['admin/Getitems']
        setTimeout(()=>{
           document.getElementById('export-log-to-xl').click()
        this.loading=false
         if(!this.json_data_payed.length){
           window.Toast.fire({
            icon: "warning",
            title:'Orders is empity !!',
          });
        }
        },2000) 
      }).catch(() => {
        this.loading=false;
        });
    },
    exportexflaged(){
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_all_flagged_orders','variable':'items'}, { root: true }).then(() => {
        this.json_data_payed=this.$store.getters['admin/Getitems']
        setTimeout(()=>{
           document.getElementById('export-log-flagged-to-xl').click()
        this.loading=false
         if(!this.json_data_payed.length){
           window.Toast.fire({
            icon: "warning",
            title:'Flagged Orders is empity !!',
          });
        }
        },2000) 
      })
    },
    exportexpayedDelivery(){
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_all_payed_Dorders','variable':'items'}, { root: true }).then(() => {
        this.json_data_payed=this.$store.getters['admin/Getitems']
        setTimeout(()=>{
           document.getElementById('export-log-delivery-to-xl').click()
        this.loading=false
         if(!this.json_data_payed.length){
           window.Toast.fire({
            icon: "warning",
            title:'Orders is empity !!',
          });
        }
        },2000) 
      }).catch(() => {
        this.loading=false;
        });
    },
    exportexflagedDelivery(){
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_all_flagged_Dorders','variable':'items'}, { root: true }).then(() => {
        this.json_data_payed=this.$store.getters['admin/Getitems']
        setTimeout(()=>{
           document.getElementById('export-flaged-delivery-to-xl').click()
        this.loading=false
         if(!this.json_data_payed.length){
           window.Toast.fire({
            icon: "warning",
            title:'Flagged Orders is empity !!',
          });
        }
        },2000) 
      })
    },
    exportexExpense(){
      this.loading=true
      this.$store.dispatch('admin/AdminController',{'data':{from:this.from,to:this.to,isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date},'method':'post','api':'admin_get_expense_export','variable':'items','params':this.active}, { root: true }).then(() => {
        this.json_data_payed=this.$store.getters['admin/Getitems']
        setTimeout(()=>{
           document.getElementById('export-expense-to-xl').click()
        this.loading=false
         if(!this.json_data_payed.length){
           window.Toast.fire({
            icon: "warning",
            title:'Flagged Orders is empity !!',
          });
        }
        },2000) 
      })
    },
    GetReportByDate(){
      this.loading=true
        this.$store.dispatch('admin/AdminController',{'data':{from:this.from,to:this.to,isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date},'method':'post','api':'admin_filter_date_casher_report','variable':'report'}, { root: true }).then(() => {
          this.report=this.$store.getters['admin/GetReport']
          var change_f=0;
          // var change=0
          var change_o=0;
          this.report[0].forEach(element => {
              if(element.flag==0){
                change_o=1
                this.dashbored.order=element.orders
                this.dashbored.cash=element.price
              }else{
                change_f=1
                this.dashbored.flagged=element.orders
                this.dashbored.flagged_cash=element.price
              }
              // change=1;
          });
          if(change_o==0){
            this.dashbored.order=0
            this.dashbored.cash=0
          }
          if(change_f==0){
            this.dashbored.flagged=0
            this.dashbored.flagged_cash=0
          }
          change_f=0;
          // var change=0
          change_o=0;
          this.report[1].forEach(element => {
              if(element.flag==0){
                change_o=1
                this.dashbored.dorder=element.orders
                this.dashbored.dcash=element.price
                this.dashbored.dfee=element.fee
              }else{
                change_f=1
                this.dashbored.dflagged=element.orders
                this.dashbored.dflagged_cash=element.price?element.price:0
                this.dashbored.dfee_f=element.fee
              }
              // change=1;
          });
          this.dashbored.expense=this.report[2]
          this.dashbored.salary=this.report[3]
          this.dashbored.credit=this.report[4]
          this.dashbored.user_credit=this.report[5]*1
          this.dashbored.bank=this.report[7].price*1
          this.dashbored.gift=this.report[6]*1
          this.dashbored.totalcash=this.report[8]*1
          this.dashbored.detailed_report=this.report[9]
          this.dashbored.credit_report=this.report[10]*1
          
          if(change_o==0){
            this.dashbored.dorder=0
            this.dashbored.dcash=0
            this.dashbored.dfee=0
          }
          if(change_f==0){
            this.dashbored.dflagged=0
            this.dashbored.dfee_f=0
          }
          console.log(this.dashbored)
      this.loading=false
      }).catch(() => {
        this.loading=false;
        });
      },
    getREportFolder(){
         this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_report_folder','variable':'report'}, { root: true }).then(() => {
          this.report_folder=this.$store.getters['admin/GetReport']
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
          default:
          this.GetReport(4);
        }
      },
      GetReport(id){
        this.active=id
      this.loading=true
        var url='admin_get_report_cahser'
        if(this.$store.getters.role=='Casher')
        url='users_get_report_casher'
        this.$store.dispatch('admin/AdminController',{'data':{isreported:this.isreported,ismain:this.ismain,reported_date:this.reported_date},'method':'post','api':url,'variable':'report','params':id}, { root: true }).then(() => {
          this.report=this.$store.getters['admin/GetReport']
          console.log(this.report)

          var change_f=0;
          // var change=0
          var change_o=0;
          this.report[0].forEach(element => {
              if(element.flag==0){
                change_o=1
                this.dashbored.order=element.orders
                this.dashbored.cash=element.price
              }else{
                change_f=1
                this.dashbored.flagged=element.orders
                this.dashbored.flagged_cash=element.price
              }
              // change=1;
          });
          if(change_o==0){
            this.dashbored.order=0
            this.dashbored.cash=0
          }
          if(change_f==0){
            this.dashbored.flagged=0
            this.dashbored.flagged_cash=0
          }
          change_f=0;
          // var change=0
          change_o=0;
          this.report[1].forEach(element => {
              if(element.flag==0){
                change_o=1
                this.dashbored.dorder=element.orders
                this.dashbored.dcash=element.price
                this.dashbored.dfee=element.fee
              }else{
                change_f=1
                this.dashbored.dflagged=element.orders
                this.dashbored.dflagged_cash=element.price
                this.dashbored.dfee_f=element.fee
              }
              // change=1;
          });
          this.dashbored.expense=this.report[2]
          this.dashbored.salary=this.report[3]
          this.dashbored.credit=this.report[4]
          this.dashbored.user_credit=this.report[5]*1
          this.dashbored.bank=this.report[7].price*1
          this.dashbored.gift=this.report[6]*1
          this.dashbored.totalcash=this.report[8]*1
          this.dashbored.detailed_report=this.report[9]
          this.dashbored.credit_report=this.report[10]*1

          
          if(change_o==0){
            this.dashbored.dorder=0
            this.dashbored.dcash=0
            this.dashbored.dfee=0
          }
          if(change_f==0){
            this.dashbored.dflagged=0
            this.dashbored.dflagged_cash=0
            this.dashbored.dfee_f=0
          }
         
      this.loading=false
      }).catch(() => {
        this.loading=false;
        });
      },
    Reported(){
        this.loading=true
         var api='admin_reported'
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':api,'variable':'status'}, { root: true }).then(() => {
         this.GetReport(1)
  this.loading=false
      }).catch(() => {
        this.loading=false;
        });
      },
      getgraph() {
    var url = 'admin_get_report_cahser_graph';
    if (this.$store.getters.role == 'Casher') {
        url = 'users_get_report_casher_graph';
    }

    // Fetch the data from the backend
    this.$store.dispatch('admin/AdminController', {
        'data': {
            isreported: this.isreported,
            ismain: this.ismain,
            reported_date: this.reported_date
        },
        'method': 'get',
        'api': url,
        'variable': 'report'
    }, { root: true }).then(() => {

        // Clear any existing labels or data to avoid appending old data
        this.dataset.labels = [];
        this.dataset.datasets = [
            {
                label: 'Gross Profit',
                backgroundColor: 'red', // Color for gross profit bars
                data: []
            },
            {
                label: 'Net Profit',
                backgroundColor: 'yellow', // Color for net profit bars
                data: []
            }
        ];

        // Get the data from the store
        var data = this.$store.getters['admin/GetReport'];

        // Loop through the returned data and populate the dataset
        for (var i = 0; i < data.length; i++) {
            this.dataset.labels.push(data[i].day); // Add the day as the label
            
            // Calculate gross profit (price - fee)
            var grossProfit = (data[i].price?data[i].price:0) - (data[i].fee?data[i].fee:0);
            this.dataset.datasets[0].data.push(grossProfit); // Add gross profit to the dataset

            // Calculate net profit (price - fee - expenses - salary - credit_money)
            var expenses = data[i].total_expense || 0; // Default to 0 if undefined
            var salary = data[i].total_salary || 0; // Default to 0 if undefined
            var credit_money = data[i].total_credit_money || 0; // Default to 0 if undefined
            var credit_money_total = data[i].total_credit || 0; // Default to 0 if undefined

            var netProfit = grossProfit - expenses - salary - credit_money-credit_money_total;
            this.dataset.datasets[1].data.push(netProfit); // Add net profit to the dataset

            // Log the calculated values for debugging
            console.log(`Day: ${data[i].day}, Gross Profit: ${grossProfit}, Net Profit: ${netProfit}`);
        }
        console.log()

        // Create the chart with the updated dataset
        var ctx = document.getElementById("can-1").getContext("2d");
        new Chart(ctx, {
            type: 'bar',
            data: this.dataset,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true // Ensure the y-axis starts at 0
                    }
                }
            }
        });
    });
}


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


