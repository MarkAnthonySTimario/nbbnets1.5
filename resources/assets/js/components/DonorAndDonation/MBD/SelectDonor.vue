<template>
  <div>
    <loading v-if="loading"></loading>
    <div v-if="mbd">
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">MBD Details
                        <router-link :to="('/MBD/' + sched_id)" class="pull-right btn btn-default btn-xs"><span class="glyphicon glyphicon-arrow-left"></span> Back to MBD</router-link>
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Agency</th><td>{{ mbd.agency_name }}</td>
                            </tr>
                            <tr>
                                <th>Date</th><td>{{ mbd.donation_dt.substr(0,10) }}</td>
                            </tr>
                            <tr v-if="donation_id != undefined">
                                <th>Donation ID</th><td>{{ donation_id }}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <donor-selector :mbd="mbd" :donation_id="donation_id"></donor-selector>
        
    </div>
  </div>
</template>

<script>

import donorSelector from './../Donor/Selector.vue';

export default {
    components : {donorSelector},
    props : ['sched_id'],
    data(){
        let {currentRoute : {query : {donation_id}}} = this.$router;
        if(donation_id == null || donation_id == undefined || donation_id == 'null' || donation_id == 'undefined'){
            donation_id = null;
        }
        return {
            donation_id, mbd : null, loading : false, headers : {
                headers : {Authorization : this.$store.state.token.access_token}
            }
        }
    },
    mounted(){
        this.loading = true;
        axios.get("api/mbd/info/"+this.sched_id,this.headers)
        .then(({data}) => {
            this.mbd = data;
            this.loading = false;
        });
    }
}
</script>
