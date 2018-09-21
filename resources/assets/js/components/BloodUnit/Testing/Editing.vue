<template>
  <div>
      <div class="row" v-if="!verify" v-show="!loadingPage">
          <div class="col-lg-8">
              <div class="panel panel-success">
                  <div class="panel-heading">
                      Blood Testing
                  </div>
                  <table class="table table-bordered table-condensed">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Donation ID</th>
                              <th v-for="(exam_name,exam_cd) in exams" :key="exam_cd">{{exam_name}}</th>
                              <td width="50"></td>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(donation,i) in donations" :key="donation.donation_id">
                              <td>{{(i+1)}}</td>
                              <td>{{donation.donation_id}}</td>
                              <td v-for="(exam_name,exam_cd) in exams" :key="exam_cd">
                                <select class="form-control input-sm" v-model="donation[exam_cd]" @change="checkData">
                                    <option value="N">{{ exam_cd != 'MALA' ? 'NR' : 'Negative'}}</option>
                                    <option value="R">{{ exam_cd != 'MALA' ? 'R' : 'Positive'}}</option>
                                </select>                                  
                              </td>
                              <td>
                                  <button @click="removeFromSelection(donation)" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button>
                              </td>
                          </tr>
                      </tbody>
                      <tfoot>
                          <tr>
                              <td :colspan="(5 + Object.keys(exams).length)" class="text-right">
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
          donations, loading : false, verify : false, loadingPage : false, exams : [], valid : false
      };
  },
  mounted(){
      
      this.loadingPage = true;
      this.$http.get(this,"keyvalues/exams")
      .then(({data}) => {
          this.loadingPage = false;
          this.exams = data;
            for(let exam_cd in this.exams){
                this.donations.forEach(d => {
                    d[exam_cd] = null
                });
            }
      });

    //   this.checkData();
  },
  methods : {
        checkData(){
            let noResult = 0;
            this.donations.forEach(d => {
                for(let exam_cd in this.exams){
                    if(!d[exam_cd]){
                        noResult++;
                    }
                }
            })
            this.valid = noResult == 0;
        },
      validateForm(){
          this.loading = true;
          this.verify = true;
      },
      submitForm(verifier){
          this.verify = false;
          let {donations} = this;
          let user = this.$session.get('user');
          let {user_id,facility_cd} = user;
          this.$http.post(this,"testing/save",{
              donations,user_id,facility_cd,verifier
          })
          .then(({data}) => {
              this.$store.state.msg = {
                  content : 'Blood Testing has been saved successfully',
                  type : 'success'
              };
              this.$emit("cancel");
          });
      },
      removeFromSelection(donation){
          this.donations = _.filter(this.donations,d=>{return d.donation_id != donation.donation_id})
          this.checkData()
      }
  }
}
</script>

<style scoped>
select:focus{
    background: #f1f1f1;
}
</style>
