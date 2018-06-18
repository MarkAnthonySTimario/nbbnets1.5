<template>
  <div>
      <div class="row">
          <loading v-if="loading"></loading>
          <div class="col-lg-8" v-if="!loading">
              <div class="panel panel-success">
                  <div class="panel-heading">
                      Edit Agency
                      <router-link to="/Agency" class="pull-right btn btn-xs btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Back to List</router-link>
                  </div>
                  <div class="panel-body">
                      <form action="" class="form-horizontal">
                          <div class="form-group required">
                              <label for="" class="control-label col-lg-4">Agency Name</label>
                              <div class="col-lg-8">
                                  <input type="text" class="form-control input-sm" v-model="agency_name">
                              </div>
                          </div>

                          <div class="form-group required">
                              <label for="" class="control-label col-lg-4">Donor Recruitment Officer</label>
                              <div class="col-lg-8"><input type="text" class="form-control input-sm" v-model="owner"></div>
                          </div>

                          <div class="form-group required">
                              <label for="" class="control-label col-lg-4">Contact Person</label>
                              <div class="col-lg-8"><input type="text" class="form-control input-sm" v-model="contact_person"></div>
                          </div>

                          <addressPicker :defs="addresspicker" @onchange="newAddress"></addressPicker>

                          <div class="form-group">
                              <label for="" class="control-label col-lg-4">Zip Code</label>
                              <div class="col-lg-8"><input type="text" class="form-control input-sm" v-model="adg_zip"></div>
                          </div>

                          <!-- div.form-group>label.control-label.col-lg-4/div.col-lg-8>input.form-control input-sm -->
                          <div class="form-group">
                              <label for="" class="control-label col-lg-4">Contact Designation</label>
                              <div class="col-lg-8"><input type="text" class="form-control input-sm" v-model="designation"></div>
                          </div>

                          <div class="form-group">
                              <label for="" class="control-label col-lg-4">Mobile No.</label>
                              <div class="col-lg-8"><input type="text" class="form-control input-sm" v-model="mobile_tel"></div>
                          </div>
                          
                          <div class="form-group">
                              <label for="" class="control-label col-lg-4">Telephone No.</label>
                              <div class="col-lg-8"><input type="text" class="form-control input-sm" v-model="adg_tel"></div>
                          </div>

                          <div class="form-group">
                              <label for="" class="control-label col-lg-4">Email</label>
                              <div class="col-lg-8"><input type="text" class="form-control input-sm" v-model="email_ad"></div>
                          </div>

                          <div class="form-group">
                              <label for="" class="control-label col-lg-4">Fax no.</label>
                              <div class="col-lg-8"><input type="text" class="form-control input-sm" v-model="adg_fax"></div>
                          </div>

                          <div class="form-group">
                              <div class="col-lg-8 col-lg-offset-4">
                                  <button class="btn btn-default btn-sm" @click.prevent="update">Update Agency</button>
                              </div>
                          </div>

                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>

export default {
  props : ['agency_cd'],
  mounted(){
      this.loading = true;
      this.$http.get(this,"agency/info/"+this.agency_cd)
      .then(({data}) => {
          
        this.data = _.merge(this.$data,data);
        this.loading = false;
      })
      .catch(error => {
          this.$store.state.error = error;
      });
  },
  data(){
      return {
        loading : false,
        facility_cd : null, agency_name : null, owner : null, contact_person : null, 
        adg_region : null, adg_city : null, adg_prov : null, adg_bgy : null,
        adg_zip : null, designation : null, mobile_tel : null, adg_tel : null, adg_fax : null, email_ad : null
      }
  },
  computed : {
      addresspicker(){
          let {adg_region,adg_prov,adg_city,adg_bgy} = this;
          return {
              region : adg_region, province : adg_prov, city : adg_city, barangay : adg_bgy
          };
      }
  },
  methods : {
      update(){
          this.loading = true;
          let data = this.$data;
          data.updated_by = this.$store.state.user.user_id;
          this.$http.post(this,'agency/update/'+this.agency_cd,data)
          .then(({data}) => {
              this.$router.replace('/Agency/'+this.agency_cd);
              this.loading = false;
          }).
          catch(error => {
              this.$store.state.error = error;
          });
      },
      newAddress({region,province,city,barangay}){
          this.adg_region = region;
          this.adg_prov = province;
          this.adg_city = city;
          this.adg_bgy = barangay;
      }
  }
}
</script>