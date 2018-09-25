<template>
  <div class="modal fade" id="agencySelector">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h5 class="modal-title">Select Agency
                      <button class="close" data-dismiss="modal"><span>&times;</span></button>
                  </h5>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-lg-12 text-center">
                          <abc123 @hasClicked="selectLetter"></abc123>
                      </div>
                  </div>
                  <div class="row" v-show="letter">
                      <div class="col-lg-4 col-lg-offset-8">
                          <br/>
                          <input type="text" class="form-control input-sm" placeholder="Search Agency Name" v-model="agency_name">
                      </div>
                  </div>
              </div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed table-hover" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th>Agency</th>
                            <th>Contact Person</th>
                            <th>DRO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <td class="text-center" colspan="5">
                                <loading-inline label="Please wait.."></loading-inline>
                            </td>
                        </tr>
                        <tr v-if="loaded && !agencies">
                            <td class="text-center" colspan="5">No Agencies Yet</td>
                        </tr>
                        <tr v-if="loaded && !letter">
                            <td class="text-center" colspan="5">Select a Letter</td>
                        </tr>
                        <tr v-if="loaded && letter" v-for="a in filteredAgencies" :key="a.agency_cd">
                            <td>{{a.agency_name}}</td>
                            <td>{{a.contact_person ? a.contact_person.substr(0,20) : null}}..</td>
                            <td>{{a.owner ? a.owner.substr(0,20) : null}}..</td>
                            <td>
                                <button class="btn btn-success btn-xs" data-dismiss="modal" @click.prevent="selectAgency(a)"><span class="glyphicon glyphicon-search"></span></button>
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
      return { loading: true, loaded: false, agencies : [], letter: null, agency_name : null }
  },
  mounted(){
      this.$http.post(this,"agencies",{
          facility_cd : this.$store.state.user.facility_cd
      })
      .then(({data}) => {
          this.agencies = data;
          this.loaded = true;
          this.loading = false;
      })
      .catch(error => {
          this.$store.state.error = error;
      });
  },
  computed: {
      filteredAgencies(){
          if(!this.letter){
              return [];
          }
          if(this.letter == '#'){
            return _.filter(this.agencies,(r => {
                return !isNaN(r.agency_name.substr(0,1))
            }));    
          }
          let out =  _.orderBy(_.filter(this.agencies,(r => {
              return r.agency_name.substr(0,1).toUpperCase() == this.letter.toUpperCase()
          })),a=>{
              return a.agency_name.toUpperCase()
          });
          const that = this
          if(this.agency_name){
              out = _.filter(out, agency => {
                return _.startsWith(agency.agency_name.toUpperCase(), this.agency_name.toUpperCase());
              });
          }

          return out;
      }
  },
  methods : {
      selectLetter(l){
          this.agency_name = null;
          this.letter = l;
      },
      selectAgency(agency){
          this.$emit('onselect',agency);
      }
  }
}
</script>
