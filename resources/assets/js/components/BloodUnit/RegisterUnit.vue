<template>
  <div class="row">
      <mbdSelector @selected="selectedMBDAgency"></mbdSelector>
      <div class="col-lg-8" v-show="!verify">
          <div class="panel panel-success">
              <div class="panel-heading">Register Blood Units</div>
              <div class="panel-body">
                  <div class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3">MBD / Walk-in</label>
                        <div class="col-lg-7 input-group">
                            <input @click.prevent="selectMBDAgency" type="text" class="form-control input-sm" placeholder="Click here to select MBD/Walk-in" readonly v-if="!sched.agency_cd" style="background-color:#fff;">
                            <div class="form-control input-sm" v-if="sched.agency_cd" @click.prevent="selectMBDAgency">
                                <span v-if="sched.agency_name == 'Walk-in'">{{sched.agency_name}}</span>
                                <span v-if="sched.agency_name != 'Walk-in'">{{sched.agency_name}} - {{sched.donation_dt.substr(0,10)}}</span>
                            </div>
                            <div class="input-group-btn">
                                <button class="btn btn-default btn-sm" @click.prevent="selectMBDAgency"><span class="glyphicon glyphicon-search"></span></button>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>
              <table class="table table-bordered table-striped">
                  <thead>
                      <tr>
                          <th width="30" class="text-center">#</th>
                          <th width="300">Donation ID</th>
                          <th colspan="4" class="text-center">Blood Bag</th>
                          <th width="30"></th>
                      </tr>
                      <tr>
                          <th colspan="2"></th>
                          <th class="text-center">S</th>
                          <th class="text-center">D</th>
                          <th class="text-center">T</th>
                          <th class="text-center">Q</th>
                          <th></th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr v-for="(r,i) in rows" :key="i">
                          <td class="text-center">{{(i+1)}}</td>
                          <td>
                              <input type="text" class="form-control input-sm" maxlength="16" v-model="r.donation_id" v-bind:keyup="(r.donation_id = r.donation_id ? r.donation_id.toUpperCase() : null)">
                          </td>
                          <td class="text-center"  @click="r.bag = 'S'"><input type="radio" v-model="r.bag" value="S"></td>
                          <td class="text-center"  @click="r.bag = 'D'"><input type="radio" v-model="r.bag" value="D"></td>
                          <td class="text-center"  @click="r.bag = 'T'"><input type="radio" v-model="r.bag" value="T"></td>
                          <td class="text-center"  @click="r.bag = 'Q'"><input type="radio" v-model="r.bag" value="Q"></td>
                          <td>
                              <button class="btn btn-danger btn-xs" @click="rows.splice(i,1)"><span class="glyphicon glyphicon-remove"></span></button>
                          </td>
                      </tr>
                  </tbody>
                  <tfoot>
                      <tr>
                          <td colspan="7">
                              <div class="row">
                                  <div class="col-lg-4">
                                    <button class="btn btn-primary btn-sm" @click.prevent="validateForm" :disabled="loading">Register Units</button>
                                    <button class="btn btn-default btn-sm" @click.prevent="newRow" :disabled="loading">New Row</button>
                                  </div>
                                    <loadingInline class="col-lg-4" v-if="loading" label="Please wait.."></loadingInline>
                                    <div class="col-lg-2 pull-right">
                                        <button class="btn btn-danger btn-sm" @click.prevent="clearAll" :disabled="loading">Clear All</button>
                                    </div>
                              </div>
                          </td>
                      </tr>
                  </tfoot>
              </table>
          </div>
      </div>
      <div class="col-lg-4" v-if="err.length" v-show="!verify">
          <div class="panel panel-danger">
              <div class="panel-heading">Some errors where found</div>
              <div class="panel-body"  style="font-size:12px;">
                  <div v-for="(e,i) in err" :key="i" v-html="e"></div>
              </div>
          </div>
      </div>
      <verifier v-if="verify" @proceed="processForm" @cancel="verify=false"></verifier>
  </div>
</template>

<script>
export default {
  data(){
      let rows = [];
      for(let i = 0; i < 10; i++){
          rows.push(this.blankRow());
      }
      let {sched} = this.$store.state;
      return {
          rows, err : [], loading : false, verify : false, sched
      }
  },
  mounted(){
      
  },
  methods : {
      validateForm(){
          this.err = [];
          if(!this.sched.sched_id){
              this.err.push("Select MBD / Agency first");
          }
          
          _.remove(this.rows, (item) => {
              if(!item.donation_id){
                  return true;
              }
          });


          this.rows.forEach((r,i) => {
              if(!r.donation_id){
                  this.err.push("Blank Donation ID at row "+(i+1));
              }else if(r.donation_id.length != 16){
                  this.err.push("Invalid Donation ID at row "+(i+1));
              }else if(!r.donation_id.startsWith('NVBSP')){
                  this.err.push("Invalid Donation ID at row "+(i+1));
              }
          });

          if(this.err.length == 0){
              this.verify = true;
          }
      },
      processForm(verifier){
          this.loading = true;
          let donation_ids = _.map(this.rows,'donation_id');
          let that = this;
          this.$http.post(this,"register/checkDonationIDs",{donation_ids,verifier})
          .then(({data}) => {
              if(data.length > 0){
                  this.loading = false;
                  this.err.push(data.join("<br/>"));
              }else{
                  that.saveChanges(verifier);
              }
          })
      },
      saveChanges(verifier){
          let {rows} = this;
          let {user_id,facility_cd} = this.$session.get('user');
          let {sched_id} = this.sched;
          this.$http.post(this,"donation/register",{rows,verifier,user_id,facility_cd,sched_id})
          .then(({data}) => {
              this.$store.state.msg = {
                  content : 'Blood Unit has been registered!',
                  type : 'success'
              };
              this.loading = false;
              this.clearAll();
          });
      },
      clearAll(){
        let rows = [];
        for(let i = 0; i < 10; i++){
            rows.push(this.blankRow());
        }
        this.rows = rows;
      },
      newRow(){
          this.rows.push(this.blankRow());
      },
      blankRow(){
          return {
              donation_id : null,
              bag : 'S'
          };
      },
      selectMBDAgency(){
          $("#MBDSelector").modal("show");
      },
      selectedMBDAgency(sched){
          this.sched = sched;
          $("#MBDSelector").modal("hide");
      }
  },
  watch : {
      sched(){
          this.$store.state.sched = this.sched;
      }
  }
}
</script>

<style scoped>
.control-label {
    margin-top:-.5em;
    font-size: 14px;
}

</style>
