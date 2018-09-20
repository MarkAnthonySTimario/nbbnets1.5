<template>
  <div class="row">
      <div v-if="!loading">
        <div class="col-lg-6">
            <div class="panel panel-warning" v-if="donation_id">
                <div class="panel-heading">Assign Donor to Walk-in Donation</div>
                <table class="table table-bordered table-condensed">
                    <tbody>
                        <tr>
                            <td class="col-lg-4">Donation ID</td>
                            <td>{{donation_id}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-user"></span> Update Donor
                    <span class="pull-right">
                        <router-link :to="('./../../Verifier')" class="btn btn-default btn-xs">
                            <span class="glyphicon glyphicon-arrow-left"></span> Back to List
                        </router-link>
                        
                      </span>
                </div>
                <div class="panel-body form-horizontal">
                    <donor-pic-capture @oncomplete="setPhoto"></donor-pic-capture>
                    <div class="form-group required">
                        <label for="" class="control-label col-lg-3">First Name</label>
                        <div class="col-lg-9"><input v-validate="'required'" type="text" class="form-control input-sm" v-model="fname" name="First Name"></div>
                        <i class="text-danger error col-lg-9 col-lg-offset-3" v-show="errors.has('First Name')">{{ errors.first('First Name') }}</i>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3">Middle Name</label>
                        <div class="col-lg-9"><input type="text" class="form-control input-sm" v-model="mname"></div>
                    </div>
                    <div class="form-group required">
                        <label for="" class="control-label col-lg-3">Last Name</label>
                        <div class="col-lg-9"><input v-validate="'required'" type="text" class="form-control input-sm" v-model="lname" name="Last Name"></div>
                        <i class="text-danger error col-lg-9 col-lg-offset-3" v-show="errors.has('Last Name')">{{ errors.first('Last Name') }}</i>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3">Name Suffix</label>
                        <div class="col-lg-9"><input type="text" class="form-control input-sm" v-model="name_suffix"></div>
                    </div>
                    <div class="form-group required">
                        <label for="" class="control-label col-lg-3">Gender</label>
                        <div class="col-lg-9">
                            <select v-validate="'required'" name="Gender" class="form-control input-sm" v-model="gender">
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                        <i class="text-danger error col-lg-9 col-lg-offset-3" v-show="errors.has('Gender')">{{ errors.first('Gender') }}</i>
                    </div>
                    <div class="form-group required">
                        <label for="" class="control-label col-lg-3">Birth Date</label>
                        <div class="col-lg-6">
                            <input v-validate="'required'" name="Birth Date" type="date" class="form-control input-sm" v-model="bdate">
                            <!-- <datepicker format="MM/dd/yyyy" typeable="true" v-model="bdate" /> -->
                        </div>
                        <div class="col-lg-3" style="margin-top:0.5em;">{{bdate | age}} years old</div>
                        <i class="text-danger error col-lg-9 col-lg-offset-3" v-show="errors.has('Birth Date')">{{ errors.first('Birth Date') }}</i>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3">Civil Status</label>
                        <div class="col-lg-9">
                            <select class="form-control input-sm" v-model="civil_stat">
                                <option v-for="(val,code) in $session.get('civil_status')" :key="code" :value="code">{{val}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3">Occupation</label>
                        <div class="col-lg-9"><input type="text" class="form-control input-sm" v-model="occupation"></div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3">Nationality</label>
                        <div class="col-lg-9">
                            <select class="form-control input-sm" v-model="nationality">
                                <option v-for="nation in nations" :key="nation.countrycode" :value="nation.countrycode">{{nation.nation}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3">Telephone</label>
                        <div class="col-lg-9"><input type="text" class="form-control input-sm" v-model="tel_no"></div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3">Mobile Phone</label>
                        <div class="col-lg-9"><input type="text" class="form-control input-sm" v-model="mobile_no"></div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-lg-3">Email</label>
                        <div class="col-lg-9"><input type="email" class="form-control input-sm" v-model="email"></div>
                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home Address</a></li>
                        <li role="presentation"><a href="#office" aria-controls="office" role="tab" data-toggle="tab">Office Address</a></li>
                    </ul>

                    <donor-addresses v-if="donor" @home="setHome" @office="setOffice" :donor="donor"></donor-addresses>
                    <!-- div.form-group.required>label.control-label.col-lg-3+div.col-lg-9>input.form-control.input-sm -->
                    
                </div>
                <div class="panel-footer">
                    <button class="btn btn-default btn-sm" @click.prevent="save">Update Donor</button>
                </div>
            </div>

        </div>
      </div>
      <loading v-if="loading"></loading>
      
  </div>
</template>

<script>
import { Validator } from 'vee-validate';
import DonorPicCapture from './selector/create/DonorPhoto.vue';
import DonorAddresses from './selector/create/DonorAddresses.vue';
import Datepicker from 'vuejs-datepicker';

const validator = new Validator({
                    fname: 'required',
                    lname: 'required',
                    gender: 'required',
                    bdate: 'required'
                });

export default {
  components : {DonorPicCapture,DonorAddresses,Datepicker},
  props : ['sched_id','seqno'],
  data(){
      let {currentRoute:{query:{donation_id}}} = this.$router;
      if(donation_id == null || donation_id == undefined || donation_id == 'null' || donation_id == 'undefined'){
          donation_id = null;
      }
      return { 
          donation_id ,
          loading : true, donor_photo : null, nations : [],
          fname : null, mname : null, lname : null, name_suffix : null, gender : null, bdate : null, civil_stat : null, 
          occupation : null,
          tel_no : null, mobile_no : null, email : null, 
          home : null, office : null, donor : {},
          nationality : 137
      };
  },
  mounted(){
      this.loading = true;
      this.$http.get(this,'donor/'+this.seqno)
      .then(({data}) => {
          let {
              fname,mname,lname,name_suffix,gender,bdate,civil_stat,occupation,
              nationality,tel_no,mobile_no,email,
              home_region,home_prov,home_citymun,home_brgy,home_no_st_blk,home_zip,
              office_region,office_prov,office_citymun,office_brgy,office_no_st_blk,office_zip,
              } = data;
          this.fname = fname;
          this.mname = mname;
          this.lname = lname;
          this.name_suffix = name_suffix;
          this.gender = gender;
          this.bdate = bdate;
          this.civil_stat = civil_stat;
          this.occupation = occupation;
          this.nationality = nationality;
          this.tel_no = tel_no;
          this.mobile_no = mobile_no;
          this.email = email;
          this.home = {
              region : home_region,
              province : home_prov,
              city : home_citymun,
              barangay : home_brgy,
              zip : home_zip,
              no_st_blk : home_no_st_blk,
              prefix : 'Home '
          };
          this.office = {
              region : office_region,
              province : office_prov,
              city : office_citymun,
              barangay : office_brgy,
              zip : office_zip,
              no_st_blk : office_no_st_blk,
              prefix : 'Office '
          };
          this.donor = {
              home : this.home,office : this.office
          }
          this.loading = false;
      })
      .catch(error => {
          this.$store.state.error = error;
      });

      this.$http.get(this,"keyvalues/nations")
      .then(({data}) => {
          this.nations = _.orderBy(data,['nation']);
      })
      .catch(error => {
          this.$store.state.error = error;
      });
  },
  methods: {
        setPhoto(photo){
            this.donor_photo = photo;
        },
        setHome(address){
            this.home = address;
        },
        setOffice(address){
            this.office = address;
        },
        save(){
            
            validator.validateAll(this.$data)
            .then(result => {
                if(result){
                    this.doSave();
                }else{
                    this.errors.add("First Name","Provide Donor's First Name");
                    this.errors.add("Last Name","Provide Donor's Last Name");
                    this.errors.add("Gender","Select Donor's Gender");
                    this.errors.add("Birth Date","Provide Donor's Date of Birth");
                }
            })
            .catch(error => {
                this.$store.state.error = error;
            });

            
        },
        doSave(){
            this.loading = true;
            
            let {donor_photo, seqno, donor_id, name_suffix, lname, fname, mname, bdate, gender, 
            civil_stat, occupation, tel_no, mobile_no, email, nationality, home, office} = this;
            
            let {facility_cd,user_id} = this.$session.get("user");
            this.$http.post(this,"donor/update",{
                donor_photo, seqno, donor_id, name_suffix, lname, fname, mname, bdate, gender, 
                civil_stat, occupation, tel_no, mobile_no, email, nationality, home, office, 
                facility_cd, user_id
            })
            .then(({data}) => {
                this.loading = false;
                this.$store.state.msg = {
                    type : 'success', content : 'Donor has been updated'
                };
                this.$router.replace('../'+data.seqno+"?donation_id="+this.donation_id);
            })
            .catch(error=>{
                this.$store.state.error = error;
            })
        }
    }
}
</script>
