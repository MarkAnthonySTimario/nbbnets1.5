<template>
    <div>
        <div class="row" v-if="!loading && !focus">
            <div class="col-lg-5">
                <Search @apply="applyDetails" />
            </div>
            <div class="col-lg-7">
                <Result :details="details" :search="search" :facilities="facilities" @showFacility="facility=>{this.focus = facility}" />
            </div>
        </div>
        <Facility :details="details" :facilitycd="focus.facility_cd" v-if="focus" @focusOut="focus=null" />
        <Loading v-if="loading" />
    </div>
</template>

<script>
    import Search from './BloodBankNetworking/Search'
    import Result from './BloodBankNetworking/Result'
    import Facility from './BloodBankNetworking/NetworkingFacility'

    export default{
        components : {Search, Result, Facility},
        data(){
            return {
                details : null, search : 0, facilities : [], loading : false, focus : null
            }
        },
        mounted(){
            
            this.loadFacilities(()=>{
                this.loadDistances()
            })
        },
        methods : {
            applyDetails(details){
                this.details = details
                this.search++
            },
            loadFacilities(callback){
                let {facility} = this.$session.get('user')
                this.loading = true;
                this.$http.get(this,'networking/facilities/'+facility.facility_name)
                .then(({data}) => {
                    this.facilities = data;
                    this.loading = false;
                    callback()
                })
            },
            loadDistances(){
                this.loading = true;
                let {facility : {facility_name}} = this.$session.get('user')
                let i = this.facilities.length
                this.facilities.map(f=>{
                    this.$http.post(this,'networking/distance',{
                        from : facility_name,
                        to : f.facility_name
                    })
                    .then(({data}) => {
                        i--;
                        f.distance = data
                        if(i <= 0){
                            this.loading = false
                        }
                    })
                })
            }
        }
    }
</script>