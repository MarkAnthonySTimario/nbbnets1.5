<template>
  <div>
    <table class="table table-condensed table-bordered table-striped">
        <thead>
            <tr>
                <th colspan="3"></th>
                <th colspan="3" class="text-center">Donor Details</th>
                <th colspan="3" class="text-center">Donation Details</th>
            </tr>
            <tr>
                <th style="width:30px;">
                    <input type="checkbox" v-model="selectAll">
                </th>
                <!-- <th style="width:30px;"></th> -->
                <th style="width:30px;"></th>
                <th><a href="javascript:void(0)" @click="sortBy('lname')">Donor Name <span class="glyphicon glyphicon-sort"></span></a></th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th><a href="javascript:void(0)" @click="sortBy('donation_id')">Donation ID <span class="glyphicon glyphicon-sort"></span></a></th>
                <th>MH/PE Result</th>
                <th>Collection Status</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="!donations.length && !loading">
                <td colspan="8" class="text-center">No Donors Yet</td>
            </tr>
            <tr v-if="loading">
                <td colspan="8" class="text-center"><loadingInline label="Please wait, loading donors.."></loadingInline></td>
            </tr>
            <tr v-if="donations && !loading" v-for="d in donations" :key="d.seqno" style="font-size:14px;">
                <td>
                    <input type="checkbox" :value="d.seqno" v-model="selected" v-if="!d.donation_id">
                </td>
                <!-- <td><button class="btn btn-danger btn-xs" v-if="!d.processing" @click.prevent="confirmRemove(d.seqno)"><span class="glyphicon glyphicon-remove"></span></button></td> -->
                <td>
                    <router-link :to="('/MBD/'+schedid+'/SearchDonor?donation_id='+d.donation_id)" class="btn btn-info btn-xs" v-if="!d.donor"><span class="glyphicon glyphicon-user"></span></router-link>
                    <button @click="assignDonorToDonationID(d)" :to="('/MBD/'+schedid+'/SearchDonor?donation_id='+d.donation_id)" class="btn btn-info btn-xs" v-if="!d.mh_pe_stat" title="Assign Donor to a Donation ID"><span class="glyphicon glyphicon-paperclip"></span></button>
                </td>
                <td v-if="!d.donor" colspan="3"></td>
                <td v-if="d.donor">{{d.donor.lname}}, {{d.donor.fname}} {{d.donor.mname}}</td>
                <td v-if="d.donor">{{d.donor.gender | gender}}</td>
                <td v-if="d.donor">{{d.donor.bdate}}</td>
                <td>{{d.donation_id}}</td>
                <td>{{d.donation_stat | mhpe}}</td>
                <td>{{d.collection_stat | collection}}</td>
            </tr>
        </tbody>
    </table>
    <confirm title="Remove donor from MBD?" @proceed="removeDonor"></confirm>

  </div>
</template>

<script>

export default {
  props : ['schedid','refresh'],
  data(){
      return {
          selected : [], donations : [], currentSeqno : null, loading : false
      }
  },
  watch : {
      selected(){
          let selectedDonations = [];
          this.selected.forEach((sel) => {
              let donation = _.find(this.donations,{seqno : sel});
              if(donation){
                  selectedDonations.push(donation);
              }
          })
          this.$emit("onselect",selectedDonations);
      },
      refresh(){
          this.selected = []; this.donations = []; this.currentSeqno = null; this.loading = false;
          this.loadDonations();
      }
  },
  mounted(){
      this.loadDonations();
  },
  computed: {
    selectAll: {
        get: function () {
            let donations = _.filter(this.donations,d => {
                if(!d.donation_id){
                    return d;
                }
            });
            return this.donations ? this.selected.length == donations.length : false;
        },
        set: function (value) {
            var selected = [];

            if (value) {
                this.donations.forEach(function (donation) {
                    if(!donation.donation_id){
                        selected.push(donation.seqno);
                    }
                });
            }

            this.selected = selected;
        }
    }
  },
  methods : {
      loadDonations(){
          this.loading = true;
            this.$http.get(this,"mbd/donations/"+this.schedid)
            .then(({data}) => {
                this.donations = _.orderBy(data,['seqno']);
                this.loading = false;
            })
            .catch(error => {
                this.$store.state.error = error;
            });
      },
      confirmRemove(seqno){
          this.currentSeqno = seqno;
          $("#confirmDialog").modal("show");
      },
      removeDonor(c){
          this.$http.post(this,"mbd/donationRemove",{
              seqno : this.currentSeqno
          })
          .then(({data}) => {
              this.currentSeqno = null;
              this.donations = _.filter(this.donations,(d) => {
                  return d.seqno != data;
              });
              c();
          });
      },
      assignDonor(donation_id){
          this.$router.replace("/MBD/"+this.schedid+"/AddDonor/"+donation_id);
      },
      assignDonorToDonationID(donation){
          let donation_id = prompt("Scan/Enter Donation ID")
          
          if(donation_id){
              this.$http.post(this,'mbd/assigndonortodonationid',{
                  seqno : donation.seqno, donation_id
              })
              .then(({data}) => {
                  this.loadDonations()
              })
          }
      },
      sortBy(field){
          if(field == 'lname'){
            this.donations = _.orderBy(this.donations,d=>d.donor ? d.donor.lname.toUpperCase() : d.seqno)
          }else{
            this.donations = _.orderBy(this.donations,['donation_id'])
          }
      }
  }
}
</script>

