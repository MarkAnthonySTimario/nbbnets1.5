<template>
  <div>
        <div class="modal fade" tabindex="-1" id="SelectDiscardReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Blood Unit Discard</h4>
                </div>
                <div class="modal-body" v-show="!loading && !verify">
                    <div v-if="!verify" class="form-horizontal">
                        <div class="form-group">
                            <label for="" class="control-label col-lg-4">Facility</label>
                            <div class="col-lg-8"><div type="text" class="form-control input-sm">{{user.facility.facility_name}}</div></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-4">Date of Discard</label>
                            <div class="col-lg-8">
                                <div class="form-control input-sm">{{null | date-today}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-4">Discard By</label>
                            <div class="col-lg-8">
                                <div class="form-control input-sm">{{user.user_fname | uppercase}} {{user.user_mname | uppercase}} {{user.user_lname | uppercase}}</div>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="" class="control-label col-lg-4">Reason for Discard</label>
                            <div class="col-lg-8">
                                <select v-model="reason" class="form-control input-sm">
                                    <option v-for="(r,i) in reasons" :key="i" :value="i">{{r}}</option>
                                </select>
                            </div>
                        </div>
                        <div :class="('form-group' + (reason == 'OTH' ? ' required' : ''))">
                            <label for="" class="control-label col-lg-4">Remarks</label>
                            <div class="col-lg-8"><textarea v-model="remarks" cols="30" rows="5" class="form-control"></textarea></div>
                        </div>
                    </div>
                    <verifier v-if="verify" @cancel="verify = false" @proceed="submitForm"></verifier>
                </div>
                <div class="modal-body" v-if="loading && !verify">
                    <loadingInline label="Please wait, loading."></loadingInline>
                </div>
                <div class="modal-body" v-if="verify">
                    <verifier :full="true" @proceed="submitForm" @cancel="verify = false"></verifier>
                </div>
                <div class="modal-footer" v-if="!loading">
                    <button type="button" :disabled="!checkData" class="btn btn-primary" @click="validateForm">Done</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
  </div>
</template>

<script>
export default {
  props : ['refresh','units'],
  data(){
      let user = this.$session.get('user');
      return {
          loading : false, verify : false, user, reasons : [], reason : null, remarks : null
      };
  },
    mounted(){
        this.loading = true;
        this.$http.get(this,"keyvalues/discardreasons")
        .then(({data}) => {
            this.reasons = data;
            this.loading = false;
        });
    },
  computed : {
      checkData(){
          let {reason,remarks} = this;
          if(!reason){
              return false;
          }else if(reason == 'OTH' && !remarks){
              return false;
          }
          return true;
      }
  },
  methods : {
      validateForm(){
          if(!this.checkData){
              return;
          }
          this.loading = true;
          this.verify = true;
      },
      submitForm(verifier){
          $("#SelectDiscardReason").modal("hide");
          this.verify = false;
          this.loading = true;
          let {units,reason,remarks} = this;
          let {user_id,facility_cd} = this.$session.get('user');
          this.$http.post(this,"discard/save",{
              units,reason,remarks,verifier,user_id,facility_cd
          })
          .then(({data}) => {
              this.$store.state.msg = {
                  content : 'Blood Units Discarded successfully',
                  type : 'success'
              };
              this.$emit("done",true);
          });
      }
  }
}
</script>
