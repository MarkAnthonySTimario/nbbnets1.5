<template>
    <div>
        <h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">Operations Configuration</h3><br/>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Disable</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.disable_flg" :value="true"></div>
        </div>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">% Allowed Availment</label>
            <div class="col-lg-4"><input type="number" class="form-control input-sm" v-model="config.bsf_av"></div>
        </div>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Max Donor Age</label>
            <div class="col-lg-4"><input type="number" class="form-control input-sm" v-model="config.max_donor_age"></div>
        </div>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Min Donor Age</label>
            <div class="col-lg-4"><input type="number" class="form-control input-sm" v-model="config.min_donor_age"></div>
        </div>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Months before next Donation</label>
            <div class="col-lg-4"><input type="number" class="form-control input-sm" v-model="config.no_months_to_nxt_don"></div>
        </div>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Hours before Reservation Cancelled</label>
            <div class="col-lg-4"><input type="number" class="form-control input-sm" v-model="config.res_hrs"></div>
        </div>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Days to Warn for Expiring Units</label>
            <div class="col-lg-4"><input type="number" class="form-control input-sm" v-model="config.no_days_expire_warning"></div>
        </div>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Standard Blood Unit Duration</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.standard_bu_duration" :value="true"></div>
        </div> 
        <h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">Optional Tests</h3>
        <hr/>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Nucliec Acid Testing</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.nat"></div>
        </div>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Donor Anti Body Screening</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.antibody"></div>
        </div>
        <div class="form-group required">
            <label for="" class="control-label col-lg-8">Zika Testing</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.zika"></div>
        </div>
        <h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">R3 Access</h3>
        <hr/>
        <div class="form-group">
            <label for="" class="control-label col-lg-8">Allow R3 Access</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.access_flg"></div>
        </div>
        <div class="form-group">
            <label for="" class="control-label col-lg-8">R3 Report Email</label>
            <div class="col-lg-4"><input type="email" class="form-control input-sm" v-model="config.report_email"></div>
        </div>
        <div class="form-group">
            <label for="" class="control-label col-lg-8">Allow R3 Facility Approval</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.admin_decide_flg"></div>
        </div>
        <div class="form-group">
            <label for="" class="control-label col-lg-8">Allow R3 Staff Access</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.user_access_flg"></div>
        </div>
        <div class="form-group">
            <label for="" class="control-label col-lg-8">Allow R3 Staff Request</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.user_request_flg"></div>
        </div>
        <h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">Transfusion Configuration</h3>
        <hr/>
        <div class="form-group">
            <label for="" class="control-label col-lg-8">Auto-generate Patient ID</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.auto_patient_id"></div>
        </div>
        <div class="form-group">
            <label for="" class="control-label col-lg-8">Ward No.</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.enable_patient_ward_no"></div>
        </div>
        <div class="form-group">
            <label for="" class="control-label col-lg-8">Room No.</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.enable_patient_room_no"></div>
        </div>
        <div class="form-group">
            <label for="" class="control-label col-lg-8">Bed No.</label>
            <div class="col-lg-1"><input type="checkbox" class="form-control input-sm" v-model="config.enable_patient_bed_no"></div>
        </div>
        <!-- div.form-group>label.control-label.col-lg-8+div.col-lg-1>input.form-control.input-sm -->
        
        <!-- div.form-group>label.control-label.col-lg-4+div.col-lg-2>input.form-control.input-sm -->
    </div>
</template>

<script>
export default {
    props : ['config']
}
</script>

