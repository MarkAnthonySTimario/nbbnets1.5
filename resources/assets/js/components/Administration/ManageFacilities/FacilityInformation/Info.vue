<template>
    <div>
        <div v-if="!loading" class="panel panel-warning">
            <div class="panel-heading">Facility Details</div>
            <table class="table table-condensed table-bordered">
                <tbody>
                    <tr>
                        <td class="col-lg-5">Facility Code</td>
                        <td>{{facility.facility_cd}}</td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>{{facility.facility_name}}</td>
                    </tr>
                    <tr>
                        <td>Lead Facility</td>
                        <td><span v-if="facility.lead">{{facility.lead.facility_name}}</span></td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>{{facility_types[facility.type]}}</td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>{{facility_cats[facility.category]}}</td>
                    </tr>
                    <tr>
                        <td>Head / Owner</td>
                        <td>{{facility.owner_name}}</td>
                    </tr>
                    <tr>
                        <td>No. / Street / Block</td>
                        <td>{{facility.address_no_st_blk}}</td>
                    </tr>
                    <tr>
                        <td>Barangay / City / Province / Region / Zip</td>
                        <td>
                            <span v-if="facility.barangay">{{facility.barangay.bgyname}}</span> /
                            <span v-if="facility.city">{{facility.city.cityname}}</span> /
                            <span v-if="facility.province">{{facility.province.provname}}</span> /
                            <span v-if="facility.region">{{facility.region.regname}}</span> /
                            <span>{{facility.address_zip}}</span> 
                        </td>
                    </tr>
                    <tr>
                        <td>Contact Person / Designation / User ID</td>
                        <td>{{facility.contact_name}} / {{facility.designation}} / {{facility.contact_user_id}}</td>
                    </tr>
                    <tr>
                        <td>Telephone / Mobile / Fax / Email</td>
                        <td>{{facility.tel_no}} / {{facility.mobile_no}} / {{facility.fax}} / {{facility.email}}</td>
                    </tr>
                    <tr>
                        <td>Date Registered / Registered By</td>
                        <td>{{facility.created_dt.substr(0,10) }} / {{facility.created_by}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <loading v-if="loading"></loading>
    </div>
</template>

<script>
export default {
    props : ['facility'],
    data(){
        return{
            loading : true,
            facility_types : [], facility_cats : []
        }
    },
    mounted(){
        this.$http.get(this,'keyvalues/facilitytypes')
        .then(({data}) => {
            this.facility_types = data; 
        });

        this.$http.get(this,'keyvalues/facilitycathergories')
        .then(({data}) => {
            this.facility_cats = data; 
            this.loading = false;
        });
    }
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