<template>
  <div>
      <div v-if="!verify && !pageLoad">
        <mbdSelector @selected="selectedMBDAgency" :walkinDates="true"></mbdSelector>
        <div class="row" v-show="!rescan">
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">Blood Labeling</div>
                    <div class="panel-body">
                        <div class="form-horizontal col-lg-4">
                            <div class="form-group">
                                <label for="" class="control-label col-lg-4">MBD / Walk-in</label>
                                <div class="col-lg-8 input-group">
                                    <input @click.prevent="selectMBDAgency" type="text" class="form-control input-sm" placeholder="Click here to select MBD/Walk-in" readonly v-if="!sched.agency_cd" style="background-color:#fff;">
                                    <div class="form-control input-sm" v-if="sched.agency_cd" @click.prevent="selectMBDAgency">
                                        <span v-if="sched.agency_name == 'Walk-in'">{{sched.agency_name}} - FROM {{sched.from}} TO {{sched.to}}</span>
                                        <span v-if="sched.agency_name != 'Walk-in'">{{sched.agency_name}} - {{sched.donation_dt.substr(0,10)}}</span>
                                    </div>
                                    <div class="input-group-btn">
                                        <button class="btn btn-default btn-sm" @click.prevent="selectMBDAgency"><span class="glyphicon glyphicon-search"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-horizontal col-lg-3">
                            <div class="form-group">
                                <label for="" class="control-label col-lg-4">Component</label>
                                <div class="col-lg-8">
                                    <select class="form-control input-sm" v-model="component_cd">
                                        <option value=""></option>
                                        <option v-for="(cn,cd) in all_components" :value="cd" :key="cd">{{cn}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <button class="btn btn-default btn-sm" :disabled="!sched.sched_id && !component_cd" @click.prevent="fetchDonations">Search <span class="glyphicon glyphicon-search"></span></button>
                        </div>
                    </div>
                    <table class="table table-condensed table-hover table-bordered">
                        <thead>
                            <tr>
                                <td :colspan="4 + Object.keys(components).length"><button class="btn btn-primary btn-sm pull-right" @click.prevent="verify = true" :disabled="!selected.length">Proceed</button></td>
                            </tr>
                            <tr>
                                <th class="text-center" style="font-size:12px;">DONATION ID</th>
                                <th class="text-center" style="font-size:12px;">DONOR</th>
                                <th class="text-center" style="font-size:12px;">BLOOD TYPE</th>
                                <th class="text-center" style="font-size:12px;">BLOOD TEST</th>
                                <th v-for="(cn,cd) in components" :key="cd" style="font-size:10px;" class="text-center">{{cn}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!sched.agency_cd">
                                <td :colspan="4 + Object.keys(components).length" class="text-center">Please enter search criteria</td>
                            </tr>
                            <tr v-if="loading">
                                <td :colspan="4 + Object.keys(components).length" class="text-center"><loadingInline v-if="loading" label="Please wait, fetching Donation IDs"></loadingInline></td>
                            </tr>
                            <tr v-for="d in donations" :key="d.donation_id" style="font-size:12px;" v-if="!loading">
                                <td class="text-center">{{d.donation_id}}</td>
                                <td class="text-center"><span v-if="d.donor_min">Done</span> <span v-if="!d.donor_min" class="text-danger">PENDING</span></td>
                                <td class="text-center">
                                    <span v-if="d.type">{{d.type.blood_type}}</span>
                                    <span v-if="!d.type" class="text-danger">PENDING</span>
                                </td>
                                <td class="text-center">
                                    <span v-if="d.test">
                                        <span class="text-success" v-if="d.test.result == 'N'">NR</span>
                                        <span class="text-danger" v-if="d.test.result == 'R'">R</span>
                                    </span>
                                    <span v-if="!d.test" class="text-danger">PENDING</span>
                                </td>
                                <td v-for="(cn,cd) in components" :key="cd" class="text-center">
                                    <div v-if="!isAlreadyLabeled(d.labels,cd) && (!d.type || !d.test || !d.donor_min)">
                                        <input v-if="hasUnit(d.units,cd)" type="checkbox" disabled>
                                    </div>
                                    <div v-if="!isAlreadyLabeled(d.labels,cd) && (d.type && d.test && d.donor_min)" @click=" hasUnit(d.units,cd) ? selectUnit(d.donation_id,cd) :null">
                                        <input v-if="hasUnit(d.units,cd)" type="checkbox" v-model="selected" :value="{donation_id : d.donation_id, component_cd : cd}" :disabled="(!d.type || !d.test || !d.donor_min)">
                                    </div>
                                    <div v-if="isAlreadyLabeled(d.labels,cd)">
                                        <span class="glyphicon glyphicon-ok text-success"></span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!loading && (sched.sched_id && component_cd) && !donations">
                                <td class="text-center" :colspan="4 + Object.keys(components).length">No Available Donations</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td :colspan="4 + Object.keys(components).length"><button class="btn btn-primary btn-sm pull-right" @click.prevent="verify = true" :disabled="!selected.length">Proceed</button></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="row" v-if="rescan">
            <div class="col-lg-4 col-lg-offset-4" style="padding-top:50px;">
                <re-scan :rescan="rescan" @success="preview = clone(rescan); confirmSelecting(); rescan = null;" @cancel="rescanCancelled()"></re-scan>
            </div>
        </div>

      </div>
      <div v-if="verify && !pageLoad">
          <verifier @proceed="submitLabeling" @cancel="verify = false;" ></verifier>
      </div>
      <loading v-if="pageLoad"></loading>
  </div>
</template>

<script>
import ReScan from './ReScan.vue';
// import Preview from './LabelPreview.vue';

export default {
  components : {ReScan},
  props : ['refresh'],
  data(){
      let {sched} = this.$store.state;
    //   sched = {"sched_id":"1300620180000299","agency_cd":"1300602181","agency_name":"NVBSP IMU","donation_dt":"2018-04-28 00:00:00"};
      let components = this.$session.get('components');
      let all_components = _.clone(components);
      return {
          sched, selected : [], donations : [], loading : false, component_cd : null, components, all_components,
          rescan : null, preview :  {donation_id : "NVBSP20160164125&" , component_cd : 40}, verify : false, pageLoad : false
      };
  },
  mounted(){
      this.fetchDonations();
  },
  methods : {
      selectMBDAgency(){
          $("#MBDSelector").modal("show");
      },
      selectedMBDAgency(sched){
          this.sched = sched;
          this.$store.state.sched = sched;
          $("#MBDSelector").modal("hide");
      },
      fetchDonations(){
          let {sched,donation_id,component_cd} = this;
          if(!this.sched.sched_id && !this.donation_id && !this.componetn_cd){
              return;
          }
          this.loading = true;
          this.$http.post(this,"labeling/list",{
              sched, component_cd, facility_cd : this.$session.get('user').facility_cd 
          })
          .then(({data}) => {
              this.donations = _.sortBy(data,['donation_id'],['asc']);
              this.loading = false;
          });
      },
      hasUnit(units,cd){
        //   console.log(_.filter(units,{component_cd : 10}));
          return _.filter(units,{component_cd : cd*1}).length > 0;
      },
      isAlreadyLabeled(labels,cd){
          return _.filter(labels,{component_cd : cd}).length > 0;
      },
      rescanCancelled(){
          let {donation_id,component_cd} = this.rescan;
          _.remove(this.selected,{donation_id,component_cd});
          this.rescan = null;
      },
      selectUnit(donation_id,component_cd){
          
          this.rescan = {donation_id,component_cd};
      },
      confirmSelecting(){
           let {donation_id,component_cd} = this.rescan;
            let criteria = {'donation_id' : donation_id, 'component_cd' : component_cd*1};
            let unit = _.find(this.selected,criteria);
            if(unit){
                this.selected = _.reject(this.selected,criteria);
            }else{
                this.selected.push(criteria);
            }
            this.rescan = null;
      },
      submitLabeling(verifier){
          this.pageLoad = true;
          let {user_id,facility_cd} = this.$session.get('user');
          let {selected} = this;
          this.$http.post(this,'labeling/save',{
              facility_cd, selected, user_id, verifier
          })
          .then(({data}) => {
              this.selected = [];
              this.pageLoad = false;
              this.$store.state.msg = {
                  content : 'Blood Labeling succesfully saved.'
              };
              this.selected = [];
            this.donations = [];
            this.loading = false;
            this.fetchDonations();
          })
      },
      clone(obj){
          return _.clone(obj);
      }
  },
  watch : {
      sched(){
          this.$store.state.sched = this.sched;
      },
      refresh(){
          this.selected = [];
          this.donations = [];
          this.loading = false;
          this.fetchDonations();
      },
      component_cd(){
        let {component_cd} = this;
        if(component_cd){
            this.components = _.pickBy(this.all_components, function(value, key){
                return key == component_cd;
            });
        }else{
            this.components = _.clone(this.all_components);
        }
      },
      preview(){
          if(this.preview.donation_id){
            let {facility_cd} = this.$session.get('user');
            let {donation_id,component_cd} = this.preview;
            this.printBloodBagLabel(facility_cd,donation_id,component_cd);
          }
      }
  }
}
</script>

<style scoped>
.control-label {
    margin-top:-.5em;
    font-size: 14px;
}
input[type='checkbox']{
    width:15px;
    height:15px;
}

</style>