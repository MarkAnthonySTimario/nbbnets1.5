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
                            <div class="col-lg-6 form-horizontal">
                                <!-- div.form-group>label.control-label.col-lg-4+div.col-lg-6 -->
                                <h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">General Information</h3><br/>
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
                                    <label for="" class="control-label col-lg-4">Head of Facility</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control input-sm">
                                    </div>
                                </div>

                                <addressPicker :defs="addresspicker" @onchange="newAddress"></addressPicker>

                                <div class="form-group">
                                    <label for="" class="control-label col-lg-4">No./Street/Block</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control input-sm" placeholder="First  Middle  Last" v-model="facility.owner_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="control-label col-lg-4">Zip</label>
                                    <div class="col-lg-6">
                                        <input type="number" class="form-control input-sm" v-model="facility.address_zip">
                                    </div>
                                </div>
                                <h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">Contact Information</h3><br/>
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
                                <div class="form-group">
                                    <label for="" class="control-label col-lg-4">Designation</label>
                                    <div class="col-lg-6">
                                        <select name="" id="" class="form-control input-sm" v-model="facility.designation"></select>
                                    </div>
                                </div>
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
                                    <div class="col-lg-6"><input type="text" class="form-control input-sm" v-model="facility.fax_no"></div>
                                </div>
                                
                                
                                
                                <!-- div.form-group>label.control-label.col-lg-4+div.col-lg-6 -->
                            </div>
                            <div class="col-lg-6 form-horizontal">
                                <parameters :config="config"></parameters>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">Blood Bag Label Template</h3><br/>
                            </div>
                            <div class="col-lg-6">
                                <div v-html="output" style="width:370px; margin-left:auto;margin-right:auto;">

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label-template @update="(raw) => {template = raw}" style="margin-left:auto;margin-right:auto;"></label-template>
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
import Parameters from './RegisterFacility/Parameters.vue';
import LabelTemplate from './RegisterFacility/LabelTemplate.vue';

export default {
    components : {Parameters,LabelTemplate},
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
        });
    },
    computed : {
      addresspicker(){
          return {
              region : null, province : null, city : null, barangay : null
          };
      },
      output(){
          let {template} = this;
          template = template.replaceAll("{{FACILITY_NAME}}",this.facility ? this.facility.facility_name : 'Department of Health');
          template = template.replaceAll("{{BARCODE}}",'<div style="background:#fff;width:100%;height:50px;text-align:center;vertical-align:middle;"><img src="images/sample-barcode.jpg" width="320" height="45" /></div>');
          template = template.replaceAll("{{ABO}}","B");
          template = template.replaceAll("{{RH}}","Positive");
          template = template.replaceAll("{{COMPONENT}}","FRESH FROZEN PLASMA");
          template = template.replaceAll("{{VOLUME}}","150");
          template = template.replaceAll("{{COLLECTION_DATE}}","January 06, 2018");
          template = template.replaceAll("{{EXPIRATION_DATE}}","January 06, 2019 23:59:00");
          template = template.replaceAll("{{STORE}}","Store at -18 to -89 &deg;C");
          return template;
      }
    },
    methods : {
      newAddress({region,province,city,barangay}){
          this.adg_region = region;
          this.adg_prov = province;
          this.adg_city = city;
          this.adg_bgy = barangay;
      }
    }
}
</script>


