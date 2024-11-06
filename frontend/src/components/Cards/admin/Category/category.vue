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
                >All Categorys</strong
              >
              <div>
                <b-input-group style="margin-top:3px;">
                      <template v-slot:prepend>
                        <i class="fas fa-search" style="color:white;margin-top:6px;background:grey;height:35px;margin-top:-3px;padding:10px;border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;"></i>
                      </template>
                                  <input
                                  @keyup="FilterCategory"
                                  v-model="form.search"
                                    class="form-control search-controller"
                                    name="search"
                                    placeholder="search any category ..."
                                    style="box-shadow:none;background-color:rgb(255,255,255);height:35px;margin-top:-3px;color:black;"
                                  >
                   </b-input-group>
                  <!-- <input type="text" class="form-control" style="border-radius:10px;border:1px solid transparent;padding:5px;margin-bottom:50px;" placeholder="Search ..." v-model="form.username" @keyup="FilterUser"> -->
                </div>
              <b-button
                class="btn  bg-gradient-danger" 
                style="padding:0;"
                v-b-modal="'post-update-modal-category'"
                @click="resetform()"
                >
                <div class="top-1"></div>
                <span style="padding-left:10px;padding-right:10px;"><i class="fas fa-gamepad" style="margin-right: 10px"></i
                >Add Category</span>
                <div class="top-2"></div>
                </b-button
              >
            </div>
             </div>
             <div class="panel-body table-responsive">
          <datatable
            :columns="columns"
            :data="categorys"
            class="table text-nowrap table-striped"
            style="text-align:center;color:black;"
          >
            <template slot-scope="{row,index}">
              <tr>
                <td style="vertical-align: middle">{{ index + 1 }}</td>
                <td style="vertical-align: middle">{{ row.title }}</td>
                <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
                <td style="vertical-align: middle">
                  <b-dropdown>
                    <template v-slot:button-content >
                      <i class="fas fa-ellipsis-v" style="color:grey;"></i>
                             </template>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-category'" @click='Editcategory(row,index)'><i class="fa fa-edit" style="color:grey;margin-right:10px;"></i>Edit</span>
                    </b-dropdown-item>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='deletecategory(row.id,index)'><i class="fas fa-edit-alt" style="color:grey;margin-right:10px;"></i> Delete</span>
                    </b-dropdown-item>
                  </b-dropdown>
                </td>
              </tr>
            </template>
          </datatable>
          </div>
        </div>
        </b-card>
      </b-col>
    </b-row>
    <b-modal :id="'post-update-modal-category'" :size="'md'">
          <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">{{display?'Update Category':'Add Category'}} <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-category')"></i></span></div>
      <form
         style="padding:30px;padding-top:15px;margin-bottom:35px;"
        @submit.prevent="display ? update() : create()"
      >
        <div class="form-group row">
          <label>category</label>
          <input
          required
            v-model="category.title"
            name="title"
            placeholder="title"
            class="form-control"
            :class="{ 'is-invalid': category.errors.has('title') }"
          />
          <has-error :form="category" field="title"></has-error>
        </div>
        <div class="form-group row">
          <label>attachment</label>
          <input
          
          @change="attachmentprocessor"
            name="attachment"
            type="file"
            placeholder="attachment"
            class="form-control"
            :class="{ 'is-invalid': category.errors.has('attachment') }"
          />
          <div style="display: flex;align-items: center;justify-content: center;width:100%;margin-top:5px;">
          <img :src="url" v-if='url' alt="" style="width:100px;">
          </div>
          <has-error :form="category" field="attachment"></has-error>
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
        { label: "Category", field: "title" },
         { label: "Created At", field: "created_at" },
        { label: "Manage" },
      ],
      categorys: [],
      display: false,
      url: "",
      title:'',
      fee:'',
      code:'',
      selected_titles:[],
      isedit:-1,
      category: new window.Form({
        title: "",
        attachment:"",
        id: "",
      }),
    };
  },
  beforeMount() {
    this.getmycategory()
  },
  methods: {
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
       this.$store.dispatch('admin/AdminController',{'data':{search:this.form.search},'method':'post','api':'admin_search_category','variable':'category'}, { root: true }).then(() => {
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
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_category','variable':'category'}, { root: true }).then(() => {
        this.categorys=this.$store.getters['admin/Getcategory']
      })
    },
    deletecategory(id,index) {
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
              this.$store.dispatch('admin/AdminController',{'data':{},'method':'delete','api':'admin_delete_category','variable':'status','params':id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'category deleted successfuly',
          });
        this.categorys.splice(index,1)
      }).catch(()=>{
         window.Toast.fire({
            icon: "error",
            title:"category can't be deleted !!",
          });
      })
            }
     });
    },
    update() {
     var form =window.VFToFD(this.category);
      console.log(form)

      this.$store
        .dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_update_category','variable':'category'}, { root: true })
        .then(() => {
          var data = this.$store.getters["admin/Getcategory"];
          this.categorys[this.updateindex].id=data.id
          this.categorys[this.updateindex].title=data.title
          this.categorys[this.updateindex].attachment=data.attachment
          this.categorys[this.updateindex].created_at=data.created_at
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
    create() {
      var form =window.VFToFD(this.category);
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_create_category','variable':'category'}, { root: true }).then(() => {
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