<template>
  <div v-if="data.id" style="background:white;padding:20px;">
  <div style="display: flex;justify-content:space-between;">
    <div>
    </div>
    <div>
        <h4 style="text-align:left;">{{ data.name }}</h4>
        <h4 style="text-align:left;">{{ data.created_at|myDate }}</h4>
    </div>
  </div>
  <div>
    <table class="table" style="margin-top:50px;">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">title</th>
      <th scope="col">Price</th>
      <th scope="col">Amount</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="req_it,index in selected" :key="req_it.items">
      <th scope="row">{{ index }}</th>
      <td>{{ req_it.item.title }}</td>
      <td>{{ req_it.sell_price }}</td>
      <td>{{ req_it.amount }}</td>
    </tr>
  </tbody>
</table>
    </div>
 </div>
</template>
<script>
export default {
 data() {
   return {
    data:{},
    selected:[]
   }
 },
 beforeMount() {
  var holder=JSON.parse(localStorage.getItem('data_requested_print'))
  this.data=holder.data
  this.selected=holder.selected
 },
 methods: {
 },
};
</script>
<style scoped>
</style>