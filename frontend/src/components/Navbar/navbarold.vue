<template>
    <div class='main-navbar'>
      <Header/>
        <b-container fluid style='width:100%;height:113px;'>
                    <b-row no-gutters style="margin-top:-13px;margin-left:-3.5%;">
                           <b-col lg='6' md="12" style="margin-top:2.8%;margin-left:-10px;" class="disk-display">
                          <b-input-group>
                            <template v-slot:prepend>
                              <img src="../../assets/logo.png" class="img-fluid" style="margin-top:-50px;margin-right:10px;margin-left:50px;">
                               <div @click="activateDrop()" class="elevation-3" style="margin-right:20px;vertical-align: middle;font-size:1.3em;background-color:#fbcf00;color:white;padding-left:13px;padding-right:10px;padding-top:5px;border:0;border-radius:3px;">
                          <strong>Explore<i class="fas fa-angle-down" style="margin-left:10px;"></i></strong>
                        </div>
                            </template>
                                <template v-slot:append>
                                  <b-button class="search-btn" type="submit">
                                    <i class="fas fa-search"></i>
                                  </b-button>
                                </template>
                                <input
                                  class="form-control search-controller"
                                  name="search"
                                  placeholder="What do you want to learn?"
                                  style="box-shadow:none;background-color:white;height:35px;padding-top:10px;"
                                >
                              </b-input-group>
                        </b-col>
                        <b-col md='6'  style='margin-top:3%;'>
                            <div class='nav' style="float:right;">
                                <ul>
                                    <li style="margin-left:-10%;margin-right:7%;"><strong>Find Your new Career</strong><b-badge class="icon-badge"><strong>new</strong></b-badge></li>
                                    <li><strong>For Enterprise</strong></li>
                                    <li><strong>For Sutdents</strong></li>
                                    <li style="color:#fbcf00;margin-right:-40px;"><strong>Log In</strong></li>
                                    <li style="vertical-align: middle;padding:8px;padding-left:5px;padding-right:5px;background-color:#fbcf00;color:white;border:0;border-radius:3px;"><strong>Join For Free</strong></li>
                                </ul>
                            </div>
                        </b-col>
                    </b-row>
        </b-container>
    </div>
</template>
<script>
import Header from './navbarHeader'
export default {
  components:{Header},
  data () {
    return {
      }
    },
  methods: {
  }
}
</script>
<style scoped>
.icon-badge {
  margin-left: 5px;
  margin-bottom: 0px;
  margin-top:-8px;
  top:2;
  background:#fbcf00;
  position: fixed;
  /* border-radius: 50% !important; */
  padding: 0.15rem 0.32rem !important;
}
.search-btn{
  height:36px;
  margin-left:2px;
  background-color:#fbcf00;
  border-radius:0;
}
.nav-icon{
  margin-top:1px;
  font-size:1.5em;
  color:#66297b;
}
.nav ul{
    list-style-type: none;
    margin-bottom: 0px;
    margin-top:5px;
}
.nav li{
    color:white;
    text-decoration:none;
    display:inline;
    margin:2.5px 0px 2.5px 22px;
    margin-left:50px;
}
.icon:hover{
    border-bottom:4px solid #66297b;
    padding-bottom: 7px;
    color:#66297b;
}

.icon-nav{
    color:black;
    font-size: 1.5em;
    padding-left:4px;
}
.modal-display{
    z-index:1;
    top:0;
    right:0;
    left:0;
    margin:0;
    width:100%;
    padding:0;
    bottom:0;
    position:fixed;
    background-color: rgba(0, 0, 0, 0.315);
}
.main-navbar{
    background-color:rgba(0,0,0,0.60);
    position:fixed;
    top:0;
    z-index:1;
    width:100%;
    -webkit-box-shadow: -2px 10px 31px -18px rgba(0,0,0,0.75);
-moz-box-shadow: -2px 10px 31px -18px rgba(0,0,0,0.75);
box-shadow: -2px 10px 31px -18px rgba(0,0,0,0.75);
}
.title{
    color:#66297b;
    font-size: 2em;
    font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    padding-top:2px;
    padding-bottom: 0;
    margin-bottom: 0;
}
.search{
    border:0.5px solid black;
    width: 50%;
    padding-left:15px;
    margin-left:9px;
    border-radius: 10px;
   padding-right:5px;
   outline:0;
   padding-top:0;
   font-size: 1.2em;
}
.search:focus{
    border:2px solid rgb(26, 26, 255);
}
.add_qution{
    border-radius: 10px;
    height: 25px;
    padding-top:0;
    margin-top:7px;
}

.router-link-exact-active{
    border-bottom:4px solid #66297b;
    padding-bottom: 7px;
}
.dropdown-menu {
  left: 15px !important;
  top: 15px !important;
  -webkit-appearance: none !important;
  background-color: transparent !important;
}
.container-heigth{
  max-height:113px;
  height:100%;
}
.user-avater{
  height:60px;
  width:60px;
  border-radius:50%;
  margin-top:-20px;
}
.search-btn{
  height:36px;
  margin-left:2px;
  background-color:#fbcf00;
  border-radius:0;
}
.nav ul{
    list-style-type: none;
    margin-bottom: 0px;
    margin-top:5px;
}
.nav li{
    color:white;
    text-decoration:none;
    display:inline;
    margin:2.5px 0px 2.5px 22px;
    margin-left:50px;
}
.main-navbar{
    background-color:#1C1B1B;
    position:fixed;
    top:0;
    z-index:1;
    width:100%;
    -webkit-box-shadow: -2px 10px 31px -18px rgba(0,0,0,0.75);
-moz-box-shadow: -2px 10px 31px -18px rgba(0,0,0,0.75);
box-shadow: -2px 10px 31px -18px rgba(0,0,0,0.75);
}
.router-link-exact-active{
    border-bottom:4px solid #66297b;
    padding-bottom: 7px;
}
.dropdown-menu {
  left: 15px !important;
  top: 15px !important;
  -webkit-appearance: none !important;
  background-color: transparent !important;
}
@media screen and (max-width: 991px) {
  .mob-display {
    display:flex;
    justify-content:space-between;
  }
  .nav-mobile{
    width:16.6%;
    margin-top:10px;
  }
  .disk-display{
      display:none;
  }
  .user-avater{
    height:45px;
  width:45px;
  border-radius:50%;
  margin-top:-10px;
  margin-left:15px;
  margin-right:0px;
  }
  .container-heigth{
    height:80px;
    padding-top:15px;
  }
}
@media screen and (min-width: 992px) {
  .mob-nav-float-left{
    float:right;
  }
  .mob-display {
    display:none;
  }
  .disk-display{
      display:block;
  }
}

</style>
