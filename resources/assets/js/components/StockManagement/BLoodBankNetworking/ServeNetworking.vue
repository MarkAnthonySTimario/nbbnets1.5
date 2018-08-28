<template>
    <div>
        <div class="row" v-if="!loading">
            <div class="col-lg-4">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Select Blood Service Facility
                    </div>
                    <div class="panel-body">
                        <select class="form-control input-sm" v-model="facility">
                            <option v-for="f in facilities" :value="f" :key="f.facility_cd">{{f.facility_name}}</option>
                        </select>
                    </div>
                    <table class="table table-condensed">
                        <tbody>
                            <tr v-for="i in intents" :key="i.id">
                                <td>{{i.created_at}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Loading v-if="loading" />
    </div>
</template>

<script>
export default {
    data(){
        return {
            facilities : [], loading : false, facility : null, intents : []
        }
    },
    mounted(){
        this.loading = true
        this.$http.get(this,'networking/facilities')
        .then(({data}) => {
            this.facilities = _.orderBy(data,f=>{
                return f.facility_name.toUpperCase()
            },['asc'])
            this.loading = false
        })
    },
    watch : {
        facility(){
            if(!this.facility){
                return
            }
            this.$http.get(this,'networking/intents/'+this.facility_cd)
            .then(({data})=> {
                this.intents = data
            })
        }
    }
}
</script>
