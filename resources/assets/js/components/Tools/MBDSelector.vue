<template>
  <div class="modal fade" id="MBDSelector">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h5 class="modal-title">
                      <span v-if="!walkin">Select MBD / Walk-in</span>
                      <span v-if="walkin && walkin_dates">Select Walk-in Date</span>
                      <button class="close" data-dismiss="modal"><span>&times;</span></button>
                  </h5>
              </div>
              <div class="modal-body" v-if="!walkin">
                  <div class="row">
                      <div class="col-lg-12 text-center">
                          <abc123 @hasClicked="selectLetter"></abc123>
                      </div>
                  </div>
                  <hr/>
                  <div class="row">
                      <div class="col-lg-8" style="height:500px;overflow:scroll;">
                          <table class="table table-condensed table-hover" style="font-size:14px;">
                              <thead>
                                  <tr>
                                      <th>
                                          Agency
                                          <div class="pull-right"><input type="text" class="form-control input-sm" placeholder="Search Agency Name" v-model="agency_name"></div>
                                    </th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr v-if="!letter">
                                      <td class="text-center">Click first letter of Agency</td>
                                  </tr>
                                  <tr v-if="loading">
                                      <td class="text-center"><loadingInline label="Please wait, loading agencies.."></loadingInline></td>
                                  </tr>
                                  <tr v-for="agency in agencies" :key="agency.agency_cd">
                                      <td>
                                          <a href="#dates" class="text-primary" @click.prevent="selectAgency(agency)">{{agency.agency_name}}</a>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                      <div class="col-lg-4">
                          <table class="table table-condensed table-hover" style="font-size:14px;">
                              <thead>
                                  <tr>
                                      <th id="dates" style="padding-top:1em;">Date of MBD</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr v-if="!current_agency">
                                      <td>No Selected Agency</td>
                                  </tr>
                                  <tr v-if="loading2">
                                      <td class="text-center"><loadingInline label="Please wait, loading.."></loadingInline></td>
                                  </tr>
                                  <tr v-for="mbd in mbds" :key="mbd.sched_id">
                                      <td>
                                          <a href="#" class="text-primary" @click.prevent="$emit('selected',{sched_id : mbd.sched_id, agency_cd : mbd.agency_cd, agency_name : mbd.agency_name, donation_dt : mbd.donation_dt})">{{mbd.donation_dt.substr(0,10)}}</a>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
              <div class="modal-body" v-if="walkin && walkin_dates">
                  <div class="row">
                      <div class="col-lg-6 col-lg-offset-3">
                          <div class="panel panel-default">
                              <div class="panel-body" >
                                  <div class="form-horizontal">
                                      <div class="form-group">
                                        <label for="" class="control-label col-lg-4">Start From</label>
                                        <div class="col-lg-8">
                                          <input type="date" class="form-control input-sm" v-model="s_date">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label for="" class="control-label col-lg-4">To</label>
                                        <div class="col-lg-8">
                                          <input type="date" class="form-control input-sm" v-model="e_date">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                          <div class="col-lg-12">
                                              <button class="btn btn-default btn-sm pull-right" :disabled="(!s_date || !e_date)" @click.prevent="$emit('selected',{sched_id : 'Walk-in',agency_cd : 'Walk-in',agency_name : 'Walk-in',from : s_date, to : e_date})">Proceed</button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer" v-if="!walkin">
                <button id="select_walkin" class="btn btn-warning" v-if="walkin_dates" @click.prevent="walkin = true">Select Walk-in</button>
                <button id="select_walkin" class="btn btn-warning" v-if="!walkin_dates" @click.prevent="$emit('selected',{sched_id : 'Walk-in', agency_cd : 'Walk-in',agency_name : 'Walk-in'})">Select Walk-in</button>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
  props : ['walkinDates'],
  data(){
      let walkinDates = this.walkinDates;
      return {
          letter : null, current_agency : null, 
          loading : false, loading2 : false, 
          agencies : [], mbds : [],
          walkin : false, walkin_dates : walkinDates, s_date : null, e_date : null,
          agency_name : null
      }
  },
  mounted(){
        $('#MBDSelector').on('shown.bs.modal', function () {
            $('.abc:first').focus();
        })
  },
  watch : {
      letter(){
          this.agencies = [];
          this.mbds = [];
          this.loading = true;          
          this.$http.post(this,"mbdselect/letter",{
              facility_cd : this.$session.get('user').facility_cd,
              letter : this.letter
          })
          .then(({data}) => {
              this.agencies = _.orderBy(data,[agency=>agency.agency_name.toLowerCase()],['asc']);
              this.loading = false;
          });
      },
      agency_name(){
          if(this.agency_name){
              this.agencies = _.filter(this.agencies, agency=>{
                  return _.startsWith(agency.agency_name.toUpperCase() , this.agency_name.toUpperCase())
              })
          }
      },
      current_agency(){
          this.mbds = [];
          this.loading2 = true;
          this.$http.get(this,"mbdselect/agencyMBDs/"+this.current_agency.agency_cd)
          .then(({data}) => {
              this.loading2 = false;
              this.mbds = _.orderBy(data,['donation_dt'],['desc']);
          });
      }
  },
  methods : {
      selectLetter(l){
          this.letter = l;
      },
      selectAgency(agency){
          this.current_agency = agency;
      }
  }
}
</script>

<style scoped>
.control-label {
    margin-top:-.5em;
    font-size: 14px;
}
body.modal-open {
    overflow: visible;
}
::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}
</style>
