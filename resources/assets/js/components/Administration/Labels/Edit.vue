<template>
  <div>
      <div class="row">
          <div class="col-lg-6">
            <br/>
              <div class="form-horizontal">
                  <div class="form-group">
                      <label for="" class="control-label col-lg-2">Facility</label>
                      <div class="col-lg-8">
                          <select class="form-control input-sm" v-model="facility">
                              <option :key="f.facility_cd" :value="f" v-for="f in facilities">{{f.facility_name}}</option>
                          </select>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="" class="control-label col-lg-2">Template</label>
                      <div class="col-lg-10" v-if="template">
                          <html-editor :config="{width:420}" :init="template" @update="(raw) => {template = raw}"></html-editor>
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-lg-10 col-lg-offset-2">
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
          <div class="col-lg-6">
              <div v-html="output" style="width:375px;">

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
  props : ['edit'],
  data(){
      let {edit} = this;
      return {
          facilities : [], facility : edit.facility, loading : false,
          template : edit.template
      }
  },
  computed : {
      output(){
          let {template} = this;
          template = template.replaceAll("{{FACILITY_NAME}}",this.facility ? this.facility.facility_name : 'Department of Health');
          template = template.replaceAll("{{BARCODE}}",'<div style="background:#fff;width:100%;height:50px;text-align:center;vertical-align:middle;"><img src="images/sample-barcode.jpg" width="320" height="45" /></div>');
          template = template.replaceAll("{{ABO}}","B");
          template = template.replaceAll("{{RH}}","Positive");
          template = template.replaceAll("{{COMPONENT}}","FRESH FROZEN PLASMA");
          template = template.replaceAll("{{VOLUME}}","150");
          template = template.replaceAll("{{COLLECTION_DATE}}","January 06, 2018");
          template = template.replaceAll("{{EXPIRATION_DATE}}","January 06, 2019 23:59:00");
          template = template.replaceAll("{{STORE}}","Store at -18 to -89 &deg;C");
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