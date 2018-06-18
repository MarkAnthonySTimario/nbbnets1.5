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
                                    <li role="presentation">
                                        <a href="#FacilityInfo" aria-controls="FacilityInfo" role="tab" data-toggle="tab">Facility Information</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#Parameters" aria-controls="Parameters" role="tab" data-toggle="tab">Parameters</a>
                                    </li>
                                    <li role="presentation" class="active">
                                        <a href="#LabelTemplate" aria-controls="LabelTemplate" role="tab" data-toggle="tab">Blood Bag Label</a>
                                    </li>
                                </ul>
                            
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane " id="FacilityInfo">
                                        <facility-info :facility="facility"></facility-info>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="Parameters">
                                        <parameters :config="config"></parameters>
                                    </div>
                                    <div role="tabpanel" class="tab-pane active" id="LabelTemplate">
                                        <facility-label :init="template" @update="(raw) => {template = raw}"></facility-label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="pull-right">
                                    <button class="btn btn-default btn-sm">Save</button>
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

export default {
    components : {FacilityInfo,Parameters,FacilityLabel},
    data(){
        let config = {
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

            access_flg : false,
            report_email : 'support@nbbnets.net',
            admin_decide_flg : false,
            user_access_flg : false,
            user_request_flg : false,
        }

        let facility = {
            facility_name : null,
            type : null,
            category : null,
            owner_name : null,
            contact_name : null,
            contact_user_id : null,
            designation : null,
            email : 'support@nbbnets.net',
            mobile_no : null,
            tel_no : null,
            fax_no : null,
            address_zip : null,
            
        }

        return {
            region : null, province : null, city : null, barangay : null, template : "", config, facility, 
            
        }
    }
}
</script>


