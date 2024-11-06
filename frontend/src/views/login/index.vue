<template>
  <div class="login-container">
    <div class="fixed-bottom">
      <h5 class="footer-text">Developed By Amanuel</h5>
    </div>
    <div class="cover-back">
      <div class="body-reg">
        <div class="login-card-container">
          <b-card no-body class="login-card">
            <form class="login-form" @submit.prevent="handleLogin()">
              <div class="title-container">
                <h1 class="title">
                  <b>Sign in <span class="disk-display-title">to Shege Burger</span></b>
                </h1>
                <p class="subtitle"><small>use your username to login</small></p>
              </div>
              <div class="input-group-container" style="margin-bottom: 10px;">
                <b-input-group>
                  <template v-slot:prepend>
                    <span class="svg-container">
                      <b-icon icon="envelope"></b-icon>
                    </span>
                  </template>
                  <input
                    class="form-control login-input"
                    :class="{ 'is-invalid': loginForm.errors.has('username') }"
                    v-model="loginForm.username"
                    required
                    ref="username"
                    placeholder="Username"
                    name="username"
                    type="text"
                    tabindex="1"
                    autocomplete="off"
                  />
                  <has-error :form="loginForm" field="username" class="error-message"></has-error>
                </b-input-group>
              </div>

              <div class="input-group-container">
                <b-input-group>
                  <template v-slot:prepend>
                    <span class="svg-container" @click="showPwd">
                      <b-icon :icon="passwordType === 'password' ? 'lock' : 'eye'" aria-hidden="true"></b-icon>
                    </span>
                  </template>
                  <input
                    class="form-control login-input"
                    :key="passwordType"
                    ref="password"
                    :class="{ 'is-invalid': loginForm.errors.has('password') }"
                    required
                    v-model="loginForm.password"
                    :type="passwordType"
                    placeholder="Password"
                    name="password"
                    tabindex="2"
                    autocomplete="off"
                    @keyup="checkCapslock"
                    @blur="capsTooltip = false"
                  />
                  <has-error :form="loginForm" field="password" class="error-message"></has-error>
                </b-input-group>
              </div>

              <b-button :loading="loading" type="submit" class="login-button">Login</b-button>
            </form>
          </b-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      loginForm: new window.Form({
        username: '',
        password: ''
      }),
      passwordType: 'password',
      capsTooltip: false,
      loading: false,
      redirect: undefined,
    }
  },
  mounted() {
    if (this.loginForm.username === '') {
      this.$refs.username.focus();
    } else if (this.loginForm.password === '') {
      this.$refs.password.focus();
    }
  },
  methods: {
    checkCapslock(e) {
      const { key } = e;
      this.capsTooltip = key && key.length === 1 && (key >= 'A' && key <= 'Z');
    },
    showPwd() {
      this.passwordType = this.passwordType === 'password' ? '' : 'password';
      this.$nextTick(() => {
        this.$refs.password.focus();
      });
    },
    handleLogin() {
      this.loading = true;
      var form = window.VFToF(this.loginForm);
      this.$store.dispatch('login', { form })
        .then(() => {
          if(this.$store.getters.role!='Chef')
          this.$router.push('/admin');
        else
        this.$router.push('/manage/chef/waiting/orders');
          this.loading = false;
        })
        .catch((err) => {
          this.loginForm.errors.errors = err;
          this.loading = false;
        });
    }
  }
}
</script>

<style lang="scss" scoped>
$bg: #2d3a4b;
$dark_gray: #889aa4;
$light_gray: #eee;

.footer-text {
  padding-left: 20px;
  color: white;
  font-size: 1.3em;
  text-align: left;
}

.cover-back {
  position: fixed;
  z-index: 1;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
}

.body-reg {
  padding-top: 0;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-card-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.login-card {
  font-weight: bolder;
  border: 0;
  padding: 0;
  background: transparent;
  width: 100%;
  max-width: 400px; /* Adjust the max-width to make it responsive */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  padding: 20px;
}

.login-input {
  border: 0;
  height: 50px; /* Reduced height for better responsiveness */
}

.login-container {
  height: 100vh;
  width: 100%;
  display: flex;
  align-items: center;
  background-image: url('../../assets/background.png');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  overflow: hidden;

  .login-form {
    max-width: 100%;
    padding: 50px 20px 0;
    margin: 0 auto;
  }

  .svg-container {
    padding: 10px 5px 6px 10px;
    color: $dark_gray;
    background: white;
  }

  .title-container {
    .title {
      color: $light_gray;
      text-align: left;
      font-size: 1.5em; /* Reduced font-size */
      letter-spacing: 2px;
      font-family: serif;
    }
  }

  .subtitle {
    color: white;
    margin-top: -10px;
    margin-bottom: 20px;
    font-size: 1em;
    letter-spacing: 1px;
  }

  .login-button {
    margin-bottom: 20px;
    padding: 8px;
    font-size: 1.4em;
    background: #0cbbb6;
    border: 0;
    width: 100%;
    margin-top: 10px;
  }
}

/* Responsive Styling */
@media only screen and (max-width: 600px) {
  .title {
    font-size: 1.2em !important;
    margin-bottom: 10px !important;
    letter-spacing: 1px !important;
  }
  
  .disk-display-title {
    visibility: hidden;
  }

  .svg-container {
    padding: 5% 5% 5% 10px !important;
    font-size: 1.2em !important;
  }

  .login-button {
    font-size: 1em;
    padding-left: 10%;
    padding-right: 10%;
  }

  .login-card {
    max-width: 90%; /* Make the card occupy more space on small screens */
    padding: 15px;
  }
}

@media only screen and (min-width: 600px) {
  .title {
    font-size: 1.5em;
  }
}

@media only screen and (min-width: 768px) {
  .title {
    font-size: 2em;
    letter-spacing: 5px;
  }
  
  .disk-display-title {
    visibility: visible;
  }

  .login-card {
    max-width: 350px;
  }
}

@media only screen and (min-width: 992px) {
  .title {
    font-size: 2em;
    letter-spacing: 5px;
  }
}

@media only screen and (min-width: 1200px) {
  .title {
    font-size: 2em;
    letter-spacing: 5px;
  }

  .login-card {
    max-width: 400px;
  }
}
</style>
