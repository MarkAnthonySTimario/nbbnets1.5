<template>
    <div>
        <div v-if="!loadingPage" class="row">
            <div class="col-lg-12">
                <options :sched="sched" :opts="opts" @selectMBDAgency="selectMBDAgency" @selectedMBDAgency="selectedMBDAgency" @fetch="fetchRecords"></options>
            </div> 
        </div>
        <div class="row">
            <div class="col-lg-12 text-right" v-if="result.length">
                <button class="btn btn-default btn-sm" :disabled="!sched.sched_id">Print</button> <button class="btn btn-default btn-sm" :disabled="!sched.sched_id">Spreed Sheet</button>
            </div>
        </div>
        <result v-if="!loadingPage" :sched="sched" :exams="exams" :opts="opts" :result="result" :fetching="fetching"></result>
        <loading v-if="loadingPage"></loading>
        <br/>
        <br/>
        <br/>
    </div>
</template>

<script>

import Options from './MBDReport/Options.vue';
import Result from './MBDReport/Result.vue';

let opts = {
    created_dt : {value : false, label : "Date Encoded"},
    fname : {value : true, label : "First Name"},  mname : {value: true, label : "Middle Name"},  lname : {value: true, label : "Last Name"},
    blood_type : {value : true, label : "ABO/Rh"},  bdate : {value: true, label : "Birthdate"},  gender : {value: true, label : "Gender"},
    address : {value: true, label : "Address"},  donation_id : {value: true, label : "Donation ID"},  mhpe : {value: true, label : "MH/PE Result"},
    collection_stat : {value: true, label : "Collection Status"},  donor_type : {value: true, label : "Type of Donor"}, tti : {value: true, label : "TTIs"},  
};
export default {
    components : {Options,Result},
    data(){
        let {sched} = this.$store.state;
        
        return {
            opts, sched, loadingPage: false, exams : [], result : [], fetching : false
        }
    },
    mounted(){
        this.loadingPage = true;
        this.$http.get(this,"keyvalues/exams")
        .then(({data}) => {
            this.loadingPage = false;
            this.exams = data;
        });
    },
    methods : {
        selectMBDAgency(){
            $("#MBDSelector").modal("show");
        },
        selectedMBDAgency(sched){
            this.sched = sched;
            $("#MBDSelector").modal("hide");
        },
        fetchRecords(){
            let {sched} = this;
            let {facility_cd} = this.$session.get('user');
            this.fetching = true;
            this.$http.post(this,'mbd/report',{
                sched, facility_cd
            })
            .then(({data}) => {
                this.result = data;
                this.fetching = false;
            });
        }
    }
}
</script>


