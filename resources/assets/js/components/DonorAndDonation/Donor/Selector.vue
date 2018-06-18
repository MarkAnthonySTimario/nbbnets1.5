<template>
  <div>
      <div class="row">
          <div class="col-lg-12">
              <div class="panel panel-success table-responsive">
                  <div class="panel-heading">Search Donor</div>
                  <div class="panel-body form-horizontal">
                    <label for="" class="control-label col-lg-2">Donor Name</label>
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-4"><input type="text" class="form-control input-sm" placeholder="First" v-model="fname" v-on:keyup.enter="doSearch"></div>
                            <div class="col-lg-4"><input type="text" class="form-control input-sm" placeholder="Middle" v-model="mname" v-on:keyup.enter="doSearch"></div>
                            <div class="col-lg-4"><input type="text" class="form-control input-sm" placeholder="Last" v-model="lname" v-on:keyup.enter="doSearch"></div>
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <button class="btn btn-default btn-sm" @click.prevent="doSearch">Search</button>
                    </div>
                  </div>
                  <table class="table table-bordered table-hover table-condensed">
                      <thead>
                          <tr>
                              <th>Donor ID</th>
                              <th width="100">Status</th>
                              <th>Name</th>
                              <th>Gender</th>
                              <th>Birth Date</th>
                              <th>Region</th>
                              <th>Province</th>
                              <th>City</th>
                              <th>Barangay</th>
                              <th>Address</th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-if="!attempt && !resultloading">
                              <td class="text-center" colspan="11">Search Donor</td>
                          </tr>
                          <tr v-if="attempt && donors.length == 0 && !resultloading">
                              <td class="text-center" colspan="11">Donor not Found</td>
                          </tr>
                          <tr v-if="resultloading">
                              <td class="text-center" colspan="11"><loadingInline label="Please wait, searching.."></loadingInline></td>
                          </tr>
                          <tr v-for="d in donors" :key="d.seqno" v-if="!resultloading" class="result-row">
                              <td style="font-size:12px;">{{d.donor_id}}</td>
                              <td nowrap>
                                  <b class="text-success" style="color:green;" v-if="(!d.donation_stat || d.donation_stat == 'Y')">May Donate</b>
                                  <b class="text-danger" v-if="(d.donation_stat == 'N')">Cannot Donate</b>
                              </td>
                              <td>{{d.fname}} {{d.mname}} {{d.lname}}</td>
                              <td>{{d.gender | gender}}</td>
                              <td>{{d.bdate}}</td>
                              <td>{{(d.region ? d.region.regname : '')}}</td>
                              <td>{{(d.province ? d.province.provname : '')}}</td>
                              <td>{{(d.city ? d.city.cityname : '')}}</td>
                              <td>{{(d.barangay ? d.barangay.bgyname : '')}}</td>
                              <td>{{d.home_no_st_blk}}</td>
                              <td nowrap>
                                  <router-link :to="('./donor/'+d.seqno+'?donation_id='+donation_id)" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-search"></span></router-link>
                                  <router-link :to="('./donor/'+d.seqno+'/update?donation_id='+donation_id)" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-pencil"></span></router-link>
                              </td>
                          </tr>
                      </tbody>
                  </table>
                  <div class="panel-footer">
                      <router-link :to="'donor/create?donation_id=' + donation_id" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> New Donor</router-link>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>

export default {
  props : ['mbd'],
  data(){
      let {fname,mname,lname} = this.$store.state.MBD.donor_search;
      let {currentRoute : {query : {donation_id}}} = this.$router;
      if(donation_id == null || donation_id == undefined || donation_id == 'null' || donation_id == 'undefined'){
          donation_id = null;
      }
      return { donation_id, loading : false, resultloading : false, donors : [], 
      fname, mname, lname,
      attempt : 0};
  },
  computed : {
      search(){
          return (this.fname || this.mname || this.lname);
      }
  },
  methods : {
      doSearch(){
          if(!this.fname && !this.mname && !this.lname){
              return false;
          }
          let {fname,mname,lname} = this;
          this.resultloading = true;
          this.rememberSearch();
          this.$http.post(this,"donors",{
              fname, mname, lname
          })
          .then(({data}) => {
              this.donors = _.sortBy(data,['fname']);
              this.resultloading = false;
              this.attempt++;
          })
          .catch(error => {
              this.$store.state.error = error;
          });

      },
      rememberSearch(){
          let {fname,mname,lname} = this.$data;
          this.$store.state.MBD.donor_search = {
              fname, mname, lname
          };
      }
  },
  mounted(){
      this.doSearch();
  }
}
</script>

<style scoped>
.result-row{
    font-size: 12px;
}
.result-row td{
    vertical-align:middle !important;
}
.result-row:hover{
    background-color: #aabbcc !important;
}
</style>