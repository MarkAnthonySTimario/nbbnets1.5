<template>
    <div>
        <div class="row" v-if="!loading">
            <div class="col-lg-3">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Select Blood Service Facility
                    </div>
                    <div class="panel-body">
                        <select class="form-control input-sm" v-model="facility">
                            <option :value="null"></option>
                            <option v-for="f in facilities" :value="f" :key="f.facility_cd">{{f.facility_name}}</option>
                        </select>
                    </div>
                    <div class="panel-footer" v-if="!facility">
                        <center>Start by Selecting the Name of the Facility</center>
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
                            <tr v-if="!intentLoading && intents.length == 0">
                                <td class="text-center">No Records Found</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-center">
                                    <button class="btn btn-default btn-xs" @click.prevent="newIntent">Create Request of this facility</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-lg-9" v-if="intent && facility">
                <Intent :intent="intent" @proceed="proceed" />
            </div>
            <div class="col-lg-9" v-if="!loading && !facility">
                <Requests :update="updateRequests" />
            </div>
        </div>
        <Loading v-if="loading" />
    </div>
</template>

<script>
import Intent from './ServeNetworking/Intent'
import Requests from './ServeNetworking/Requests'

export default {
    components : {Intent,Requests},
    data(){
        return {
            facilities : [], 
            loading : false, 
            facility : null, 
            intents : [], 
            intentLoading : false, 
            intent : null, 
            updateRequests : 0
        }
    },
    mounted(){
        // this.facility = {"facility_name":"tmc","facility_cd":"13109"}
        // this.intent = {"id":8,"created_at":"2018-08-29 22:18:41","by":"13109","details":"[{\"blood_type\":\"A pos\",\"component_cd\":10,\"quantity\":\"3\"},{\"blood_type\":\"A pos\",\"component_cd\":\"20\",\"quantity\":\"2\"}]"}
        this.loadFacilities()
    },
    methods : {
        loadFacilities(){
            this.loading = true
            this.$http.get(this,'networking/facilities')
            .then(({data}) => {
                this.facilities = _.orderBy(data,f=>{
                    return f.facility_name.toUpperCase()
                },['asc'])
                _.remove(this.facilities,f=>{
                    return f.facility_cd == this.$session.get('user').facility.facility_cd
                })
                this.loading = false
            })
        },
        loadIntent(intent){
            this.intent = intent
        },
        getIntents(){
            if(!this.facility){
                return
            }
            this.intentLoading = true
            this.$http.post(this,'networking/intents',{
                from : this.facility.facility_cd,
                to : this.$session.get('user').facility.facility_cd
            })
            .then(({data})=> {
                this.intents = _.orderBy(data,['created_at'],['desc'])
                this.intentLoading = false
            })
        },
        newIntent(){
            let user = this.$session.get('user')
            this.loading = true
            
            this.$http.post(this,'networking/sendintent',{
                from : this.facility.facility_cd,
                to : user.facility.facility_cd,
                by : this.facility.facility_cd,
                details : [
                    {blood_type : 'A pos', component_cd : '10', quantity : 1}
                ]
            })
            .then(({data})=>{
                this.getIntents()
                this.loading = false
            })
        },
        proceed(details){
            let user = this.$session.get('user')
            
            this.loading = true
            this.$http.post(this,'networking/serveintent',{
                reserved_by : user.user_id,
                facility_cd : user.facility.facility_cd,
                id : this.intent.id,
                details : JSON.stringify(details)
            })
            .then(({data})=>{
                this.getIntents()
                this.intent = null
                this.facility = null
                this.updateRequests++
                this.loading = false
            })
        }
    },
    watch : {
        facility(){
            this.getIntents()
        }
    }
}
</script>
