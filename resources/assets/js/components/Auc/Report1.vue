<template>
  <div>
      <table class="table table-bordered table-striped">
          <thead>
              <tr>
                  <!-- <th></th> -->
                  <th>Agency Name</th>
                  <th>Address</th>
                  <th>Distance (KM)</th>
                  <th>Donors</th>
                  <th>1st time Donors</th>
              </tr>
          </thead>
          <tbody>
              <tr v-for="a in notempty(agencies)" :key="a.sched_id">
                  <!-- <td>{{i+1}}</td> -->
                  <td>{{a.agency_name}}</td>
                  <td>{{a.adg_no_st_blk}} {{a.barangay ? a.barangay.bgyname : ''}} {{a.city.cityname}} {{a.province.provname}} {{a.region.regname}}</td>
                  <td>{{a.distance}}</td>
                  <td>{{a.donors.length}}</td>
                  <td>{{fdonors(a).length}}</td>
              </tr>
          </tbody>
      </table>
  </div>
</template>

<script>
export default {
  data(){
      return {
          agencies : []
      }
  },
  mounted(){
      let {facility_cd} = this.$session.get('user')
      this.$http.post(this,'auc/report1',{
          facility_cd
      }).then(({data}) => {
          this.agencies = data
      })
  },
  methods : {
      fdonors(agency){
          return _.filter(agency.donors,{donations:1});
      },
      notempty(agencies){
          return _.filter(agencies,agency=>agency.donors.length);
        // return agencies;
      }
  }
}
</script>
