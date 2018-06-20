<template>
    <div>
        <div v-if="!loading">
            <div class="row">
                <div class="col-lg-6">
                    <info :facility="facility"></info>
                </div>

                <div class="col-lg-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            Blood Label
                        </div>
                    </div>
                    <parameters :facility="facility"></parameters>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Recent Activity
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Users
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <loading v-if="loading"></loading>
    </div>
</template>

<script>
import Info from './FacilityInformation/Info.vue';
import Parameters from './FacilityInformation/Parameters.vue';
export default {
    components : {Info,Parameters},
    props : ['facility_cd'],
    data(){
        return {
            loading : true, facility : null
        }
    },
    mounted(){
        this.$http.post(this,'admin/facility',{
            facility_cd : this.facility_cd
        })
        .then(({data}) => {
            this.facility = data;
            this.loading = false;
        })
    }
}
</script>


<style scoped>
table td{
    font-size:12px;
    padding:0.5em !important;
    padding-left:1em !important;
}
</style>

