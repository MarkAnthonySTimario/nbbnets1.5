<template>
  <div>
      <div class="panel panel-warning">
          <div class="panel-heading">Search Walk-in Donation</div>
          <div class="panel-body form-horizontal">
              <router-link to="/Verifier" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> New Walk-in</router-link>
              <div class="pull-right">
                <input type="date" v-model="search" class="form-control input-sm" placeholder="Search Donation ID">
              </div>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Donor</th>
                        <th class="text-center">Type of Donor</th>
                        <th class="text-center">MH/PE</th>
                        <th class="text-center">Method</th>
                        <th class="text-center">Collection</th>
                        <th class="text-center">Donation ID</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center" colspan="9" v-if="!search && !loading">Select Date of Donation</td>
                    </tr>                  
                    <tr>
                        <td class="text-center" colspan="9" v-if="search && !donations.length && !loading">No Walk-in Donation Found</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="9" v-if="loading"><loadingInline label="Please wait, loading.."></loadingInline></td>
                    </tr>
                    <tr v-for="d in donations" :key="d.seqno" v-if="!loading && search && donations" class="text-center">
                        <td v-if="d.donor">{{d.donor.fname | uppercase}} {{d.donor.mname | uppercase}} {{d.donor.lname | uppercase}}</td>
                        <td v-if="!d.donor"></td>
                        <td>{{d.donation_type | donationType}}</td>
                        <td>{{d.mh_pe_stat | mhpe | uppercase}}</td>
                        <td>{{d.collection_method | collection_method}}</td>
                        <td>{{d.collection_stat | collection | uppercase}}</td>
                        <td>{{d.donation_id}}</td>
                        <td>
                            <router-link :to="('SelectWalkinDonor?donation_id='+d.donation_id)" v-if="!d.donor" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-user"></span></router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
          </div>
      </div>
  </div>
</template>

<script>

export default {
  data(){
      return {
          search : this.$store.state.WALKIN.search, donations : [], loading : false
      }
  },
  mounted(){
      this.search = !this.search ? this.dateToday() : this.search;
      this.doSearch();
  },
  destroyed(){
      this.$store.state.WALKIN.search = this.search;
  },
  watch : {
     search(){
         this.doSearch();
     }
  },
  methods : {
      doSearch(){
          this.donations = [];
           if(!this.search){
               return false;
           }
           this.loading = true;
            this.$http.post(this,"walkin",{
                facility_cd : this.$session.get('user').facility_cd,
                search : this.search
            })
            .then(({data}) => {
                this.donations = data;
                this.loading = false;
            })
            .catch(error => {
                this.$store.state.error = error;
            })
      },
      dateToday(){
          let today = new Date();
            let dd = today.getDate();
            let mm = today.getMonth()+1; //January is 0!

            let yyyy = today.getFullYear();
            if(dd<10){
                dd='0'+dd;
            } 
            if(mm<10){
                mm='0'+mm;
            } 
            return yyyy+'-'+mm+'-'+dd;
      }
  }
}
</script>

<style scoped>
table td{
    font-size:14px !important;
}
</style>
