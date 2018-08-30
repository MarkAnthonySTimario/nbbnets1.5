<template>
  <div class="row">
      <agency-selector @onselect="setAgency"></agency-selector>
                                  
      <div class="col-lg-8">
          <div class="panel panel-warning">
              <div class="panel-heading">
                  Create New MBD
                      <router-link to="/MBD" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-arrow-left"></span> Back to List</router-link>
              </div>
              <div class="panel-body form-horizontal">

                  <div class="form-group required">
                      <label for="" class="control-label col-lg-4">Agency</label>
                      <div class="col-lg-8">
                          <div class="input-group">
                              <div type="text" class="form-control input-sm">
                                  <span class="pull-right" v-if="!agency">Click the button to select an Agency</span>
                                  <span v-if="agency">{{agency.agency_name}}</span>
                              </div>
                              <div class="input-group-btn">
                                  <a href="#" data-target="#agencySelector" data-toggle="modal" class="btn btn-default btn-sm">
                                      <span class="glyphicon glyphicon-search"></span>
                                  </a>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="form-group required">
                      <label for="" class="control-label col-lg-4">MBD Date</label>
                      <div class="col-lg-8"><input type="date" class="form-control input-sm" v-model="donation_dt"></div>
                  </div>

                  <div class="form-group">
                      <label for="" class="control-label col-lg-4">Remarks</label>
                      <div class="col-lg-8"><textarea v-model="remarks" cols="30" rows="10" class="form-control input-sm"></textarea></div>
                  </div>

                  <div class="form-group">
                      <div class="col-lg-8 col-lg-offset-4" v-if="error">
                          <div class="alert alert-danger">
                            <span class="glyphicon glyphicon-warning-sign"></span> {{error}}
                          </div>
                      </div>
                      <div class="col-lg-8 col-lg-offset-4">
                          <div class="row">
                            <div class="col-lg-4">
                            <button :disabled="loading" class="btn btn-default btn-sm" @click.prevent="createAgency">Create MBD</button>

                            </div>
                            <div class="col-lg-8">
                            <loadingInline v-if="loading" label="Please wait.."></loadingInline>

                            </div>

                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>

import agencySelector from './../Agency/Selector.vue';

export default {
    components : {agencySelector},
    data(){
        return {
            agency : null, donation_dt : '2018-08-30', remarks : null, error : null, loading : false
        }
    },
    methods : {
        setAgency(agency){
            this.agency = agency;
        },
        createAgency(){
            if(!this.agency){
                this.error = "Select an agency";
                return;
            }

            if(!this.donation_dt){
                this.error = "Set MBD Date";
                return;
            }

            this.error = null;
            this.loading = true;

            let {user_id,facility_cd} = this.$store.state.user;
            let {agency : {agency_cd,agency_name} , donation_dt, remarks} = this; 
            axios.post("api/mbd/create",{
                user_id, facility_cd, agency_cd, agency_name, donation_dt, remarks
            },{
                headers : {Authorization : this.$store.state.token.access_token}
            })
            .then(({data}) => {
                this.$router.replace(data.sched_id);
                this.loading = false;
            });
        }
    }
}
</script>