<template>
    <div class="panel panel-info">
        <div class="panel-heading">
            Parameters
        </div>
        <table class="table condensed table-bordered">
            <tbody>
                <tr>
                    <td colspan="2">Operations Configuration</td>
                </tr>
                <tr>
                    <td class="col-lg-8">Disable</td>
                    <td>{{facility.disable_flg ? 'Yes' : 'No'}}</td>
                </tr>
                <tr>
                    <td>% of Allowed BSF Availment</td>
                    <td>{{facility.bsf_av}} %</td>
                </tr>
                <tr>
                    <td>Maximum Donor Age</td>
                    <td>{{facility.max_donor_age}}</td>
                </tr>
                <tr>
                    <td>Minimum Donor Age</td>
                    <td>{{facility.min_donor_age}}</td>
                </tr>
                <tr>
                    <td>Months before Donor may donate again</td>
                    <td>{{facility.no_months_to_nxt_don}}</td>
                </tr>
                <tr>
                    <td>Hours before reservation expired</td>
                    <td>{{facility.res_hrs}}</td>
                </tr>
                <tr>
                    <td>No. of Days when System Warns Facility of Expiring Units</td>
                    <td>{{facility.no_days_expire_warning}}</td>
                </tr>
                <tr>
                    <td>Using Standard Blood Unit Duration</td>
                    <td>{{facility.standard_bu_duration}}</td>
                </tr>
                <tr>
                    <td colspan="2">Optional Tests</td>
                </tr>
                <tr>
                    <td>Nucliec Acid Testing</td>
                    <td>{{facility.nat ? 'Yes' : 'No'}}</td>
                </tr>
                <tr>
                    <td>Donor Antibody Screening</td>
                    <td>{{facility.antibody ? 'Yes' : 'No'}}</td>
                </tr>
                <tr>
                    <td>Zika Testing</td>
                    <td>{{facility.zika ? 'Yes' : 'No'}}</td>
                </tr>
                <tr>
                    <td colspan="2">R3 Access</td>
                </tr>
                <tr>
                    <td>Allow R3 Access</td>
                    <td>
                        <span v-if="facility.r3config">
                            {{facility.r3config.access_flg ? 'Yes' : 'No'}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>R3 Report Email</td>
                    <td>
                        <span v-if="facility.r3config">
                            {{facility.r3config.report_email}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Allow R3 Facility Approval</td>
                    <td>
                        <span v-if="facility.r3config">
                            {{facility.r3config.admin_decide_flg ? 'Yes' : 'No'}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Allow R3 Staff Access</td>
                    <td>
                        <span v-if="facility.r3config">
                            {{facility.r3config.user_access_flg ? 'Yes' : 'No'}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Allow R3 Staff Request</td>
                    <td>
                        <span v-if="facility.r3config">
                            {{facility.r3config.user_request_flg ? 'Yes' : 'No'}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Transfusion Configuration</td>
                </tr>
                <tr>
                    <td>Auto-generate Patient ID</td>
                    <td>
                        <span v-if="facility.transfusion_config">
                            {{facility.transfusion_config.auto_patient_id == 'Y' ? 'Yes' : 'No'}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Require Ward No.</td>
                    <td>
                        <span v-if="facility.transfusion_config">
                            {{facility.transfusion_config.enable_patient_ward_no == 'Y' ? 'Yes' : 'No'}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Require Room No.</td>
                    <td>
                        <span v-if="facility.transfusion_config">
                            {{facility.transfusion_config.enable_patient_room_no == 'Y' ? 'Yes' : 'No'}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Require Bed No.</td>
                    <td>
                        <span v-if="facility.transfusion_config">
                            {{facility.transfusion_config.enable_patient_bed_no == 'Y' ? 'Yes' : 'No'}}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props : ['facility']
}
</script>


<style scoped>
table td{
    font-size:12px;
    padding:0.5em !important;
    padding-left:1em !important;
}
tr td:nth-child(1){
    background-color: #f1f1f1;
    text-align:right;
}
</style>
