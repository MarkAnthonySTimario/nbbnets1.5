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
                    <table class="table table-condensed" v-if="facility">
                        <tbody>
                            <tr v-for="i in intents" :key="i.id" v-if="!intentLoading">
                                <td class="text-center" style="font-size:12px;">
                                    <a href="#" class="text-primary" @click.prevent="loadIntent(i)">{{i.created_at}}</a>
                                </td>
                            </tr>
                            <tr v-if="intentLoading">
                                <td class="text-center"><LoadingInline label="Please wait, loading.." /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-8" v-if="intent">
                <Intent :intent="intent" />
            </div>
        </div>
        <Loading v-if="loading"  />
    </div>
</template>

<script>
import Intent from './ServeNetworking/Intent'
export default {
    components : {Intent},
    data(){
        return {
            facilities : [], loading : false, facility : null, intents : [], intentLoading : false, intent : null
        }
    },
    mounted(){
        this.facility = {"facility_name":"Philippine Blood Center","facility_cd":"13006"}
        this.intents = [{"id":1,"created_at":"2018-08-28 21:03:13","by":"13006","details":"[{\"blood_type\":\"A pos\",\"component_cd\":10,\"quantity\":0}]"},{"id":2,"created_at":"2018-08-28 21:03:52","by":"13006","details":"[{\"blood_type\":\"A pos\",\"component_cd\":10,\"quantity\":\"1\"},{\"blood_type\":\"B pos\",\"component_cd\":\"20\",\"quantity\":\"1\"}]"}]
        this.loading = true
        this.$http.get(this,'networking/facilities')
        .then(({data}) => {
            this.facilities = _.orderBy(data,f=>{
                return f.facility_name.toUpperCase()
            },['asc'])
            this.loading = false
        })
    },
    methods : {
        loadIntent(intent){
            this.intent = intent
        }
    },
    watch : {
        facility(){
            if(!this.facility){
                return
            }
            this.intentLoading = true
            this.$http.post(this,'networking/intents',{
                from : this.facility.facility_cd,
                to : this.$session.get('user').facility.facility_cd
            })
            .then(({data})=> {
                this.intents = data
                this.intentLoading = false
            })
        }
    }
}
</script>
