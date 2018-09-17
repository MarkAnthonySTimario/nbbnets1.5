<template>
  <div>
      <mbdSelector @selected="selectedMBDAgency" :walkinDates="true"></mbdSelector>
      <div class="row">
          <div class="col-lg-8">
              <div class="panel panel-warning">
                <div class="panel-heading">Blood Processing</div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">MBD / Walk-in</label>
                            <div class="col-lg-7 input-group">
                                <input @click.prevent="selectMBDAgency" type="text" class="form-control input-sm" placeholder="Click here to select MBD/Walk-in" readonly v-if="!sched.agency_cd" style="background-color:#fff;">
                                <div class="form-control input-sm" v-if="sched.agency_cd" @click.prevent="selectMBDAgency">
                                    <span v-if="sched.agency_name == 'Shared'">{{sched.agency_name}}</span>
                                    <span v-if="sched.agency_name == 'Walk-in'">{{sched.agency_name}} - FROM {{sched.from}} TO {{sched.to}}</span>
                                    <span v-if="sched.agency_name != 'Walk-in' && sched.agency_name != 'Shared'">{{sched.agency_name}} - {{sched.donation_dt.substr(0,10)}}</span>
                                </div>
                                <div class="input-group-btn">
                                    <button class="btn btn-default btn-sm" @click.prevent="selectMBDAgency"><span class="glyphicon glyphicon-search"></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-condensed table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="30" class="text-center" @click="selectAll = !selectAll"><input type="checkbox"  v-model="selectAll"></th>
                            <th>Donation ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!sched.agency_cd">
                            <td colspan="2" class="text-center">Select MBD / Walk-in</td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="2" class="text-center"><loadingInline v-if="loading" label="Please wait, fetching Donation IDs"></loadingInline></td>
                        </tr>
                        <tr v-for="d in donations" :key="d.donation_id">
                            <td class="text-center"><input type="checkbox" v-model="selected" :value="d"></td>
                            <td>{{d.donation_id}}</td>
                        </tr>
                        <tr v-if="!loading && (sched.sched_id && !donations.length)">
                            <td class="text-center" colspan="2">No Available Donations</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><button class="btn btn-primary" @click.prevent="$emit('hasSelected',selected)" :disabled="!selected.length">Proceed</button></td>
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
  props : ['updated'],
  data(){
      let {sched} = this.$store.state;
      return {
          sched, selected : [], donations : [], loading : false
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
          if(!this.sched.sched_id){
              return;
          }
          this.loading = true;
          this.$http.post(this,"processing/list",{
              sched : this.sched, facility_cd : this.$session.get('user').facility_cd 
          })
          .then(({data}) => {
              this.donations = _.orderBy(data,['donation_id']);
              this.loading = false;
          });
      }
  },
  watch : {
      sched(){
          this.$store.state.sched = this.sched;
          this.fetchDonations();
      },
      updated(){
        //   console.log("but why");
          let {sched} = this.$store.state;
          this.sched = sched;
          this.selected = [];
          this.donations = [];
          this.loading = false;
          this.fetchDonations();
      }
  },
  computed : {
    selectAll: {
        get: function () {
            return this.donations ? this.selected.length == this.donations.length : false;
        },
        set: function (value) {
            var selected = [];

            if (value) {
                this.donations.forEach(function (donation) {
                    selected.push(donation);
                });
            }

            this.selected = selected;
        }
    }
  }
}
</script>