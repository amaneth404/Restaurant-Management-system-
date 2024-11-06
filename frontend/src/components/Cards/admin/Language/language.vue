<template>
   <div>
    <b-row>
      <b-col md="12">
        <b-card
        no-body
          class="overflow-auto card"
          style="background-color:rgb(255,255,255);padding:0;"
        >
          <b-card-body >
            <div style="display: flex; justify-content: space-between;margin-bottom:20px;padding:20px;">
              <strong
                class="float-left font-weight-black font-monospace"
                style="font-size: 1.3em;color:black;"
                >All Languages</strong
              >
              <b-button
                class="btn  bg-gradient-danger" 
                style="padding:0;"
                v-b-modal="'post-update-modal-language'"
                @click="resetform()"
                >
                <div class="top-1"></div>
                <span style="padding-left:10px;padding-right:10px;"><i class="fas fa-gamepad" style="margin-right: 10px"></i
                >Add Language</span>
                <div class="top-2"></div>
                </b-button
              >
            </div>
             </b-card-body>
          <div >
          <datatable
            :columns="columns"
            :data="languages"
            class="table text-nowrap"
            style="text-align: center;color:black;"
          >
            <template slot-scope="{row,index}">
              <tr>
                <td style="vertical-align: middle">{{ index + 1 }}</td>
                <td style="vertical-align: middle">{{ row.title }}</td>
                <td style="vertical-align: middle">{{row.code}}</td>
                <td style="vertical-align: middle">{{ row.created_at|myDate }}</td>
                <td>
                  <b-dropdown>
                    <template v-slot:button-content >
                      <i class="fas fa-ellipsis-v" style="color:grey;"></i>
                             </template>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;" v-b-modal="'post-update-modal-language'" @click='Editlanguage(row,index)'><i class="fa fa-edit" style="color:grey;margin-right:10px;"></i>Edit</span>
                    </b-dropdown-item>
                    <b-dropdown-item>
                  <span type='button'  style="width:100%;background:transparent;color:black;border:0;text-aling:left;"  @click='deletelanguage(row.id,index)'><i class="fas fa-trash-alt" style="color:grey;margin-right:10px;"></i> Delete</span>
                    </b-dropdown-item>
                  </b-dropdown>
                </td>
              </tr>
            </template>
          </datatable>
          </div>
        </b-card>
      </b-col>
    </b-row>
    <b-modal :id="'post-update-modal-language'" :size="'md'">
          <div style="padding-left:20px;padding-top:15px;padding-bottom:5px;border-bottom:1px solid rgba(214,214,214);font-size:1.3em;">{{display?'Update Language':'Add Language'}} <span><i class="fas fa-times" style="float:right;margin-right:20px;margin-top:5px;color:grey;" @click="closemoadl('post-update-modal-language')"></i></span></div>
      <form
         style="padding:30px;padding-top:15px;margin-bottom:35px;"
        @submit.prevent="display ? update() : create()"
      >
        <div class="form-group row">
          <label>language</label>
          <input
          required
            v-model="language.title"
            name="title"
            placeholder="title"
            class="form-control"
            :class="{ 'is-invalid': language.errors.has('title') }"
          />
          <has-error :form="language" field="title"></has-error>
        </div>
        <div class="form-group row">
          <label>Code</label>
          <input
          required
            v-model="language.code"
            name="code"
            placeholder="code"
            class="form-control"
            :class="{ 'is-invalid': language.errors.has('code') }"
          />
          <has-error :form="language" field="code"></has-error>
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
      updateindex:-1,
      columns: [
        { label: "#" },
        { label: "Language", field: "title" },
        {
          label: "Code",
          field: "code"
        },
         { label: "Created At", field: "created_at" },
        { label: "Manage" },
      ],
      languages: [],
      display: false,
      url: "",
      language: new window.Form({
        title: "",
        code: "",
        id: "",
      }),
    };
  },
  beforeMount() {
    this.getmylanguage()
  },
  methods: {
    closemoadl(val){
      this.$bvModal.hide(val)
    },
    resetform() {
      this.language.reset();
      this.url = "";
      this.display=false
      this.updateindex=-1
    },
    Editlanguage(language,index) {
      this.language.fill(language);
      this.display = true;
      this.updateindex=index
    },
    getmylanguage() {
      this.$store.dispatch('admin/AdminController',{'data':{},'method':'get','api':'admin_get_lang','variable':'lang'}, { root: true }).then(() => {
        this.languages=this.$store.getters['admin/Getlanguage']
        console.log(this.languages)
      })
    },
    deletelanguage(id,index) {
      window.Swal.fire({
            title: 'are you sure',
            text: 'you want to delete this language',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.value) {
              this.$store.dispatch('admin/DeleteLanguage', {id}, { root: true }).then(() => {
         window.Toast.fire({
            icon: "success",
            title:'language deleted successfuly',
          });
        this.languages.splice(index,1)
      })
            }
     });
    },
    update() {
      var id=this.language.id
     var form =window.FormConvert(this.language);
      this.$store
        .dispatch('admin/AdminController',{'data':form,'method':'put','api':'admin_update_lang','variable':'lang','params':id}, { root: true })
        .then(() => {
          var data = this.$store.getters["admin/Getlanguage"];
          this.languages[this.updateindex].id=data.id
          this.languages[this.updateindex].title=data.title
          this.languages[this.updateindex].type=data.type
          this.languages[this.updateindex].created_at=data.created_at
          this.display = false;
          this.$bvModal.hide("post-update-modal-language");
        })
        .catch((err) => {
          this.language.errors.errors = err;
        });
    },
    create() {
      var form =window.FormConvert(this.language);
      this.$store.dispatch('admin/AdminController',{'data':form,'method':'post','api':'admin_create_lang','variable':'lang'}, { root: true }).then(() => {
          this.languages.unshift(this.$store.getters["admin/Getlanguage"]);
          this.display = false;
          this.$bvModal.hide("post-update-modal-language");
        })
        .catch((err) => {
          this.language.errors.errors = err;
        });
    },
  },
};
</script>
<style scoped>
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
    background-color:#0000;
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
</style>