<template>
    <tr>
        <td style="font-size:12px;vertical-align:top; padding-top:1em;">{{row+1}}</td>
        <td :style="mh_pe_remarks ? 'font-size:12px;vertical-align:top; padding-top:1em;' : 'font-size:12px;vertical-align:middle;'" class="col-lg-3">
            {{donation.donor.fname | uppercase}} 
            {{donation.donor.mname | uppercase}} 
            {{donation.donor.lname | uppercase}} 
        </td>
        <td>
            <select class="form-control input-sm" v-model="donation.donation_stat">
                <option value="A">Accepted</option>
                <option value="TD">Temporary Deferred</option>
                <option value="PD">Permanently Deferred</option>
                <option value="ID">Indefinitely Deferred</option>
            </select>
        </td>
        <td v-if="donation.donation_stat != 'A' && donation.donation_stat != null">
            <a href="#mhpe_questionaire"  data-toggle="modal">
                <input type="text" class="form-control input-sm" v-model="donation.mh_pe_question"  :disabled="donation.donation_stat == 'A' || !donation.donation_stat" @click.prevent="$emit('mhpeClicked')" readonly style="background:#fff;">
            </a>
        </td>
        <td v-if="donation.donation_stat == 'A' || donation.donation_stat == null">
            <input class="form-control input-sm" disabled />
        </td>
        <td class="col-lg-2">
            <div class="input-group">
                <input type="text" class="form-control input-sm" v-model="new_remark" :disabled="donation.donation_stat == 'A' || !donation.donation_stat">
                <div class="input-group-btn"><button class="btn btn-success btn-sm" :disabled="!new_remark" @click="saveRemark"><span class="glyphicon glyphicon-ok"></span></button></div>
            </div>
            <span v-if="mh_pe_remarks">
                <div class="btn-group" v-for="(remark,i) in mh_pe_remarks" :key="i" style="margin:0.5em;display:flex;">
                    <button class="btn btn-danger btn-xs" @click="removeRemark(remark)">{{remark}}</button>
                    <button class="btn btn-default btn-xs" @click="removeRemark(remark)"><span class="glyphicon glyphicon-remove text-danger"></span></button>
                </div>
            </span>
        </td>
        <td>
            <select class="form-control input-sm" v-model="donation.collection_stat" :disabled="!donation.donation_stat || donation.donation_stat != 'A'">
                <option value="COL">Successful</option>
                <option value="UNS">Unsuccessfuly</option>
            </select>
        </td>
        <td>
            <input type="text" class="form-control input-sm" v-model="donation.donation_id"  :disabled="!donation.donation_stat || donation.donation_stat != 'A'" maxlength="16">
        </td>
        <td>
            <!-- <input type="text" class="form-control input-sm" v-model="donation.coluns_res" :disabled="!donation.donation_stat || (!donation.collection_stat || donation.collection_stat != 'UNS')"> -->
            <select class="form-control input-sm"  v-model="donation.coluns_res" :disabled="!donation.donation_stat || (!donation.collection_stat || donation.collection_stat != 'UNS')">
                <option :value="null"></option>
                <option>Buldge</option>
                <option>Faint</option>
                <option>Clot</option>
                <option>Others</option>
            </select>
        </td>
    </tr>
</template>

<script>

export default {
  props : ['donation','donation_statuses','row'],
  data(){
      return {
          mh_pe_remarks : [], new_remark : null
      }
  },
  methods : {
      saveRemark(){
          this.mh_pe_remarks.push(this.new_remark);
          this.new_remark = null;
      },
      removeRemark(remark){
          this.mh_pe_remarks = _.filter(this.mh_pe_remarks,(r) => {
              return r !== remark;
          });
      }
  },
  computed : {
      mh_pe_remark(){
          return this.donation.mh_pe_remark;
      }
  },
  watch : {
      donation :{
          handler(){
              this.donation.donation_id = this.donation.donation_id ? this.donation.donation_id.toUpperCase() : null;
              this.donation.coluns_res = this.donation.coluns_res ? this.donation.coluns_res.toUpperCase() : null;
          },
          deep : true
      },
      new_remark(){
          this.new_remark = this.new_remark ? this.new_remark.toUpperCase() : null;
      },
      mh_pe_remark(){
          if(this.mh_pe_remark != null){
             this.mh_pe_remarks = this.mh_pe_remark.split(",");
          }
      },
      mh_pe_remarks(){
          this.donation.mhpe_remark = this.mh_pe_remarks.join(',');
      }
  }
}
</script>

<style scoped>
    input:focus, select:focus {
        background-color: #f1f1f1 !important;
    }
</style>