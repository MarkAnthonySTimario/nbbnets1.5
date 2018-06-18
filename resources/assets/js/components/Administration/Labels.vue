<template>
  <div>
      <div class="row" v-if="!create && !edit">
          <div class="col-lg-12">
              <div class="panel panel-success">
                <div class="panel-heading">Blood Label Templates
                    <button class="btn btn-default btn-sm pull-right" @click="create = true">Add New Template</button>
                </div>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Facility Code</th>
                            <th>Name of Facility</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <td colspan="3" class="text-center"><loadingInline label="Loading templates.."></loadingInline></td>
                        </tr>
                        <tr v-if="!loading && templates.length" v-for="t in templates" :key="t.id" style="font-size:14px;">
                            <td>{{t.facility.facility_cd}}</td>
                            <td>{{t.facility.facility_name}}</td>
                            <td>
                                <button @click="edit = t;" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>
                                <button @click="remove(t)" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button>
                            </td>
                        </tr>
                        <tr v-if="!loading && !templates.length">
                            <td colspan="3" class="text-center">No Available Templates Yet</td>
                        </tr>
                    </tbody>
                </table>
              </div>
          </div>
      </div>
      
      <div class="row" v-if="create">
          <div class="col-lg-12">
              <create :facilities="facilities" @cancel="create = null;" @complete="create = null; fetchTemplates()"></create>
          </div>
      </div>

      <div class="row" v-if="edit">
          <div class="col-lg-12">
              <edit-template :facilities="facilities" :edit="edit" @cancel="edit = null;" @complete="edit = null; fetchTemplates()"></edit-template>
          </div>
      </div>
  </div>
</template>

<script>
import Create from "./Labels/Create.vue";
import EditTemplate from "./Labels/Edit.vue";

export default {
  components : {Create,EditTemplate},
  data(){
      return {
          loading :false, templates : [], create : false, edit : null, facilities : []
      }
  },
  mounted(){
      this.fetchTemplates();
  },
  methods : {
      fetchTemplates(){
          this.loading = true;
            this.$http.get(this,"admin/templates")
            .then(({data}) => {
                this.templates = data;
                this.loading = false;
            })
            this.$http.get(this,"admin/facility/listsimple")
            .then(({data}) => {
                this.facilities = data;
            })

      },
      remove(t){

      }
  }
}
</script>
