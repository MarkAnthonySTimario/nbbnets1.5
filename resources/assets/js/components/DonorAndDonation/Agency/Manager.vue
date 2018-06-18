<template>
  <div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-success">
            <div class="panel-heading">
              Manage Agency
            </div>
            <div class="panel-body">
              <router-link to="/Agency/create" class="btn btn-default btn-sm">New Agency</router-link>
              <div class="pull-right col-lg-6 form-horizontal">
                <label class="control-label col-lg-6">Search</label>
                <div class="col-lg-6">
                  <input type="text" class="form-control input-sm" v-model="search">
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Name of Agency</th>
                    <th>DRO</th>
                    <th>Contact Person</th>
                    <th>Region</th>
                    <th>Province</th>
                    <th>City</th>
                    <th>Barangay</th>
                    <th width="100"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center" colspan="8" v-if="noRecords">No Agency Found</td>
                  </tr>
                  <tr>
                    <td class="text-center" colspan="8" v-if="init">Search Agency</td>
                  </tr>
                  <tr>
                    <td class="text-center" colspan="8" v-if="isSearching"><loadingInline label="Searching Agency"></loadingInline></td>
                  </tr>
                  <tr v-if="!isSearching" v-for="a in result" :key="a.agency_cd" style="font-size:12px;">
                    <td>{{a.agency_name}}</td>
                    <td>{{a.owner}}</td>
                    <td>{{a.contact_person}}</td>
                    <td><span v-if="a.region">{{a.region.regname}}</span></td>
                    <td><span v-if="a.province">{{a.province.provname}}</span></td>
                    <td><span v-if="a.city">{{a.city.cityname}}</span></td>
                    <td><span v-if="a.barangay">{{a.barangay.bgyname}}</span></td>
                    <td>
                      <router-link :to="('/Agency/' + a.agency_cd)" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-search"></span></router-link>
                      <router-link :to="('/Agency/update/'+a.agency_cd)" class="btn btn-xs btn-warning"><span class="glyphicon glyphicon-pencil"></span></router-link>
                    </td>
                  </tr>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>

export default {
  mounted(){
    if(this.$store.state.AgencyManager.search){
      this.search = this.$store.state.AgencyManager.search;
    }
  },
  data(){
    return {
      search : null, loading : false, result : []
    }
  },
  watch : {
    search(){
      if(this.search){
        this.loading = true;
        this.doSearch(this);
      }
    }
  },
  computed : {
    init(){
      return !this.search && !this.loading && !this.result.length
    },
    noRecords(){
      return this.search && !this.loading && !this.result.length
    },
    isSearching(){
      return this.loading;
    }
  },
  methods : {
    doSearch : _.debounce((that) => {
      that.$http.post(that,"agencies",{
        facility_cd : that.$session.get('user').facility_cd,
        search : that.search
      })
      .then( ({data}) => {
        that.$store.state.AgencyManager.search = that.search;
        that.result = data;
        that.loading = false;
      })
      .catch(error => {
        that.$store.state.error = error;
      });
    },1000)
  }
}
</script>