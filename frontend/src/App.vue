<template>
<div id="app">
<router-view/>
</div>
</template>
<script>
export default {
  name: 'App',
  created:function(){
    window.axios.interceptors.response.use(undefined, function(err) {
            return new Promise(function() {
                if (err.response.status == 401 && err.response.config && !err.response.config.__isRetryRequest) {
                  localStorage.removeItem('token_empire_token_identifyer')
                  localStorage.removeItem('username')
                  delete window.axios.defaults.headers.common['Authorization']
                  window.Fire.$emit('logout')
                  this.$router.push('/login')
                }
                throw err;
            })
        })
  },
  mounted(){
    window.Fire.$on('logout',()=>{
      this.$router.push('/login')
    })
  }
}
</script>
<style lang="scss">
.popover {
      position: absolute !important;
    top: 0px !important;
    left: 90px !important;
}
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
color: #2c3e50;
background-color:rgba(255, 255, 255, 0.288);
}
.modal-content{
  background-color: rgb(52,58,64) !important;
  color:white !important;
}
.modal-footer{
  display:none !important;
}
.modal-header{
  display:none !important;
}

.bg-gradient-danger {
  -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#fae903), color-stop(99%, rgba(144, 182, 55, 0.705) )) !important;
        background: linear-gradient(to right, #fae903, rgba(144, 182, 55, 0.705)  99%) !important;
}
.buy-now .btn-buy-now {
    position: fixed !important;
    bottom: 3rem !important;
    right: 1.625rem !important;
    z-index: 999999 !important;
    box-shadow: 0 1px 20px 1px #ff3e1d !important;
}
.btn-danger {
    color: #fff !important;
    background-color: #ff3e1d !important;
    border-color: #ff3e1d !important;
    box-shadow: 0 0.125rem 0.25rem 0 rgb(255 62 29 / 40%) !important;
}
.badge-success {
    border: 1px solid #1bcfb4 !important;
    background: #1bcfb4 !important;
    color: #ffffff !important;
}
.btn-primary {
    color: #fff !important;
    background-color: #696cff !important;
    border-color: #696cff !important;
    box-shadow: 0 0.125rem 0.25rem 0 rgb(105 108 255 / 40%) !important;
}
.slick-arrow.slick-prev::before{
  color:rgb(4,96,130); 
}
.slick-arrow.slick-next::before{
  color:rgb(4,96,130); 
}
.dropdown  .btn-secondary{
background: transparent !important;
color:black;
border:0 !important;
}
.modal-body {
    position: relative;
    flex: 1 1 auto;
     padding: 0 !important; 
}
.modal-content{
  border-radius: 10px !important;
}
.bg-gradient-danger-header {
  -webkit-transition: all .25s ease;
  -o-transition: all .25s ease;
  -moz-transition: all .25s ease;
  transition: all .25s ease;
  border:0 !important;
    background: -webkit-gradient(linear, left top, right top, from(#e4f359), color-stop(99%, gold)) !important;
        background: linear-gradient(to right, rgba(10, 10, 10, 0.842), rgba(0, 0, 0, 0.897) 50%) !important;
}


</style>
