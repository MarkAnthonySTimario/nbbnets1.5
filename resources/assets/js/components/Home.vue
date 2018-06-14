<template>
  <div>
      <br/>
      <div class="row">
        <div class="col-lg-7">
          <div class="row">
            <div class="col-lg-12">
              <div class="panel panel-success">
                <div class="panel-heading">Blood Stocks</div>
                <inventory></inventory>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="jumbotron"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="row">
            <div class="col-lg-12">
              <user></user>
            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import inventory from './Home/InventoryTable.vue';
import user from './Home/User.vue';

export default {
  components : {inventory,user}  
}
</script>
