<template> 
<div style="overflow-x:hidden;">
      <div>
          <DashNavbar/>
      </div>
      <div v-if="false">
          <MainNavbar/>
      </div>
      <aside>
            <SideNavBar class='side-nav'/>
            </aside>
             <div class="content-wrapper">
      <div class="container-fluid">
        <div >
         <div class='topiccard' >
            <AppMain/>
          </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
      </div><!-- /.container-fluid -->

    </div>
</template>
<script>
import DashNavbar from './../components/Navbar/dashborednav'
import MainNavbar from './../components/Navbar/navbarold'
import SideNavBar from './../components/SideNav/adminsidenav'
import AppMain from './AppMain'
export default {
    components:{DashNavbar,MainNavbar,SideNavBar,AppMain},
    data(){
        return {

        }
    }
}
</script>
<style scoped>
.content-wrapper{
  margin-left:300px;
}
.side-nav{
  width: 100%;
}
.rightside{
  padding-right: 20px;
  padding-left:20px;
}
@media screen and (max-width: 992px) {
.rightside{
  display:none;
}
}
@media screen and (min-width: 768px) {
  .topiccard{
  margin-left:6%;
  box-sizing: border-box;
}
.side-nav{
  width:100%;
  height:100vh;
}
.nav-phone{
  display: none;
}
.mob{
  display: none;
}
}
@media screen and (max-width: 767px) {
.side-nav{
  display: none;
}
.content-wrapper{
    margin-left:0;
  }
.nav{
  display: none;
}
.tab{
  display: none;
}
.topiccard{
  margin:0;
  box-sizing: border-box;
  overflow-x:hidden;
}
.rightside{
  display:none;
}
}
</style>
