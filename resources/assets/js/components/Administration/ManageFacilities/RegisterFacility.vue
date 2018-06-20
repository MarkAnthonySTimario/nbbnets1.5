<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        Register New Facility
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div role="tabpanel">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#FacilityInfo" aria-controls="FacilityInfo" role="tab" data-toggle="tab">Facility Information</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#Parameters" aria-controls="Parameters" role="tab" data-toggle="tab">Parameters</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#LabelTemplate" aria-controls="LabelTemplate" role="tab" data-toggle="tab">Blood Bag Label</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#Users" aria-controls="Users" role="tab" data-toggle="tab">Users</a>
                                    </li>
                                </ul>
                            
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="FacilityInfo">
                                        <facility-info :facility="facility"></facility-info>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="Parameters">
                                        <parameters :config="config"></parameters>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="LabelTemplate">
                                        <facility-label :init="label" @update="(raw) => {label = raw}"></facility-label>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="Users">
                                        <users :facility="facility"></users>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="pull-right">
                                    <button class="btn btn-default btn-sm" @click="saveFacility" :disabled="incomplete">Save</button>
                                    <router-link to="/ManageFacilities" class="btn btn-danger btn-sm">Cancel</router-link>
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
import FacilityInfo from './RegisterFacility/FacilityInfo.vue';
import Parameters from './RegisterFacility/Parameters.vue';
import FacilityLabel from './RegisterFacility/FacilityLabel.vue';
import Users from './RegisterFacility/Users.vue';

export default {
    components : {FacilityInfo,Parameters,FacilityLabel,Users},
    data(){
        let config = this.newConfig();

        let facility = this.newFacility();

        // facility.facility_name = 'test';
        // facility.facility_type = 'HOSP';
        // facility.category = 'BC';
        // facility.region = '08';
        // facility.users = [{"user_id":"test","ulevel":1,"fname":"test","mname":"test","lname":"test"},{"user_id":"test2","ulevel":3,"fname":"test","mname":"test","lname":"test"}];

        return {
            label : "", config, facility, 
            
        }
    },

    methods : {

        newConfig(){
            return {
                disable_flg : false,
                bsf_av : 60,
                max_donor_age : 65,
                min_donor_age : 16,
                no_months_to_nxt_don : 2,
                res_hrs : 8,
                no_days_expire_warning : 2,
                standard_bu_duration : false,

                nat : false,
                antibody : false,
                zika : false,

                enable_patient_ward_no : false,
                enable_patient_room_no : false,
                enable_patient_bed_no : false,

                access_flg : false,
                report_email : 'support@nbbnets.net',
                admin_decide_flg : false,
                user_access_flg : false,
                user_request_flg : false,
            }
        },

        newFacility(){
            return {
                facility_name : null,
                facility_type : null,
                category : null,
                owner_name : null,
                address_no_st_blk : null,
                contact_name : null,
                contact_user_id : null,
                designation : null,
                email : 'support@nbbnets.net',
                mobile_no : null,
                tel_no : null,
                fax : null,
                address_zip : null,
                users : [],
                region : null, province : null, city : null, barangay : null,
            };
        },

        hasDuplicates(collection,property) {
            return _.uniqBy(collection,property).length !== collection.length; 
        },

        saveFacility(){
            let {user_id} = this.$session.get('user');
            let {facility,config,label} = this.$data;
            let {users} = facility;
            this.loading = true;
            this.$http.post(this,'admin/registerfacility',{
                facility, users, config, template : label, user_id
            })
            .then(({data}) => {
                
                this.loading = false;
                this.$store.state.msg = {
                    content : 'Facility Registration Complete!'
                };
                this.$router.replace("/ManageFacilities/info/"+data.facility_cd);
            })
        }

    },

    computed : {

        incomplete(){
            
            let {
                facility : {facility_name,facility_type,category,region,email,users},
                config : {bsf_av,max_donor_age,min_donor_age,no_months_to_nxt_don,res_hrs,no_days_expire_warning,report_email}
            } = this;
            
            let validFacilityInfo = facility_name && facility_type && category && region && email;

            let validConfig = bsf_av && max_donor_age && min_donor_age && no_months_to_nxt_don && res_hrs && no_days_expire_warning && report_email;
            
            let validUsers = _.filter(users,({user_id,ulevel,fname,mname,lname}) => {
                if(user_id && ulevel && fname && mname && lname){
                    return true;
                }
            });

            let usersHasNoDuplication = !this.hasDuplicates(validUsers,'user_id');

            // console.log(validUsers);

            if(validFacilityInfo && validConfig && validUsers.length >= 2 && usersHasNoDuplication){
                return false;
            }

            return true;
        }

    }
}
</script>


