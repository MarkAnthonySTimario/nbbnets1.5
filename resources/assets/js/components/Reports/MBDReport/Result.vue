<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <table class="report_1 col-lg-6">
                    <thead>
                        <tr>
                            <th width="200">MBD</th>
                            <td>{{sched.agency_name}}</td>
                        </tr>
                        <tr>
                            <th>MBD Date</th>
                            <td>
                                <div v-if="sched.sched_id">
                                    <span v-if="sched.sched_id != 'Walk-in'">{{sched.donation_dt.substr(0,10)}}</span>
                                    <span v-if="sched.sched_id == 'Walk-in'">{{sched.from}} - {{sched.to}}</span>
                                </div>
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-12 table-responsive">
                <table class="report_1 col-lg-12">
                    <thead>
                        <tr>
                            <th v-if="opts.created_dt.value">Date Encoded</th>
                            <th v-if="opts.lname.value">Last Name</th>
                            <th v-if="opts.fname.value">First Name</th>
                            <th v-if="opts.mname.value">Middle Name</th>
                            <th v-if="opts.blood_type.value">ABO/Rh</th>
                            <th v-if="opts.bdate.value">Birthdate</th>
                            <th v-if="opts.gender.value">Gender</th>
                            <th v-if="opts.address.value">Address</th>
                            <th v-if="opts.donation_id.value">Donation ID</th>
                            <th v-if="opts.mhpe.value">MH/PE Result</th>
                            <th v-if="opts.collection_stat.value">Collection Status</th>
                            <th v-if="opts.donor_type.value">Type of Donor</th>
                            <!-- <th v-if="opts.tti.value" v-for="(tti,cd) in exams" :key="cd">{{tti}}</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!result.length && !fetching">
                            <td :colspan="opts.tti.value ? (Object.keys(activeOpts).length)+Object.keys(exams).length : Object.keys(activeOpts).length">No Result Found</td>
                        </tr>
                        <tr v-for="(row,i) in result" :key="i" v-if="!fetching">
                            <th v-if="opts.created_dt.value">{{row.created_dt.substr(0,10)}}</th>
                            <th v-if="opts.lname.value">{{row.donor ? row.donor.lname : ''}}</th>
                            <th v-if="opts.fname.value">{{row.donor ? row.donor.fname : ''}}</th>
                            <th v-if="opts.mname.value">{{row.donor ? row.donor.mname : ''}}</th>
                            <th v-if="opts.blood_type.value">{{row.type ? row.type.blood_type : ''}}</th>
                            <th v-if="opts.bdate.value">{{row.donor ? row.donor.bdate : ''}}</th>
                            <th v-if="opts.gender.value">
                                <span v-if="row.donor">{{row.donor.gender | gender}}</span>
                            </th>
                            <th v-if="opts.address.value">
                                <span v-if="row.donor">
                                    {{row.donor.home_no_st_blk}}, 
                                    <span v-if="row.donor.barangay">{{row.donor.barangay.bgyname}}</span> ,
                                    <span v-if="row.donor.city">{{row.donor.city.cityname}}</span> ,
                                    <span v-if="row.donor.province">{{row.donor.province.provname}}</span> ,
                                    <span v-if="row.donor.region">{{row.donor.region.regname}}</span> 
                                </span>
                            </th>
                            <th v-if="opts.donation_id.value">{{row.donation_id}}</th>
                            <th v-if="opts.mhpe.value">{{row.mh_pe_stat | mhpe}}</th>
                            <th v-if="opts.collection_stat.value">{{row.collection_stat | collection}}</th>
                            <th v-if="opts.donor_type.value">{{row.donation_type | donationType}}</th>
                            <!-- <td v-for="(exam,cd) in exams" :key="cd" v-if="opts.tti.value">{{getTest(row.test,cd) | testResult}}</td> -->
                        </tr>
                        <tr v-if="fetching">
                            <td :colspan="opts.tti.value ? (Object.keys(activeOpts).length)+Object.keys(exams).length : Object.keys(activeOpts).length"><loadingInline label="Please wait, retrieving records.."></loadingInline></td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-12">
                <table class="report_1 col-lg-6">
                    <thead>
                        <tr>
                            <th width="200">Total</th>
                            <td>{{result.length}}</td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props : ['sched','opts','exams','result','fetching'],
    computed : {
        activeOpts(){
            return _.pickBy(this.opts,(v,k) => {
                if(v.value && k != 'tti'){
                    return k;
                }
            });
        }
    },
    methods : {
        getTest(test,exam_cd){
            if(!test){
                return null;
            }
            let t = _.find(test.details,{exam_cd});
            if(!t){
                return null;
            }
            return t.result_int.toUpperCase();
        }
    },
    filters : {
        testResult(testResult){
            if(!testResult){
                return '';
            }
            switch(testResult.toUpperCase()){
                case 'N':
                    return 'NR';
                break;
                case 'R':
                    return 'R';
                break;
                default:
                    return '';
            }
        }
    }
    
}
</script>


<style scoped>


table.report_1 {
  border-collapse: collapse;
  border: 2px solid #996;
  font: normal 70%/140% verdana, arial, helvetica, sans-serif;
  color: #333;
  background: #fffff0;
  }
caption.report_1 {
  padding: 0 .4em .4em;
  text-align: left;
  font-size: 1em;
  font-weight: bold;
  text-transform: uppercase;
  color: #333;
  background: transparent;
  }
.report_1 td, .report_1 th {
  border: 1px solid #cc9;
  padding: .3em;
  }
.report_1 thead th,.report_1 tfoot th {
  border: 1px solid #cc9;
  text-align: left;
  font-size: 1em;
  font-weight: bold;
  color: #444;
  background: #dbd9c0;
  }
.report_1 tbody td a {
  background: transparent;
  color: #72724c;
  text-decoration: none;
  border-bottom: 1px dotted #cc9;
  }
.report_1 tbody td a:hover {
  background: transparent;
  color: #666;
  border-bottom: 1px dotted #72724c;
  }
.report_1 tbody th a {
  background: transparent;
  color: #72724c;
  text-decoration: none;
  font-weight:bold;
  border-bottom: 1px dotted #cc9;
  }
.report_1 tbody th a:hover {
  background: transparent;
  color: #666;
  border-bottom: 1px dotted #72724c;
  }
.report_1 tbody th,.report_1 tbody td {
  vertical-align: top;
  text-align: left;
  }
.report_1 tfoot td {
  border: 1px solid #996;
  }
.report_1 .odd {
  color: #333;
  background: #f7f5dc;
  }
.report_1 tbody tr:hover {
  color: #333;
  background: #fff;
  }
.report_1 tbody tr:hover th,
.report_1 tbody tr.odd:hover th {
  color: #333;
  background: #ddd59b;
  }
</style>