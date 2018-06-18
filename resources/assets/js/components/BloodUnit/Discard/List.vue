<template>
  <div>
      <mbdSelector @selected="selectedMBDAgency" :walkinDates="true"></mbdSelector>
      <div class="row">
          <div class="col-lg-12">
              <div class="panel panel-success">
                <div class="panel-heading">Blood Discard</div>
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
                    <div class="form-horizontal col-lg-4">
                        <div class="form-group">
                            <label for="" class="control-label col-lg-5">Donation ID</label>
                            <div class="col-lg-7">
                                <input type="text" maxlength="16" step="font-size:10px;" class="form-control input-sm" placeholder="Donation ID" v-model="donation_id" @keyup="donation_id = donation_id ? donation_id.toUpperCase() : null" >
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
                        <button class="btn btn-sm btn-default" :disabled="!sched.sched_id && !donation_id && !component_cd" @click.prevent="fetchDonations">Search <span class="glyphicon glyphicon-search"></span></button>
                    </div>
                </div>
                <table class="table table-condensed table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">Donation Schedule</th>
                            <th colspan="4"></th>
                            <th class="text-center" :colspan="(Object.keys(components).length)">Blood Components</th>
                        </tr>
                        <tr>
                            <th class="text-center" style="font-size:12px;">AGENCY</th>
                            <th class="text-center" style="font-size:12px;">DATE</th>
                            <th class="text-center" style="font-size:12px;">DONATION ID</th>
                            <th class="text-center" style="font-size:12px;">BLOOD TYPE</th>
                            <th class="text-center" style="font-size:12px;">BLOOD TEST</th>
                            <th class="text-center" style="font-size:12px;">BLOOD LABELING</th>
                            <th v-for="(cn,cd) in components" :key="cd" style="font-size:10px;" class="text-center">{{cn}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!sched.agency_cd">
                            <td :colspan="6 + Object.keys(components).length" class="text-center">Please enter search criteria</td>
                        </tr>
                        <tr v-if="loading">
                            <td :colspan="6 + Object.keys(components).length" class="text-center"><loadingInline v-if="loading" label="Please wait, fetching Donation IDs"></loadingInline></td>
                        </tr>
                        <tr v-for="d in donations" :key="d.donation_id" style="font-size:12px;" v-if="!loading">
                            <td v-if="d.sched_id == 'Walk-in'" nowrap>Walk-in</td>
                            <td v-if="d.sched_id == 'Walk-in'" nowrap>{{d.created_dt.substr(0,10)}}</td>
                            <td v-if="d.mbd" nowrap>{{d.mbd.agency_name}}</td>
                            <td v-if="d.mbd" nowrap>{{d.mbd.donation_dt.substr(0,10)}}</td>
                            <td>{{d.donation_id}}</td>
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
                            <td class="text-center">
                                <span v-if="d.labels.length" class="text-success">LABELED</span>
                                <span v-if="!d.labels.length" class="text-danger">PENDING</span>
                            </td>
                            <td v-for="(cn,cd) in components" :key="cd" class="text-center" @click=" hasUnit(d.units,cd) ? selectUnit(d.donation_id,cd) :null">
                                <div v-if="!isAlreadyDiscarded(d.discards,cd)">
                                    <span v-if="hasUnit(d.units,cd)">{{fetchUnit(d.units,cd).comp_stat | comp_stat}}</span> <input v-if="hasUnit(d.units,cd)" type="checkbox" v-model="selected" :value="{donation_id : d.donation_id, component_cd : cd}">
                                </div>
                                <div v-if="isAlreadyDiscarded(d.discards,cd)">
                                    <span class="glyphicon glyphicon-trash text-warning"></span>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!loading && (sched.sched_id && donation_id && component_cd) && !donations">
                            <td class="text-center" :colspan="6 + Object.keys(components).length">No Available Donations</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td :colspan="6 + Object.keys(components).length"><button class="btn btn-primary" @click.prevent="$emit('hasSelected',selected)" :disabled="!selected.length">Proceed</button></td>
                        </tr>
                    </tfoot>
                </table>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
  props : ['refresh'],
  data(){
      let {sched} = this.$store.state;
    //   sched = {"sched_id":"1300620180000636","agency_cd":"1300601860","agency_name":"NVBSP-IMU","donation_dt":"2018-04-20 00:00:00"};
      let components = this.$session.get('components');
      let all_components = _.clone(components);
      return {
          sched, selected : [], donations : [], loading : false, donation_id : null, component_cd : null, components, all_components
      };
  },
  filters : {
      comp_stat(v){
          if(v != 'AVA' || v != 'EXP'){
              return 'OP';
          }else if(v == 'EXP'){
              return 'E';
          }else if(v == 'AVA'){
              return 'A';
          }else{
              return '';
          }
      }
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
          this.$http.post(this,"discard/list",{
              sched, donation_id, component_cd, facility_cd : this.$session.get('user').facility_cd 
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
      isAlreadyDiscarded(discards,cd){
          return _.filter(discards,{component_cd : cd}).length > 0;
      },
      selectUnit(donation_id,component_cd){
          let criteria = {'donation_id' : donation_id, 'component_cd' : component_cd*1};
          let unit = _.find(this.selected,criteria);
          if(unit){
              this.selected = _.reject(this.selected,criteria);
          }else{
              this.selected.push(criteria);
          }
      },
      fetchUnit(units,cd){
          return _.first(units,{'component_cd':cd*1});
      }
  },
  watch : {
      sched(){
          this.$store.state.sched = this.sched;
        //   this.fetchDonations();
      },
      refresh(){
        //   console.log("but why");
        //   let {sched} = this.$store.state;
        //   this.sched = sched;
        //   this.donation_id = null;
        //   this.component_cd = null;
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
      }
  }
}
</script>

<style scoped>

input[type='checkbox']{
    width:15px;
    height:15px;
}
</style>