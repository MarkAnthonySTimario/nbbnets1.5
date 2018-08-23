<template>
    <div>
        <div class="row" v-if="!loading && mbd">
            <div class="col-lg-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">MBD Details
                        <router-link to="/MBD" class="pull-right btn btn-default btn-xs"><span class="glyphicon glyphicon-arrow-left"></span> Back to List</router-link>
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Agency</th><td>{{ mbd.agency_name }}</td>
                            </tr>
                            <tr>
                                <th>Date</th><td>{{ mbd.donation_dt.substr(0,10) }}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="col-lg-6" v-if="updateDonationMode && updateDonationModeErrors.toString().replace(/[,]+/g,'').length > 0">
                <div class="panel panel-danger">
                    <div class="panel-heading">Some Errors were found</div>
                    <div class="panel-body" style="font-size:12px;">
                        <ul v-for="(error,i) in updateDonationModeErrors" :key="i">
                            <li v-for="(msg,i2) in error" :key="i2">
                                <span class="glyphicon glyphicon-remove text-danger"></span> {{msg}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-12" v-if="!updateDonationMode">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <router-link :to="('/MBD/'+sched_id+'/SearchDonor')" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-search"></span> Search Donor</router-link>
                        <button @click="updateDonationMode = true" class="btn btn-default btn-xs" :disabled="!selected.length"><span class="glyphicon glyphicon-pencil"></span> Update Donation Details</button>
                    </div>
                    <donations :schedid="mbd.sched_id" @onselect="updateSelection" :refresh="refreshDonations"></donations>
                </div>
            </div>
            <div class="col-lg-12" v-if="updateDonationMode">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Update Phlebotomy Details
                        <button @click="updateDonationMode = false" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                    </div>
                    <phlebotomy :donations="selected" @onerror="updateDonationHasErrors" @oncomplete="updateDonationIsDone"></phlebotomy>
                </div>
            </div>
        </div>
        <loading v-if="loading"></loading>
    </div>
</template>

<script>
import Donations from './Manager/Donations.vue';
import Phlebotomy from './Manager/Phlebotomy.vue';

export default {
  components : { Donations, Phlebotomy },
  props : ['sched_id'],
  data(){
      return  {
          mbd : null, loading : false, selected : [],
          headers : {headers : { Authorization : this.$store.state.token.access_token }},
          updateDonationMode : false, updateDonationModeErrors : [], refreshDonations : 0
      }
  },
  methods : {
      updateSelection(selection){
          this.selected = selection;
      },
      updateDonationHasErrors(errors){
          this.updateDonationModeErrors = errors;
      },
      updateDonationIsDone(){
          this.selected = [];
          this.updateDonationMode = false;
          this.refreshDonations++;
      }
  },
  mounted(){
      this.loading = true;
      this.$http.get(this,"mbd/info/"+this.sched_id)
      .then(({data}) => {
          this.mbd = data;
          this.loading = false;
      })
      .catch(error => {
          this.$store.state.error = error;
      });
  }
}
</script>


