<template>
    <div>
        <router-link to="/ManageFacilities" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-arrow-left"></span> Back to List</router-link>
        <router-link :to="'/ManageFacilities/UpdateFacility/'+facility_cd" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-cog"></span> Update Facility Parameters</router-link>
        <br/><br/>
        <div v-if="!loading">
            <div class="row">
                <div class="col-lg-6">
                    <info :facility="facility"></info>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            Blood Label
                        </div>
                        <div class="panel-body">
                            <div v-html="raw"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <parameters :facility="facility"></parameters>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Users
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Access Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="u in facility.users" :key="u.username">
                                    <td>{{u.username}}</td>
                                    <td>{{u.user_fname}} {{u.user_mname}} {{u.user_lname}}</td>
                                    <td>{{u.level ? u.level.userlevelname : null}}</td>
                                </tr>
                            </tbody>
                        </table>
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
            loading : true, facility : null, raw : null
        }
    },
    mounted(){
        this.$http.post(this,'admin/facility',{
            facility_cd : this.facility_cd
        })
        .then(({data}) => {
            this.facility = data;
            this.loading = false;
            this.refreshHTML()
        })
    },
    methods : {
        refreshHTML(){
            this.loading = true;
            let {facility_cd} = this;

            this.$http.get(this,"labeltemplate/gettemplate/"+facility_cd)
            .then(({data}) => {
                this.raw = this.prepareTemplate(data);
                this.loading = false;
            });
        }
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

