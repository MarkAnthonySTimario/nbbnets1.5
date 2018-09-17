<template>
  <div class="modal fade" id="UnscreenedUnitSelector">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h5 class="modal-title">
                      Select Unscreened Units to Share
                      <button class="close" data-dismiss="modal"><span>&times;</span></button>
                  </h5>
              </div>
              <table class="table table-bordered table-condensed" style="font-size:12px;">
                  <thead>
                      <tr>
                          <td colspan="3">
                              <div class="col-lg-4 col-lg-offset-8">
                                  <input v-model="search" type="text" class="form-control input-sm" placeholder="Search Donation ID">
                              </div>
                          </td>
                      </tr>
                      <tr>
                          <th>Donation ID</th>
                          <th>Component</th>
                          <th width="50"></th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr v-if="loading">
                          <td colspan="3" class="text-center">
                              <LoadingInline label="Please wait, loading.." />
                          </td>
                      </tr>
                      <tr v-if="!loading && units.length == 0">
                          <td colspan="3" class="text-center">
                              No Unscreened Units
                          </td>
                      </tr>
                      <tr v-if="!loading && units.length" v-show="!u.selected" v-for="(u,i) in currentVisibleUnits" :key="i">
                          <td>{{u.donation_id}}</td>
                          <td>{{components[u.component_cd]}}</td>
                          <td><button class="btn btn-xs btn-success" @click="selectUnit(u)"><span class="glyphicon glyphicon-plus"></span></button></td>
                      </tr>
                  </tbody>
                  <tfoot>
                      <tr>
                          <td colspan="3" class="text-right">
                              <button class="btn btn-default btn-xs" @click="prevPage">Previous</button>
                              <button class="btn btn-default btn-xs" @click="nextPage">Next</button>
                          </td>
                      </tr>
                  </tfoot>
              </table>
          </div>
      </div>
  </div>
</template>

<script>
    export default {
        data(){
            let user = this.$session.get('user')
            let components = this.$session.get('components')
            return {
                loading : false, units : [], origUnits : [], user, components, search : null,
                pageSize:10,
                currentPage:1,
            }
        },
        mounted(){
            this.getUnits()
        },
        methods : {
            getUnits(){
                this.loading = true
                this.$http.get(this,'shareunscreenedunits/unscreenedlist/'+this.user.facility_cd)
                .then(({data}) => {
                    this.loading = false
                    data.map(u=>{
                        u.selected = false
                    })
                    this.units = data
                    this.origUnits = data
                })
            },
            nextPage() {
                if((this.currentPage*this.pageSize) < this.units.length) {
                    this.currentPage++;
                }
            },
            prevPage() {
                if(this.currentPage > 1) {
                    this.currentPage--;
                }
            },
            selectUnit(u){
                this.$emit('selectUnit',u)
                u.selected = true
            }
        },
        computed : {
            currentVisibleUnits(){
                return _.filter(this.units,(unit,index) => {
                    let start = (this.currentPage-1)*this.pageSize;
                    let end = this.currentPage*this.pageSize;
                    return index >= start && index < end;
                })
            },
        },
        watch : {
            search(){
                if(!this.search){
                    this.units = this.origUnits
                }
                this.units = _.filter(this.origUnits,unit => {
                    return _.startsWith(unit.donation_id.toUpperCase(),this.search.toUpperCase())
                })
            }
        }
    }
</script>