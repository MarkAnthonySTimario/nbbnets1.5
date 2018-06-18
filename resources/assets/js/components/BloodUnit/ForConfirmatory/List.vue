<template>
  <div>
      <div v-show="!verify && !pageLoading" class="row">
          <div class="col-lg-8">
              <div class="panel panel-success">
                <div class="panel-heading">For Confirmatory TTI Reactive Blood Units</div>
                <table class="table table-condensed table-hover table-bordered" id="ForConfirmatory">
                    <thead>
                        <tr>
                            <th width="30" class="text-center" @click="selectAll = !selectAll"><input type="checkbox"  v-model="selectAll"></th>
                            <th>Donation ID</th>
                            <th>Component</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <td colspan="3" class="text-center"><loadingInline v-if="loading" label="Please wait, fetching Donation IDs"></loadingInline></td>
                        </tr>
                        <tr v-for="unit in currentVisibleUnits" :key="unit.donation_id+unit.component_cd">
                            <td class="text-center"><input type="checkbox" v-model="selected" :value="unit"></td>
                            <td>{{unit.donation_id}}</td>
                            <td>{{components[unit.component_cd]}}</td>
                        </tr>
                        <tr v-if="!loading && !units.length">
                            <td class="text-center" colspan="3">No For Confirmatory Blood Units</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">
                                <button class="btn btn-primary" @click.prevent="discard" :disabled="!selected.length">Discard</button>
                                <p class="pull-right">
                                    <button class="btn btn-default" @click="prevPage">Previous</button> 
                                    <button class="btn btn-default" @click="nextPage">Next</button>
                                </p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
              </div>
          </div>
      </div>
      <verifier v-if="verify && !pageLoading" @proceed="proceed" @cancel="canceled"></verifier>
      <loading v-if="pageLoading"></loading>
  </div>
</template>

<script>
export default {
  data(){
      return {
          components : this.$session.get('components'), units : [], loading : false, selected : [],
            pageSize:20,
            currentPage:1,
            verify : false,
            pageLoading : false
      };
  },
  mounted(){
      this.fetchUnits();
  },
  methods : {
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
      fetchUnits(){
          this.loading = true;
          this.$http.post(this,"forconfirmatory/list",{
              facility_cd : this.$session.get('user').facility_cd 
          })
          .then(({data}) => {
              this.units = _.sortBy(data,['donation_id'],'asc');
              this.loading = false;
          });
      },
      discard(){
          if(!this.selected.length){
              return;
          }
            this.verify = true;
      },
      proceed(verifier){
          this.pageLoading = true;
          let {user_id,facility_cd} = this.$session.get('user');
          this.$http.post(this,"forconfirmatory/discard",{
              units : this.selected, verifier, user_id, facility_cd
          }).then(({data}) => {
              this.pageLoading = false;
              this.$store.state.msg = {
                  content : 'Blood Units has been discarded successfully',
                  type : 'success'
              };
              this.fetchUnits();
          })
      },
      canceled(){
          this.verify = false;
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
    selectAll: {
        get: function () {
            return this.units ? this.selected.length == this.units.length : false;
        },
        set: function (value) {
            var selected = [];

            if (value) {
                this.units.forEach(function (unit) {
                    selected.push(unit);
                });
            }

            this.selected = selected;
        }
    }
  }
}
</script>
