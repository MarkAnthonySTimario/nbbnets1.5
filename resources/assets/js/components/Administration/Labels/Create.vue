<template>
  <div>
      <div class="row">
          <div class="col-lg-4 col-lg-offset-2">
              <div v-html="output">
              </div>
          </div>
          <div class="col-lg-12">
            <br/>
              <div class="form-horizontal">
                  <div class="form-group">
                      <label for="" class="control-label col-lg-2">Facility</label>
                      <div class="col-lg-10">
                          <select class="form-control input-sm" v-model="facility">
                              <option :key="f.facility_cd" :value="f" v-for="f in facilities">{{f.facility_name}}</option>
                          </select>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="" class="control-label col-lg-2">Template</label>
                      <div class="col-lg-10"><textarea rows="10" class="form-control input-sm" v-model="template"></textarea></div>
                  </div>
                  <div class="form-group">
                      <div class="col-lg-2 col-lg-offset-2">
                          <button class="btn btn-default btn-sm" @click="createTemplate" :disabled="loading">Save</button>
                          <button class="btn btn-danger btn-sm" @click="$emit('cancel')" :disabled="loading">Cancel</button>
                      </div>
                      <div class="col-lg-8">
                          <loadingInline v-if="loading" label="Please wait, saving.."></loadingInline>
                      </div>
                  </div>
                  <!-- div.form-group>label.control-label.col-lg-4+div.col-lg-8>input.form-control.input-sm -->
              </div>
          </div>
          
      </div>
  </div>
</template>

<script>
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

export default {
  data(){
      return {
          facilities : [], facility : null, loading : false,
          template : '<div style="background: #e1e1e1;border:1px solid #000;padding:0px;width:356px;margin-left:auto;margin-right:auto;">\
                    <table style="width:100%;font-size: 8px;">\
                        <tr>\
                            <td width="100" style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;vertical-align:middle;">\
                                <img src="images/doh.png" width="50" style="margin-left:auto;margin-right:auto;" />\
                            </td>\
                            <td style="font-size: 10px;text-align: center;padding:2px;border-bottom:1px solid #000;">\
                                <!--FACILITY_NAME--><br/>\
                                Blood Bank and Transfusion Services<br/>\
                                Quezon Ave. Quezon City<br/>\
                                Tel. No. 1234567 local 123\
                            </td>\
                        </tr>\
                    </table>\
                    <!--BARCODE-->\
                    <table style="width:100%;font-size: 8px;border-top:1px solid #000;">\
                        <tr>\
                            <td rowspan="2" width="100" style="font-size:36px;font-weight: bold;vertical-align: middle;border-right:1px solid #000;border-bottom:1px solid #000;">\
                                <center>\
                                    <!--ABO-->\
                                </center>\
                            </td>\
                            <td rowspan="2" style="vertical-align: middle;border-right:1px solid #000;border-bottom:1px solid #000;">\
                                <center>Rh(D)<br/><!--RH--></center>\
                            </td>\
                            <td style="font-size:8px;border-bottom:1px solid #000;">\
                                Collection Date:<br/>\
                                <!--COLLECTION_DATE-->\
                            </td>\
                        </tr>\
                        <tr>\
                            <td style="font-size:8px;border-bottom:1px solid #000;">\
                                Expiration Date:<br/>\
                                <!--EXPIRATION_DATE-->\
                            </td>\
                        </tr>\
                        <tr>\
                            <td colspan="3" style="border-right:1px solid #000;border-bottom:1px solid #000;"><!--COMPONENT--> VOLUME: <!--VOLUME-->ml</td>\
                        </tr>\
                        <tr>\
                            <td colspan="2" style="border-right:1px solid #000;border-bottom:1px solid #000;">Human Blood Product</td>\
                            <td style="border-right:1px solid #000;border-bottom:1px solid #000;"><!--STORE--></td>\
                        </tr>\
                        <tr>\
                            <td colspan="2" style="border-right:1px solid #000;border-bottom:1px solid #000;">From a volunteer blood donor</td><td >CPDA-1 Anticoagulant</td>\
                        </tr>\
                        <tr>\
                            <td colspan="3" style="border-right:1px solid #000;"><p>NON-REACTIVE Donation tested for specific markers for HIV 1 & 2, hepatitis B & C, malaria and syphilis, ANTIBODY SCREEN: NEGATIVE</p></td>\
                        </tr>\
                    </table>\
                </div>'
      }
  },
  computed : {
      output(){
          let {template} = this;
          template = template.replaceAll("<!--FACILITY_NAME-->",this.facility ? this.facility.facility_name : 'Department of Health');
          template = template.replaceAll("<!--BARCODE-->",'<div style="background:#fff;width:100%;height:50px;text-align:center;vertical-align:middle;"><img src="images/sample-barcode.jpg" width="320" height="45" /></div>');
          template = template.replaceAll("<!--ABO-->","B");
          template = template.replaceAll("<!--RH-->","Positive");
          template = template.replaceAll("<!--COMPONENT-->","FRESH FROZEN PLASMA");
          template = template.replaceAll("<!--VOLUME-->","150");
          template = template.replaceAll("<!--COLLECTION_DATE-->","January 06, 2018");
          template = template.replaceAll("<!--EXPIRATION_DATE-->","January 06, 2019 23:59:00");
          template = template.replaceAll("<!--STORE-->","Store at -18 to -89 &deg;C");
          return template;
      }
  },
  mounted(){
      this.$http.get(this,"admin/facility/listsimple")
      .then(({data}) => {
          this.facilities = data;
      })
  },
  methods : {
      createTemplate(){
          this.loading = true;
          let {facility,template} = this;
          this.$http.post(this,"admin/savetemplate",{
              facility, template
          })
          .then(({data}) => {
              this.$emit("complete");
          })
      }
  }
}
</script>


<style scoped>
.control-label {
    margin-top:-.5em;
    font-size: 14px;
}
.form-group.required .control-label:after {
  content:"*";
  color:red;
}
.error {
    font-size:12px;
    margin-top:0.5em;
}
.collection-expiration{
    font-size:8px;
}
</style>