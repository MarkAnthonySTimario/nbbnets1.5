<template>
  <div class="row">
      <div class="col-lg-8">
          <div class="panel panel-success">
              <div class="panel-heading">
                  Mobile Blood Donations
              </div>
              <div class="panel-body">
                  <div class="col-lg-4">
                      <router-link to="/MBD/create" class="btn btn-default btn-sm">Create MBD</router-link>
                  </div>
                  <div class="col-lg-8 form-horizontal">
                      <div class="form-group">
                        <label for="" class="col-lg-6 control-label">Search</label>
                        <div class="col-lg-6"><input type="text" class="form-control input-sm" v-model="search"></div>
                      </div>
                  </div>
              </div>
              <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                      <thead>
                          <tr class="text-center">
                              <th>Agency</th>
                              <th>Date of MBD</th>
                              <th width="50"></th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-if="untouched">
                              <td class="text-center" colspan="3">Search Agency Name</td>
                          </tr>
                          <tr v-if="(search && loading)">
                              <td class="text-center" colspan="3"><loadingInline label="Please wait, searching.."></loadingInline></td>
                          </tr>
                          <tr v-if="(search && !loading && !mbds.length)">
                              <td class="text-center" colspan="3">No MBD Records Found</td>
                          </tr>
                          <tr v-if="mbds && !loading" v-for="mbd in mbds" :key="mbd.sched_id">
                              <td>{{mbd.agency_name}}</td>
                              <td>{{mbd.donation_dt.substr(0,10)}}</td>
                              <td>
                                  <router-link :to="('/MBD/' + mbd.sched_id)" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-th-list"></span></router-link>
                                  <!-- <router-link :to="('/MBD/update/' + mbd.sched_id)" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></router-link> -->
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
</template>

<script>

export default {
  data(){
      return {
          untouched : true,
          search : this.$store.state.MBD.search,
          loading : false,
          mbds : [],
          headers : { headers : { Authorization : this.$store.state.token.access_token }}
      }
  },
  watch : {
      search(){
          this.untouched = false;
          if(this.search){
              this.loading = true;
              this.doSearch(this);
          }
      }
  },
  methods : {
      doSearch : _.debounce((that) => {
          that.executeSearch(that);
      },1000),
      executeSearch(that){
          let {search} = that;
          
          if(!search){
              return false;
          }
          that.$http.post(that,"mbd",{
              search, facility_cd : that.$store.state.user.facility_cd
          })
          .then(({data}) => {
              that.mbds = _.orderBy(data,['donation_dt'],['desc']);
              that.loading = false;
          })
          .catch(error => {
              that.$store.state.error = error;
          });
      }
  },
  mounted(){
      if(this.search){
          this.untouched = false;
          this.loading = true;
          this.executeSearch(this);

      }
  },
  destroyed(){
      this.$store.state.MBD.search = this.search;
  }
}
</script>