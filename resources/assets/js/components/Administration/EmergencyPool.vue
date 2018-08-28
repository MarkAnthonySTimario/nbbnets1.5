<template>
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Emergency Pool Settings</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <form role="form">
                                <div class="form-group">
                                    <label for="blood_type">Blood Type</label>
                                    <select class="form-control input-sm" required="required" v-model="blood_type">
                                        <option v-for="(bt,i) in blood_types" :value="bt" :key="i">{{bt}}</option>
                                    </select>                                    
                                </div>

                                <div class="form-group">
                                    <label for="component">Component</label>
                                    <select class="form-control input-sm" required="required" v-model="component_cd">
                                        <option v-for="(cn,cd) in components" :key="cd" :value="cd">{{cn}} </option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="pool">Emergency Pool Settings</label>
                                    <input type="number" class="form-control input-sm" required="required"  v-model="pool">
                                </div>
	                                
                                <button type="button" class="btn btn-primary btn-sm" @click.prevent="create">Submit</button>
                            </form>                            
                        </div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-condensed table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Blood Type</th>
                                                <th class="text-center">Component</th>
                                                <th class="text-center">Pool</th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="value in records" :key='value.id'>
                                                <td class="text-center">{{value.blood_type}}</td>
                                                <td class="text-center">{{components[value.component_cd]}}</td>
                                                <td class="text-center">{{value.pool}}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success btn-xs" @click="blood_type = value.blood_type; component_cd = value.component_cd; pool = value.pool;">
                                                        <span class="glyphicon glyphicon-pencil"></span>
                                                    </button>
                                                </td>
                                            </tr>                                          
                                        </tbody>
                                    </table>                               
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
  data(){
      return {
        component_cd : null,
        blood_type : null,
        pool : null,
        records : [],
        components : this.$session.get('components'),
        blood_types : this.$session.get('blood_types'),
      }
  },
  created(){
      this.refresh()
  },

methods : {
      create(){
          this.loading = true;
          let data = this.$data;
          data.facility_cd = this.$store.state.user.facility_cd;

            let that = this
          this.$http.post(this,'emergencypool/create',data) 
          .then(({data}) => {
              that.blood_type = null
              that.component_cd = null
              that.pool = null
              this.refresh()
              that.$router.replace('/EmergencyPool/');
              that.loading = false;
          })
      },  
      refresh(){
          this.$http.get(this,'emergencypool/get/'+this.$store.state.user.facility_cd)
            .then(({data}) => {
                this.records = data
            })
      }    
  }
}
</script>


<style>
body {
    font-size: 12px;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
}

thead > tr {
    background-color: #ccc;
    text-align: center;
    text-transform: uppercase;
}
</style>