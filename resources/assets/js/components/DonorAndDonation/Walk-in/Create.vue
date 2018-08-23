<template>
  <div class="row">
      <loading v-if="loading"></loading>
      <div class="col-lg-6" v-if="!loading">
          <div class="panel panel-warning">
              <div class="panel-heading">
                  New Walk-in Donation
                  <span class="pull-right">
                    <router-link :to="('./../' + this.seqno)" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-arrow-left"></span> Back to Profile</router-link>
                  </span>
               </div>
               <div class="panel-body form-horizontal">
                   <div class="form-group required">
                       <label for="" class="control-label col-lg-3">Donor</label>
                       <div class="col-lg-9"><div class="form-control input-sm">{{donor.fname}} {{donor.mname}} {{donor.lname}}</div></div>
                   </div>
                   <div class="form-group required">
                       <label for="" class="control-label col-lg-3">Type of Donor</label>
                       <div class="col-lg-9">
                           <select v-model="donation_type" class="form-control input-sm">
                               <option :value="code" v-for="(val,code) in donation_types" :key="code">{{val}}</option>
                           </select>
                       </div>
                   </div>
                   <div class="form-group required">
                       <label for="" class="control-label col-lg-3">MH / PE Result</label>
                       <div class="col-lg-9">
                           <select v-model="mh_pe_stat" class="form-control input-sm">
                               <option :value="code" v-for="(val,code) in donor_statuses" :key="code">{{val}}</option>
                           </select>
                       </div>
                   </div>
                   <div class="form-group" v-if="(mh_pe_stat != 'A')">
                       <label for="" class="control-label col-lg-3">MH / PE Remarks</label>
                       <div class="col-lg-9"><textarea type="text" class="form-control input-sm" v-model="mh_pe_remark"></textarea></div>
                       <i class="text-danger error col-lg-9 col-lg-offset-3" v-show="errors.has('Remarks')">{{ errors.first('Remarks') }}</i>
                   </div>
                   <div class="form-group required">
                       <label for="" class="control-label col-lg-3">Method of Blood Collection</label>
                       <div class="col-lg-9">
                           <select v-model="collection_method" class="form-control input-sm">
                               <option :value="code" v-for="(val,code) in collection_methods" :key="code">{{val}}</option>
                           </select>
                       </div>
                   </div>
                   <div class="form-group required">
                       <label for="" class="control-label col-lg-3">Status of Collection</label>
                       <div class="col-lg-9">
                           <select v-model="collection_stat" class="form-control input-sm" name="Collection Status">
                               <option :value="stat.code" v-for="stat in collection_stats" :key="stat.code">{{stat.value}}</option>
                           </select>
                       </div>
                        <i class="text-danger error col-lg-9 col-lg-offset-3" v-show="errors.has('Collection Status')">{{ errors.first('Collection Status') }}</i>
                   </div>
                   <div class="form-group" v-if="collection_unsuccessful">
                       <label for="" class="control-label col-lg-3">Reason for Unsuccessful Collection</label>
                       <div class="col-lg-9"><textarea v-model="coluns_res" class="form-control input-sm"></textarea></div>
                       <i class="text-danger error col-lg-9 col-lg-offset-3" v-show="errors.has('Reason')">{{ errors.first('Reason') }}</i>
                   </div>
                   <div class="form-group required">
                       <label for="" class="control-label col-lg-3">Donation ID</label>
                       <div class="col-lg-9"><input type="text" class="form-control input-sm" v-model="donation_id" name="Donation ID" maxlength="16"></div>
                       <i class="text-danger error col-lg-9 col-lg-offset-3" v-show="errors.has('Donation ID')">{{ errors.first('Donation ID') }}</i>
                   </div>
                   <div class="form-group">
                       <div class="col-lg-9 col-lg-offset-3">
                           <button class="btn btn-default btn-sm" @click="validateForm">Save Walk-in</button>
                       </div>
                   </div>
                   <!-- div.form-group.required>label.control-label.col-lg-3+div.col-lg-9>input.form-control.input-sm -->
               </div>
          </div>
      </div>
      <div class="col-lg-6">
          <questions v-if="(mh_pe_stat != 'A')" @onselect="updateMHPE"></questions>
      </div>
  </div>
</template>

<script>
import Questions from './../Questionaire/Questions.vue';


export default {
  components : {Questions},
  props : ['seqno'],
  data(){
      return {
          loading : true, donor : null, donation_type : 'V', donation_types : [], donor_statuses : [],
          mh_pe_stat : 'A', mh_pe_deferral : null, mh_pe_question : null, mh_pe_remark : null,
          collection_method : 'WB', collection_methods : [], collection_stat : 'COL', 
          collection_stats : [{ code : 'COL' , value : 'Successful'}, {code : 'UNS' , value : 'Unsuccessful'}],
          coluns_res : null, donation_id : null, facility_cd : this.$session.get('user').facility_cd,
          user_id : this.$session.get('user').user_id
      }
  },
  mounted(){
      this.$http.get(this,"keyvalues/donationtypes")
      .then(({data}) => {
          this.donation_types = data;
      })
      .catch(err => {
          this.$store.state.error = err;
      });

      this.$http.get(this,"keyvalues/donorstatuses")
      .then(({data}) => {
          this.donor_statuses = data;
      })
      .catch(err => {
          this.$store.state.error = err;
      });

      this.$http.get(this,"keyvalues/collectionmethods")
      .then(({data}) => {
          this.collection_methods = data;
      })
      .catch(err => {
          this.$store.state.error = err;
      });
      
      this.$http.get(this,"donor/"+this.seqno)
      .then(({data}) => {
          this.donor = data;
          this.loading = false;
      })
      .catch(err => {
          this.$store.state.error = err;
      });
  },
  methods : {
      updateMHPE(res){
          this.mh_pe_question = res.questions;
          this.mh_pe_deferral = res.pe;
      },
      validateForm(){
          this.errors.clear();
          if(this.collection_stat == 'U' && !this.coluns_res){
              this.errors.add('Reason','Provide reason for unsuccessful collection');
          }
          if(!this.donation_id){
              this.errors.add('Donation ID','Scan / Enter Donation ID');
          }
          if(this.donation_id.length != 16){
              this.errors.add('Donation ID','Invalid Format for Donaion ID');
          }else if(!this.donation_id.startsWith('NVBSP')){
              this.errors.add('Donation ID','Invalid Format for Donaion ID');
          }
          if(this.mh_pe_stat != 'A'){
              if(!this.mh_pe_remark && !this.mh_pe_deferral && !this.mh_pe_question){
                  this.errors.add('Remarks','Provide reason for Donor Deferral');
              }
          }
          if(!this.errors.count()){
              this.save();
          }
      },
      save(){
          this.loading = true;
          this.$http.post(this,"walkin/create",this.$data)
          .then(({data}) => {
            if(data == 'donation_id_error'){
                this.loading = false;
                this.errors.add('Donation ID','Donation ID already taken');
                return;
            }
            this.$store.state.msg = {
                type : 'success', content : 'Walk-in Donation has been created'
            };
            this.$router.replace('/Walkin');
          })
          .catch(error => {
              this.$store.state.error = error;
          });
      }
  },
  watch : {
      donation_id(){
          this.donation_id = this.donation_id.toUpperCase();
      }
  },
  computed : {
      collection_unsuccessful(){
          return this.collection_stat == 'U';
      }
  }
}
</script>