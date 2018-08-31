<template>
  <div>
      <div class="row" v-if="!verify" v-show="!loadingPage">
          <div class="col-lg-8">
              <div class="panel panel-success">
                  <div class="panel-heading">
                      Zika Blood Testing
                  </div>
                  <table class="table table-bordered table-condensed">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Donation ID</th>
                              <th>Zika Test Result</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(donation,i) in donations" :key="donation.donation_id">
                              <td>{{(i+1)}}</td>
                              <td>{{donation.donation_id}}</td>
                              <td>
                                  <select class="form-control input-sm" v-model="donation.zika">
                                      <option value="N">Negative</option>
                                      <option value="R">Positive</option>
                                  </select>
                              </td>
                          </tr>
                      </tbody>
                      <tfoot>
                          <tr>
                              <td :colspan="(4 + Object.keys(exams).length)" class="text-right">
                                <loadingInline v-if="loading" label="Please wait, loading.."></loadingInline>
                                <button class="btn btn-default" :disabled="!valid || loading" @click="validateForm">Save Changes</button>
                                <button class="btn btn-danger" :disabled="loading" @click.prevent="donations = []; $emit('cancel',null)">Cancel</button>
                              </td>
                          </tr>
                      </tfoot>
                  </table>
              </div>
          </div>
      </div>
      <verifier v-if="verify" @cancel="verify = false" @proceed="submitForm"></verifier>
      <loading v-if="loadingPage"></loading>
  </div>
</template>

<script>
export default {
  props : ['donationids'],
  data(){
      let donations = [];
      this.donationids.forEach(donation_id => {
          donations.push({
              donation_id : donation_id
          });
      });
      return {
          donations, loading : false, verify : false, loadingPage : false, exams : []
      };
  },
  methods : {
      validateForm(){
          this.loading = true;
          this.verify = true;
      },
      submitForm(verifier){
          this.verify = false;
          let {donations} = this;
          let user = this.$session.get('user');
          let {user_id,facility_cd} = user;
          this.$http.post(this,"testing/savezika",{
              donations,user_id,facility_cd,verifier
          })
          .then(({data}) => {
              this.$store.state.msg = {
                  content : 'Zika Test Results has been saved successfully',
                  type : 'success'
              };
              this.$emit("cancel");
          });
      }
  },
  computed : {
      valid(){
          return _.filter(this.donations,d=>!d.zika).length == 0
      }
  }
}
</script>

<style scoped>
select:focus{
    background: #f1f1f1;
}
</style>
