<template>
  <div>
      <div class="row" v-if="!loading">
          <div class="col-lg-6">
              <div class="panel panel-success">
                  <div class="panel-heading">
                      MBD Details <router-link :to="('./../SearchDonor?donation_id='+donation_id)" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-arrow-left"></span> Back to List</router-link>
                  </div>
                    <table class="table table-condensed table-bordered" v-if="mbd">
                        <tbody>
                            <tr>
                                <th class="col-lg-4">Agency</th>
                                <td>{{mbd.agency_name}}</td>
                            </tr>
                            <tr>
                                <th class="col-lg-4">Date</th>
                                <td>{{mbd.donation_dt.substr(0,10)}}</td>
                            </tr>
                            <tr v-if="donation_id">
                                <th>Donation ID</th>
                                <td>{{donation_id}}</td>
                            </tr>
                        </tbody>
                    </table>
              </div>
          </div>
      </div>
      <div class="row" v-if="!loading">
          <div class="col-lg-6">
              <div class="panel panel-warning">
                  
                  <div class="panel-heading">
                      <span class="glyphicon glyphicon-user"></span> Donor Details
                      <span class="pull-right">
                        <router-link :to="('./'+seqno+'/update?donation_id='+donation_id)" class="btn btn-default btn-xs">
                            <span class="glyphicon glyphicon-pencil"></span> Update Information
                        </router-link>
                        <button v-if="mbd && !alreadyInMBD && !donation_id" 
                            class="btn btn-default btn-xs" 
                            :disabled="(donor.donation_stat == 'N')"
                            @click.prevent="confirmAdd">
                            <span class="glyphicon glyphicon-plus"></span> New MBD Donation
                        </button>
                        <button v-if="mbd && !alreadyInMBD && donation_id" 
                            class="btn btn-default btn-xs" 
                            :disabled="(donor.donation_stat == 'N')"
                            @click.prevent="confirmAdd">
                            <span class="glyphicon glyphicon-ok"></span> Assign to {{donation_id}}
                        </button>
                        <span v-if="alreadyInMBD" class="text-default">
                            &nbsp;&nbsp;Donor already in MBD
                        </span>

                      </span>
                  </div>
                  <table class="table table-condensed table-bordered">
                      <tbody>
                          <tr v-if="donor.donor_photo" style="height:150px;">
                              <th></th>
                              <td class="text-center">
                                  <img class="img img-thumb" :src="('data:image/jpeg;base64,'+donor.donor_photo)" alt="Donor Photo" style="width:400px;height:250px;" v-if="donor.donor_photo !== true" />
                              </td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Donor ID</th>
                              <td class="col-lg-8">{{donor.donor_id}}</td>
                          </tr>
                          <tr>
                              <th>Donation Status</th>
                              <td style="font-size:30px !important;">
                                  <b class="text-success" v-if="(donor.donation_stat != 'N')">May Donate</b>
                                  <b class="text-danger" v-if="(donor.donation_stat == 'N')">Cannot Donate</b>
                              </td>
                          </tr>
                          <tr>
                              <th>Donor Name</th>
                              <td>
                                  {{donor.fname}} {{donor.mname}} {{donor.lname}}
                              </td>
                          </tr>
                          <tr>
                              <th>Name Suffix</th>
                              <td>{{donor.name_suffix}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Gender</th>
                              <td class="col-lg-8">{{donor.gender | gender}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Birth Date</th>
                              <td class="col-lg-8">{{donor.bdate}} ({{donor.bdate | age}} years old)</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Civil Status</th>
                              <td class="col-lg-8">{{donor.civil_stat | civilStatus}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Nationality</th>
                              <td class="col-lg-8">{{donor.nation ? donor.nation.nationality : null}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Telephone No.</th>
                              <td class="col-lg-8">{{donor.tel_no}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Mobile No.</th>
                              <td class="col-lg-8">{{donor.mobile_no}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Email</th>
                              <td class="col-lg-8">{{donor.email}}</td>
                          </tr>
                          <tr>
                              <td class="bg-success" colspan="2">Home Address</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Region</th>
                              <td class="col-lg-8">{{donor.region | novalue('regname') | nye}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Province</th>
                              <td class="col-lg-8">{{donor.province | novalue('provname') | nye}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">City/Municipality</th>
                              <td class="col-lg-8">{{donor.city | novalue('cityname') | nye}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Barangay</th>
                              <td class="col-lg-8">{{ donor.barangay | novalue('bgyname') | nye}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">House No. / Street / Block</th>
                              <td class="col-lg-8">{{donor.home_no_st_blk}}</td>
                          </tr>
                          <tr>
                              <td class="bg-success" colspan="2">Office Address</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Region</th>
                              <td class="col-lg-8">{{donor.officeregion | novalue('regname') | nye}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Province</th>
                              <td class="col-lg-8">{{donor.officeprovince | novalue('provname') | nye}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">City/Municipality</th>
                              <td class="col-lg-8">{{donor.officecity | novalue('cityname') | nye}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">Barangay</th>
                              <td class="col-lg-8">{{donor.officebarangay | novalue('bgyname') | nye}}</td>
                          </tr>
                          <tr>
                              <th class="col-lg-4">House No. / Street / Block</th>
                              <td class="col-lg-8">{{donor.office_no_st_blk}}</td>
                          </tr>
                          <!-- tr>th.col-lg-4+td.col-lg-8 -->
                      </tbody>
                  </table>
              </div>
          </div>

          <div class="col-lg-6">
              <div class="panel panel-primary">
                  <div class="panel-heading">Donation History</div>
                <table class="table table-condensed table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Facility</th>
                            <th>Date</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="d in donor.donations" :key="d.seqno"  v-if="donor.donations.length">
                            <td>{{d.facility.facility_name}}</td>
                            <td>{{d.created_dt.substr(0,10)}}</td>
                            <td>{{d.remarks}}</td>
                        </tr>
                        <tr v-if="!donor.donations.length">
                            <td class="text-center" colspan="3">No Donations Yet</td>
                        </tr>
                    </tbody>
                </table>
              </div>
          </div>
      </div>
      <loading v-if="loading"></loading>
      <confirm title="Review Details before procedding." @proceed="addDonorToMBD"></confirm>
  </div>
</template>

<script>

export default {
  props : ['sched_id','seqno'],
  data(){
      return {
          donation_id : null, mbd : null, donor : null,loading : true
      }
  },
  mounted(){
      let {currentRoute : {query : {donation_id}}} = this.$router;
      if(donation_id != null && donation_id != undefined && donation_id != "undefined" && donation_id != "null"){
          this.donation_id = donation_id;
      }
      this.$http.get(this,"mbd/info/"+this.sched_id)
      .then(({data}) => {
          this.mbd = data;
      })
      .catch(error => {
          this.$store.state.error = error;
      });

      this.$http.get(this,"donor/"+this.seqno)
      .then(({data}) => {
          this.donor = data;
          this.donor.donations = _.orderBy(this.donor.donations,['created_dt'],['desc']);
          this.loading = false;
          this.fetchPhoto();
      })
      .catch(error => {
          this.$store.state.error = error;
      });
  },
  methods : {
      fetchPhoto(){
          if(this.donor.donor_photo){
              this.$http.get(this,'donor/'+this.donor.seqno+'/photo')
              .then(({data}) => {
                  this.donor.donor_photo = data;
              })
              .catch(error => {
                  this.$store.state.error = error;
              });
          }
      },
      confirmAdd(){
          $("#confirmDialog").modal("show");
      },
      addDonorToMBD(c){
          this.loading = true;
          let {sched_id,donation_id,donor : {seqno}} = this;
          let {facility_cd,user_id} = this.$session.get('user');
          let post_url = donation_id ? 'mbd/assignDonor' : 'mbd/addDonor';
          this.$http.post(this,post_url,{
              sched_id,seqno,facility_cd,user_id,donation_id
          })
          .then(({data}) => {
              this.$store.state.msg = {
                  content : 'Donor has been added to this MBD',
                  type : 'success'
              };
              this.$store.state.MBD.donor_search = {};
              this.$router.replace("/MBD/"+this.sched_id);
              c();
          })
          .catch(error => {
              this.$store.state.error = error;
          });
      }
  },
  computed : {
      alreadyInMBD(){
          if(!this.mbd){
              return false;
          }
          return _.filter(this.mbd.donations,{'donor_sn' : this.donor.seqno}).length > 0;
      }
  }
}
</script>
