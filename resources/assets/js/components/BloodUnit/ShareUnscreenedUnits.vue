<template>
    <div>
        <UnitSelector @selectUnit="selectUnit" />
        <LoadingInline v-if="loading" label="Please Wait, loading.." />
        <div class="row" v-if="!loading && !showVerifier">
            <div class="col-lg-8">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Share Unscreened Units
                    </div>
                    <div class="panel-body">
                        
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td colspan="2" class="text-right">
                                        <button class="btn btn-default btn-sm" @click="showUnitSelector">Select Units</button>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Donation ID</th>
                                    <th>Component</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(u,i) in units" :key="i">
                                    <td>{{u.donation_id}}</td>
                                    <td>{{components[u.component_cd]}}</td>
                                </tr>
                                <tr v-if="units.length == 0">
                                    <td colspan="2" class="text-center">Start by Selecting Unscreened Units</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    


                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Share to
                    </div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="row">

                                <div class="form-group">
                                    <label for="" class="control-label col-lg-3">Share to</label>
                                    <div class="col-lg-8">
                                        <select v-model="shared_facility_cd" class="form-control input-sm">
                                            <option :value="null">--Select Facility--</option>
                                            <option v-for="f in facilities" :key="f.facility_cd" :value="f.facility_cd">{{f.facility_name}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="btn btn-default btn-sm" :disabled="units.length == 0 || !shared_facility_cd" @click="showVerifier = true">Proceed</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Verifier v-if="showVerifier" @proceed="save" />
    </div>
</template>

<script>
    import UnitSelector from './ShareUnscreenedUnits/UnitSelector.vue'
    export default {
        components : {UnitSelector},
        data(){
            let user = this.$session.get('user')
            let components = this.$session.get('components')
            return {
                shared_facility_cd : null, facilities : [], loading : false, user_id: user.user_id, facility_cd : user.facility_cd, components, units : [],
                showVerifier : false
            }
        },
        mounted(){
            this.getFacilities()
        },
        methods : {
            getFacilities(){
                this.loading = true
                this.$http.get(this,'shareunscreenedunits/facilities')
                .then(({data}) => {
                    this.loading = false
                    this.facilities = _.sortBy(_.filter(data,f=>f.facility_cd != this.facility_cd),f=>f.facility_name.toUpperCase())
                })
            },
            showUnitSelector(){
                $("#UnscreenedUnitSelector").modal("show")
            },
            selectUnit(u){
                this.units.push(u)
            },
            save(verifier){
                let {units,shared_facility_cd,user_id,facility_cd} = this
                this.showVerifier = false
                this.loading = true
                this.$http.post(this,"shareunscreenedunits/share",{
                    user_id,facility_cd,units,verifier,shared_facility_cd
                })
                .then(({data}) => {
                    this.loading = false
                    this.units = []
                    this.shared_facility_cd = null
                    this.$store.state.msg = {
                        content : "Blood Units has been shared to facility."
                    }
                })
            }
        }
    }
</script>