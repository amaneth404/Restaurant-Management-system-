<template>
  <div style="overflow-x:hidden;height:100vh;" class="main_page">
    <div v-if="type == 'dash'">
      <MainNavbar v-if="false" />
      <div :class="['transition-controller', isdisplay ? 'nav-bar-minimized' : 'nav-bar']">
        <DashNavbar />
      </div>
      <aside>
        <SideNavBar class="side-nav" :isdisplay="isdisplay" />
      </aside>
      <div class="content-wrapper">
        <div class="container-fluid" style="margin-top:100px;">
          <div>
            <div :class="['transition-controller', isdisplay ? 'topiccard-minimized' : 'topiccard']">
              <AppMain />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="type == 'main'">
      <MainNavbar />
      <div>
        <AppMain />
      </div>
    </div>
  </div>
</template>
<script>
import DashNavbar from './../components/Navbar/navbarHeader.vue';
import MainNavbar from './../components/Navbar/navbarold';
import SideNavBar from './../components/SideNav/adminsidenav';
import AppMain from './AppMain';

export default {
  components: { DashNavbar, MainNavbar, SideNavBar, AppMain },
  props: {
    type: String,
  },
  data() {
    return {
      isdisplay: false,
    };
  },
  mounted() {
    window.Fire.$on('hide-or-unhide-mobile-side', () => {
      this.isdisplay = false;
    });
    window.Fire.$on('hide-or-unhide-sidenav', () => {
      this.isdisplay = !this.isdisplay;
    });
  },
};
</script>
<style scoped>
.main_page {
  background-color: rgba(219, 217, 217, 0.219);
}

.content-wrapper {
  margin-top: 50px;
}

.transition-controller {
  transition: margin 0.28s ease;
}

/* Sidebar Styles */
#side-nav {
  width: 15%;
  position: fixed;
  top: 0;
  bottom: 0;
  z-index: 100;
  transition: width 0.28s ease;
}

#side-nav.togled {
  display: block;
}

#side-nav-minimized {
  width: 50px;
}

.topiccard {
  margin-left: 15%;
}

.nav-bar {
  margin-left: 0;
}

/* Responsive Styles */
@media screen and (max-width: 991px) {
  #side-nav {
    width: 100% !important;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    position: absolute;
  }

  #side-nav-minimized {
    display: none;
  }

  .content-wrapper {
    margin-left: 0;
    margin-top: 60px;
  }

  .topiccard {
    margin-left: 0;
  }

  .nav-bar {
    margin-left: 0;
  }

  .topiccard-minimized,
  .nav-bar-minimized {
    margin-left: 10px;
    box-sizing: border-box;
    overflow-x: hidden;
  }

  .nav,
  .tab {
    display: none;
  }
}

@media screen and (max-width: 767px) {
  #side-nav {
    width: 100% !important;
    display: none;
  }

  #side-nav-minimized {
    display: none;
  }

  .content-wrapper {
    margin-left: 0;
    margin-top: 60px;
  }

  .topiccard,
  .nav-bar,
  .topiccard-minimized,
  .nav-bar-minimized {
    margin-left: 0;
    box-sizing: border-box;
    overflow-x: hidden;
  }

  .nav,
  .tab {
    display: none;
  }
}
</style>
