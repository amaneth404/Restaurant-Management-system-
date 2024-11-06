
<template>
  <div :id="isdisplay ? 'side-nav-collapsed' : 'side-nav-expanded'" :style="isdisplay ? 'width:0px;background:rgba(20, 20, 20);' : 'width:200px;background:rgba(20, 20, 20);'">
    <div class="close_button" @click="closeSidenav()"></div>
    <b-card no-body class='left-nav'>
      <div class="logo-container">
        <img src="shege showcase5.png" alt="" class="image-logo">
      </div>
      <b-row no-gutters class="side-items">
        <router-link 
          v-for="(item, index) in filteredMenuItems" 
          :key="index" 
          :to="item.link" 
          class="col-12 nav-items">
          <b-row no-gutters class='item-list' @click="closeSidenav(item.name)" :id="`popover-${index + 1}-right-home`">
            <div class="icon-container">
              <div>
                <i :class="item.icon" class="menu-icon"></i>
              </div>
            </div>
            <div class="text-container">
              {{ item.name }}
            </div>
          </b-row>
        </router-link>

      </b-row>
      <b-row no-gutters class='item-list nav-items' @click="Logout()" style="color:white;width:100%;cursor: pointer;padding-left:10px;">
        <b-col md='auto' style="width:10%;">
          <div>
            <i class="fas fa-lock" style="font-size:1em;"></i>
          </div>
        </b-col>
        <b-col md="auto" style="width:80%;text-align: left;margin-left:5%;">Log Out</b-col>
      </b-row>
    </b-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isdisplay: false,
      menuItems: [
        { name: 'Dashboard', icon: 'mdi mdi-chart-pie', link: '/admin', roles: ['Admin', 'Store', 'Casher'] },
        { name: 'Total Cash', icon: 'mdi mdi-currency-usd', link: '/manage/totalcash', roles: ['Admin'] },
        { name: 'Users', icon: 'mdi mdi-account-group', link: '/manage/users', roles: ['Admin'] },
        { name: 'Salary History', icon: 'mdi mdi-currency-usd', link: '/salary', roles: ['Admin'] },
        { name: 'Banks', icon: 'mdi mdi-bank', link: '/manage/bank', roles: ['Admin'] },
        { name: 'Expense Category', icon: 'mdi mdi-cash', link: '/manage/categorys/expense', roles: ['Admin', 'Casher'] },
        { name: 'Expense', icon: 'mdi mdi-cash', link: '/expense', roles: ['Admin', 'Casher'] },
        { name: 'Casher', icon: 'mdi mdi-cash-register', link: '/casher/report', roles: ['Admin'] },
        { name: 'Waiter', icon: 'mdi mdi-silverware-fork-knife', link: '/manage/waiter', roles: ['Admin', 'Casher'] },
        { name: 'Menu', icon: 'mdi mdi-food', link: '/manage/menu', roles: ['Admin'] },
        { name: 'Order', icon: 'mdi mdi-food', link: '/manage/order', roles: ['Admin', 'Casher'] },
        { name: 'Chef', icon: 'mdi mdi-food', link: '/manage/chef/order', roles: ['Admin'] },
        { name: 'Waiting', icon: 'mdi mdi-food', link: '/manage/waiting/orders', roles: ['Admin', 'Casher'] },
        { name: 'Waiting', icon: 'mdi mdi-food', link: '/manage/chef/waiting/orders', roles: ['Chef'] },
        { name: 'Done', icon: 'mdi mdi-food', link: '/manage/chef/done/orders', roles: ['Chef'] },
        { name: 'Void', icon: 'mdi mdi-food', link: '/manage/flaged/orders', roles: ['Admin'] },
        { name: 'Transfer History', icon: 'mdi mdi-source-branch', link: '/manage/transfered', roles: ['Admin', 'Store'] },
        { name: 'Category', icon: 'mdi mdi-source-branch', link: '/manage/categorys', roles: ['Admin', 'Store'] },
        { name: 'Item Group', icon: 'mdi mdi-group', link: '/manage/item', roles: ['Admin', 'Store'] },
        { name: 'Items Log', icon: 'mdi mdi-history', link: '/items/log', roles: ['Admin'] },
        { name: 'Items', icon: 'fas fa-cart-plus', link: '/manage/items', roles: ['Admin', 'Store'] },
        { name: 'Profile', icon: 'fas fa-user', link: '/account/and/profile', roles: ['Admin', 'Store', 'Casher', 'Chef'] },
      ]
    }
  },
  mounted() {
    window.Fire.$on('hide-or-unhide-mobile-side', () => {
      this.closeSidenav()
    })
  },
  computed: {
    filteredMenuItems() {
      const userRole = this.$store.getters.role;
      return this.menuItems.filter(item => item.roles.includes(userRole));
    }
  },
  methods: {
    closeSidenav() {
      if (window.screen.width <= 991)
        this.isdisplay = !this.isdisplay;
    },
    Logout() {
      this.$store.dispatch('Logout').then(() => {
        this.$router.push('/login')
      })
    }
  }
}
</script>

<style scoped>
#side-nav-expanded,
#side-nav-collapsed {
  transition: width 0.3s ease;
  overflow-y: auto;
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  background-color: #2c3e50;
  z-index: 1000;
}

.router-link-exact-active .item-list {
  border-radius: 0;
  background-color: white;
  color: black;
  border-top-left-radius: 15px;
  border-bottom-left-radius: 15px;
}

#side-nav-collapsed .text-container {
  display: none;
}

.image-logo {
  height: auto; /* Changed from fixed height to auto for responsiveness */
  max-height: 150px; /* Max height constraint */
  width: 100%;
  object-fit: cover;
  margin-top: -50px;
}

.logo-container {
  text-align: center;
  padding: 5px;
}

.icon-container {
  width: 30px;
  padding-left: 10px;
}

.text-container {
  width: calc(100% - 40px);
  text-align: left;
  padding-left: 10px;
  font-size: 1rem; /* Base font size */
}

.menu-icon {
  font-size: 1.2em;
  color: white;
}

.nav-items {
  margin-bottom: 10px;
  padding: 5px;
  text-align: left;
  margin-left: 5px;
  color: white;
  text-decoration: none;
}

.nav-items:hover {
  background-color: #34495e;
}

/* Media Queries for Responsive Design */
@media (max-width: 768px) {
  #side-nav-expanded,
  #side-nav-collapsed {
    width: 70px; /* Adjusted width for mobile */
  }

  #side-nav-expanded .text-container,
  #side-nav-collapsed .text-container {
    display: inline;
  }

  .text-container {
    font-size: 0.9rem; /* Smaller font size for mobile */
  }

  .menu-icon {
    font-size: 1.1em; /* Smaller icon size for mobile */
  }

  .nav-items {
    font-size: 0.8rem; /* Smaller font size for nav items */
  }
}

@media (max-width: 480px) {
  .text-container {
    font-size: 0.7rem; /* Even smaller font size for very small screens */
  }

  .menu-icon {
    font-size: 1em; /* Further reduced icon size */
  }

  .nav-items {
    font-size: 0.7rem; /* Further reduced nav item size */
  }
}

.left-nav {
  background-color: rgba(20, 20, 20, 0.452);
  margin-top: 2rem;
}
</style>
