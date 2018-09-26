<template>
    <div class="panel panel-warning">
        <div class="panel-heading">Expiring Units</div>
        <table class="table table-condensed table-striped" style="font-size:12px;">
            <thead>
            <tr>
                <th>Donation ID</th>
                <th>Component</th>
                <th>Expiration</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(e,i) in expiring" :key="i">
                <td>{{e.donation_id}}</td>
                <td>{{components[e.component_cd]}}</td>
                <td>{{e.expiration_dt}}</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-right">
                <button @click="refreshExpiry" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-refresh"></span></button>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
export default {
  data(){
    let components = this.$session.get('components')
    return {
      expiring : [], components
    }
  },
  mounted(){
    this.refreshExpiry()
  },
  methods : {
    refreshExpiry(){
      let {facility} = this.$session.get('user')
      this.$http.get(this,'home/expiry/'+facility.facility_cd)
      .then(({data}) => {
        this.expiring = _.filter(data,u=>u.daysold<=facility.no_days_expire_warning)
      })
    }
  }
}
</script>
