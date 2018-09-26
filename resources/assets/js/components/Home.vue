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
                <div class="panel-footer">
                  <chart :height="300" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="row">
            <div class="col-lg-12">
              <user></user>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <expiring-units />
            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import inventory from './Home/InventoryTable.vue';
import chart from './StockManagement/StatusOfInventory/Available.vue';
import user from './Home/User.vue';
import ExpiringUnits from './Home/ExpiringUnits.vue';

export default {
  components : {inventory,chart,user,ExpiringUnits}
}
</script>
