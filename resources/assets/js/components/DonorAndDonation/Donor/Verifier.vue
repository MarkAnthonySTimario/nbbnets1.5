<template>
  <div>
      <div class="row" v-if="donation_id">
        <div class="col-lg-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    Assign Donor to Walk-in Donation
                    <router-link to="/Walkin" class="pull-right btn btn-default btn-xs"><span class="glyphicon glyphicon-arrow-left"></span> Back to List</router-link>
                </div>
                <table class="table table-bordered table-condensed">
                    <tbody>
                        <tr>
                            <td>Donation ID</td>
                            <td>{{donation_id}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

      </div>
      <selector></selector>
  </div>
</template>

<script>
import Selector from './Selector.vue';

export default {
    data(){
        let {currentRoute:{query:{donation_id}}} = this.$router;
        return {donation_id};
    },
    components : {Selector}
}
</script>
