<template>
  <div>
      <div class="row" v-if="!verify">
          <div class="col-lg-8">
              <div class="panel panel-success">
                  <div class="panel-heading">
                      Blood Typing
                  </div>
                  <table class="table table-bordered table-condensed">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Donation ID</th>
                              <th>ABO</th>
                              <th>Rh Type</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(donation,i) in donations" :key="donation.donation_id">
                              <td>{{(i+1)}}</td>
                              <td>{{donation.donation_id}}</td>
                              <td>
                                  <select class="form-control input-sm" v-model="donation.abo">
                                      <option value="A">A</option>
                                      <option value="B">B</option>
                                      <option value="AB">AB</option>
                                      <option value="O">O</option>
                                  </select>
                              </td>
                              <td>
                                  <select class="form-control input-sm" v-model="donation.rh">
                                      <option value="pos">Positive</option>
                                      <option value="neg">Negative</option>
                                  </select>
                              </td>
                          </tr>
                      </tbody>
                      <tfoot>
                          <tr>
                              <td colspan="4" class="text-right">
                                  <loadingInline v-if="loading" label="Please wait, loading.."></loadingInline>
                                <button class="btn btn-default" :disabled="checkData || loading" @click="validateForm">Save Changes</button>
                                <button class="btn btn-danger" :disabled="loading" @click.prevent="donations = []; $emit('cancel',null)">Cancel</button>
                              </td>
                          </tr>
                      </tfoot>
                  </table>
              </div>
          </div>
      </div>
      <verifier v-if="verify" @cancel="verify = false" @proceed="submitForm"></verifier>
  </div>
</template>

<script>
export default {
  props : ['donationids'],
  data(){
      let donations = [];
      this.donationids.forEach(donation_id => {
          donations.push({
              donation_id : donation_id,
              abo : null, 
              rh : null
          });
      });
      return {
          donations, loading : false, verify : false 
      };
  },
  computed : {
      checkData(){
          let noABO = _.filter(this.donations,{abo : null});
          let noRH = _.filter(this.donations,{rh : null});
          let incomplete = noABO.length + noRH.length;
          return incomplete > 0;
      }
  },
  methods : {
      validateForm(){
          if(this.checkData){
              return;
          }
          this.loading = true;
          this.verify = true;
      },
      submitForm(verifier){
          this.verify = false;
          let {donations} = this;
          let user = this.$session.get('user');
          let {user_id,facility_cd} = user;
          this.$http.post(this,"typing/save",{
              donations,user_id,facility_cd,verifier
          })
          .then(({data}) => {
              this.$store.state.msg = {
                  content : 'Blood Typing has been saved successfully',
                  type : 'success'
              };
              this.$emit("cancel");
          });
      }
  }
}
</script>

<style scoped>
select:focus{
    background: #f1f1f1;
}
</style>
