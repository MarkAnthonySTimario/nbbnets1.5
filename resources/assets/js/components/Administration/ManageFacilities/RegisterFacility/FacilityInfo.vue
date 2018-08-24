<template>
    <div class="form-horizontal">
        <div class="col-lg-6">
            <!-- div.form-group>label.control-label.col-lg-4+div.col-lg-6 -->
            <br/><h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">General Information</h3><br/>
            <div class="form-group required">
                <label for="" class="control-label col-lg-4">Name of Facility</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control input-sm" v-model="facility.facility_name">
                </div>
            </div>
            <div class="form-group required">
                <label for="" class="control-label col-lg-4">Type</label>
                <div class="col-lg-6">
                    <select name="" id="" class="form-control input-sm" v-model="facility.facility_type">
                        <option value=""></option>
                        <option :value="code" v-for="(value,code) in facility_types" :key="code">{{value}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group required">
                <label for="" class="control-label col-lg-4">Category</label>
                <div class="col-lg-6">
                    <select name="" id="" class="form-control input-sm" v-model="facility.category">
                        <option value=""></option>
                        <option :value="code" v-for="(value,code) in facility_cats" :key="code">{{value}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-lg-4">Lead Facility</label>
                <div class="col-lg-6">
                    <select type="text" class="form-control input-sm" v-model="facility.lead_facility">
                        <option :value="f.facility_cd" v-for="f in facilities" :key="f.facility_cd">{{f.facility_name}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-lg-4">Head of Facility/Owner</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control input-sm" placeholder="First  Middle  Last" v-model="facility.owner_name">
                </div>
            </div>

            <addressPicker :defs="addresspicker" @onchange="newAddress" :req="['region']"></addressPicker>

            <div class="form-group">
                <label for="" class="control-label col-lg-4">No./Street/Block</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control input-sm" placeholder="First  Middle  Last" v-model="facility.address_no_st_blk">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-lg-4">Zip</label>
                <div class="col-lg-6">
                    <input type="number" class="form-control input-sm" v-model="facility.address_zip">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <br/><h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">Contact Information</h3><br/>
            <div class="form-group">
                <label for="" class="control-label col-lg-4">Contact Person</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control input-sm" placeholder="First  Middle  Last" v-model="facility.contact_name">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-lg-4">Contact Person User ID</label>
                <div class="col-lg-6">
                    <input type="text" class="form-control input-sm" v-model="facility.contact_user_id">
                </div>
            </div>
            <!-- <div class="form-group">
                <label for="" class="control-label col-lg-4">Designation</label>
                <div class="col-lg-6">
                    <select name="" id="" class="form-control input-sm" v-model="facility.designation"></select>
                </div>
            </div> -->
            <div class="form-group required">
                <label for="" class="control-label col-lg-4">Email Address</label>
                <div class="col-lg-6"><input type="email" class="form-control input-sm" v-model="facility.email"></div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-lg-4">Mobile No.</label>
                <div class="col-lg-6"><input type="text" class="form-control input-sm" v-model="facility.mobile_no"></div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-lg-4">Tel No.</label>
                <div class="col-lg-6"><input type="text" class="form-control input-sm" v-model="facility.tel_no"></div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-lg-4">Fax No.</label>
                <div class="col-lg-6"><input type="text" class="form-control input-sm" v-model="facility.fax"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default{
    props : ['facility'],
    data(){
        return{
            facility_types : [], facility_cats : [], facilities : []
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
        });

        this.$http.get(this,"admin/facility/listsimple")
        .then(({data}) => {
            this.facilities = data;
        })
    },
    computed : {
        addresspicker(){
            let {region,province,city,barangay,address_region,address_prov,address_citymun,address_bgy} = this.facility;
            return {
                region : address_region ? address_region : region , 
                province : address_prov ? address_prov : province, 
                city : address_citymun ? address_citymun : city , 
                barangay : address_bgy ? address_bgy : barangay
            };
        },
    },
    methods : {
        newAddress({region,province,city,barangay}){
            this.facility.region = region;
            this.facility.province = province;
            this.facility.city = city;
            this.facility.barangay = barangay;
        }
    }
}
</script>