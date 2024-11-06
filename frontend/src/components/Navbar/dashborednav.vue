<template>
    <div class='main-navbar'>
        <b-container fluid style='width:100%;' class="container-heigth nav-disk">
                    <b-row no-gutters style="margin-top:-13px;margin-left:-3.5%;padding-bottom:10px;">
                        <!-- <b-col md='2' style='margin-top:2px;margin-bottom:0;' class="disk-display">
                                    <img src="../../assets/logo.png" class="img-fluid">
                        </b-col> -->
                        <b-col lg='6' md="6" offset="6"  class='user-image'>
                            <b-col md="12" sm="auto">
                              <!-- <img src="./../../assets/Intersection 2.png" style='float:right;' class="user-avater"> -->
                            </b-col>
                        </b-col>
                    </b-row>
        </b-container>
    </div>
</template>
<script>
export default {
  data () {
    return {
      }
    },
  methods: {
    activateDrop(){
      
    }
  }
}
</script>
<style scoped>
.user-image{
  margin-top:50px;
}
.app-icon{
  margin-top:40px;
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
    margin-top:56px;
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
  .user-image{
  margin-top:20px;
  }
  .app-icon{
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
@media screen and (max-width: 767px) {
.nav-disk{
    display:none;
  }
}
</style>
