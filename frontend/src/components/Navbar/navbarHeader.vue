<template>
  <div class="main-navbar">
    <b-row no-gutters>
      <b-col md="12">
        <div>
          <!-- Mobile Display -->
          <div class="mob-display">
            <div class="logo-container">
              <b>Store</b>
            </div>
            <div class="icon-container">
              <i class="fa fa-list" aria-hidden="true" @click="hidesidenav"></i>
            </div>
          </div>
        </div>
      </b-col>
      <b-col md="12">
        <div>
          <!-- Desktop Display -->
          <div class="disk-display">
            <div class="logo-container">
              <!-- Optional Logo -->
            </div>
            <div class="icon-container">
              <i class="fas fa-bell"></i>
            </div>
          </div>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: {
        state: -1,
        search: ''
      },
      filtered_users: []
    }
  },
  mounted() {
    window.Fire.$on('search-user-data', (val) => {
      if (val == 'req') {
        this.search.state = -1
      } else if (val == 'c') {
        this.search.state = 0
      } else if (val == 'r') {
        this.search.state = 1
      }
    })
  },
  methods: {
    FilterUsers() {
      this.$store.dispatch('admin/AdminController', { 'data': this.search, 'method': 'post', 'api': 'admin_serach_user', 'variable': 'filteredUsers' }, { root: true }).then((val) => {
        this.filtered_users = val
      })
    },
    hidesidenav() {
      window.Fire.$emit('hide-or-unhide-mobile-side')
    }
  }
}
</script>

<style scoped>
.main-navbar {
  position: fixed;
  top: 0;
  z-index: 1;
  width: 100%;
}

.mob-display {
  background-color: rgb(32, 44, 64);
  padding: 10px 1.3%;
  color: rgb(140, 140, 140);
  box-shadow: 0 0 0.375rem 0.25rem rgba(0, 0, 0, 0.2);
  height: 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.disk-display {
  background-color: white;
  padding: 10px 1.3%;
  height: 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-container {
  margin-left: 15px;
}

.icon-container {
  margin-right: 20px;
  color: #bba8bff5;
  font-size: 1.2em;
}

@media screen and (max-width: 991px) {
  .mob-display {
    display: flex;
  }
  .disk-display {
    display: none;
  }
}

@media screen and (min-width: 992px) {
  .mob-display {
    display: none;
  }
  .disk-display {
    display: flex;
  }
}

.icon-header {
  font-size: 1.3em;
  color: #bba8bff5;
  margin-right: 5px;
  padding-top: 5px;
}

.user-avater {
  height: 40px;
  width: 40px;
  border-radius: 50%;
  margin-top: 0;
}
</style>
