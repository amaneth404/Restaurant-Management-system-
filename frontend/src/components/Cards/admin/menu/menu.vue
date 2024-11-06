<template>
  <div style="padding-left:1%;">
    <h4 style="text-align: left;"><strong>Manage Menu</strong></h4>
   <p style="text-align: left;">Admin/Manage Menu</p>
   <b-row style="margin-top:40px;">
    <b-col md="3">
      <div style="margin-left:-13px;">
      <h5 style="text-align: left;color:#5c5c5c9f;font-size: 1em;;">choose</h5>
      <h4 style="text-align: left;"><strong>Category</strong></h4>
    </div>
      <b-row style="padding-left:0px;">
        <b-col md="12">
          <b-card no-body class="cate" style="border:0;height:63px;padding:5px;margin-bottom: 13px;" v-for="cate,index in categorys" :key="cate.id" @click="getMenu(cate,index)" :style="cate.id==active_cate?'background:#F8C655':''">
            <b-row no-gutters>
              <b-col md="auto" style="padding-left: 20px;margin-top:-5px;width:20%;">
                <i class="mdi mdi-food" style="font-size: 2.5em;padding-bottom:0px;" :style="cate.id==active_cate?'color:white':''"></i>
              </b-col>
              <b-col md="auto" style="padding-top:3px;padding-left:5px;width:80%;">
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
          <b-card  no-body style="border:0;padding:5px;" class="menu card-icon" :style="menu.id==active_menu?'background:#F8C655':''">
            <h5 style="text-align: left;padding-left:10px;text-overflow: ellipsis;
  white-space: nowrap;
  overflow: hidden;
  display: inline-block;width:70%;"><strong>{{ menu.name }}</strong></h5>
            <p style="text-align: left;padding-left:10px;">{{ menu.price }} birr</p>
          </b-card>
        </b-col>
      </b-row>
    </b-col>
    <b-col md="4">
      <b-row >
        <b-col md="12">
          <b-card no-body style="padding:30px;border:0;background-color: #F7F7F9;border-radius: 10px;box-shadow: 1px 2px 5px grey;" >
          <h4 style="text-align: left;">Add New Menu Category</h4>
          <p style="text-align: left;">Fill the from to add new food category</p>
          <form
        @submit.prevent="display ? update() : create()"
      >
        <div class="form-group row">
          <label>category</label>
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
        <div class="form-group row" v-if="false">
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
          <b-button type="submit" class="btn btn-primary" style="margin-top:5px;margin-bottom:10px;width:100%;"> {{display?'Update Category':'+ ADD Category'}}</b-button>
          <div style="display: inline;font-size: 1.3em;" v-if="display">
          <i class="mdi mdi-refresh" style="color:green;margin-right: 10px;" @click="refreshCategory"></i>
          <i class="mdi mdi-trash-can" style="color:red;" @click="deletecategory"></i>
          </div>
      </form>
          </b-card>
          <b-card no-body style="padding:30px;border:0;background-color: #F7F7F9;border-radius: 10px;margin-top:20px;box-shadow: 1px 2px 5px grey;">
          <h4 style="text-align: left;">Add New Menu</h4>
          <p style="text-align: left;">add new food menu</p>
          <form
        @submit.prevent="display_menu ? updateMenu() : createMenu()"
      >
        <div class="form-group row">
          <label>Menu</label>
          <input
          required
            v-model="menu.name"
            name="name"
            placeholder="name"
            class="form-control"
            :class="{ 'is-invalid': menu.errors.has('name') }"
          />
          <has-error :form="menu" field="name"></has-error>
        </div>
        <div class="form-group row">
          <label>Price</label>
          <input
          required
            v-model="menu.price"
            name="price"
            placeholder="price"
            class="form-control"
            :class="{ 'is-invalid': menu.errors.has('price') }"
          />
          <has-error :form="menu" field="price"></has-error>
        </div>
        <div class="form-group row">
          <label>Cost</label>
          <input
            v-model="menu.cost"
            name="cost"
            type="text"
            placeholder="cost"
            class="form-control"
            :class="{ 'is-invalid': menu.errors.has('cost') }"
          />
          <has-error :form="menu" field="cost"></has-error>
        </div>
        <div class="form-group row">
          <label>Time</label>
          <input
            v-model="menu.time"
            name="time"
            type="number"
            placeholder="time"
            class="form-control"
            :class="{ 'is-invalid': menu.errors.has('time') }"
          />
          <has-error :form="menu" field="time"></has-error>
        </div>
        <div class="form-group row">
          <label style="margin-right:10px;">Connect </label>
          <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="customSwitch1" :checked="menu.from_data==1?false:true" @change="menu.from_data=!menu.from_data">
            <label class="custom-control-label" for="customSwitch1">From Store</label>
          </div>
          <has-error :form="menu" field="price"></has-error>
        </div>
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="customSwitch12" :checked="menu.tokichen==0?false:true" v-model="menu.tokichen">
          <label class="custom-control-label" for="customSwitch12">To kichen</label>
        </div>
          <b-button type="submit" class="btn btn-primary" style="margin-top:5px;margin-bottom:10px;width:100%;" v-if="active_cate!=-1"> {{display_menu?'Update Menu':'+ ADD Menu'}}</b-button>
          <div style="display: inline;font-size: 1.3em;" v-if="display_menu">
          <i class="mdi mdi-refresh" style="color:green;margin-right: 10px;" @click="refreshmenu"></i>
          <i class="mdi mdi-trash-can" style="color:red;" @click="deletemenu"></i>
          </div>
      </form>
          </b-card>
        </b-col>
      </b-row>
    </b-col>
   </b-row>
  </div>
</template>

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

.card:hover {
  background-color: #e2e6ea; /* Hover background color */
  transform: scale(1.05); /* Slightly enlarge the card on hover */
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
<script>
export default {
  data() {
    return {
      search:'',
      form:new window.Form({
      search:''
            }),
      updateindex:-1,
      columns: [
        { label: "#" },
        { label: "Category", field: "title" },
        { label: "Image", field: "image" },
         { label: "Created At", field: "created_at" },
        { label: "Manage" },
      ],
      filtered_data:[],
      active_cate:-1,
      active_menu:-1,
      active_index:-1,
      active_menu_index:-1,
      categorys: [],
      menus:[],
      display: false,
      display_menu:false,
      url: "",
      title:'',
      fee:'',
      code:'',
      selected_titles:[],
      isedit:-1,
      category: new window.Form({
        name: "",
        attachment:"",
        from:1,
        id: "",
      }),
      menu: new window.Form({
        name: "",
        icon:"",
        cost:0,
        from_data:1,
        time:0,
        tokichen:0,
        cate_id:"",
        price:0,
        id: "",
      }),
    };
  },
  beforeMount() {
    this.getmycategory()
  },
  methods: {
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
    attachmentprocessor (e) {
      var photos = e.target.files[0]
      var file = photos
      var limit = 1024 * 2
      if (file.size <= limit) {
        this.category.attachment = file
        this.url = URL.createObjectURL(file)
      }
    },
    getMenu(cate,index){
      this.active_cate=cate.id
      this.category.fill(cate)
      this.display=true
      this.active_index=index
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_menu','variable':'category','params':cate.id}, { root: true }).then(() => {
        this.menus=this.$store.getters['admin/Getcategory']
      })
    },
    SelectedMenu(menu,index){
      this.menu.reset();
      this.menu.from_data=1
      this.active_menu=menu.id
      this.menu.fill(menu)
      if(!menu.time){
        this.menu.time=0
      }
      this.display_menu=true
      this.active_menu_index=index
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
      this.menu.from_data=this.menu.from_data?1:0
      this.menu.tokichen=this.menu.tokichen?1:0
      this.menu.cate_id=this.active_cate
     var form =window.VFToFD(this.menu);
      this.$store
        .dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_update_menu','variable':'category'}, { root: true })
        .then(() => {
          var data = this.$store.getters["admin/Getcategory"];
          this.menus[this.active_menu_index].id=data.id
          this.menus[this.active_menu_index].cost=data.cost
          this.menus[this.active_menu_index].name=data.name
          this.menus[this.active_menu_index].time=data.time
          this.menus[this.active_menu_index].icon=data.icon
          this.menus[this.active_menu_index].from_data=data.from_data
          this.menus[this.active_menu_index].tokichen=data.tokichen
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
     console.log(form)
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
          this.category.reset()
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
      this.menu.from_data=this.menu.from_data?1:0
      this.menu.tokichen=this.menu.tokichen?1:0
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
          this.category.reset()
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