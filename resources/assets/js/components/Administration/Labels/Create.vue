<template>
  <div>
      <div class="row">
          <div class="col-lg-6">
              <div class="form-horizontal">
                  <div class="form-group">
                      <label for="" class="control-label col-lg-2">Template</label>
                      <div class="col-lg-10" v-if="template">
                          <html-editor :config="{width:420}" :init="template" @update="(raw) => {template = raw}"></html-editor>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="" class="control-label col-lg-2">Facility</label>
                      <div class="col-lg-8">
                          <select class="form-control input-sm" v-model="facility">
                              <option :key="f.facility_cd" :value="f" v-for="f in facilities">{{f.facility_name}}</option>
                          </select>
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
          <div class="col-lg-6" style="background:#c1c1c1;padding:0px !important;">
              <div v-html="output" style="width:375px;">
              </div>
          </div>
      </div>
  </div>
</template>

<script>

export default {
  props : ['facilities'],
  data(){
      return {
          facility : null, loading : true,
          template : ''
      }
  },
  computed : {
      output(){
        //   Source found in mixin
        return this.prepareTemplate(this.template);
      }
  },
  mounted(){
      this.facility = _.find(data,{facility_cd:'00000'});
      this.$http.get(this,"labeltemplate/gettemplate/00000")
      .then(({data}) => {
          this.template = data;
          this.loading = false;
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
