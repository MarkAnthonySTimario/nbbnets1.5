<template>
    <table class="table table-condensed table-bordered" style="border:none !important;" v-if="!loading">
        <tbody class="text-center">
            <tr>
                <td width="20%">Total Donations</td>
                <td width="10%">HIV</td>
                <td width="10%">HBV</td>
                <td width="10%">HCV</td>
                <td width="10%">Syphilis</td>
                <td width="10%">Malaria</td>
                <td width="15%">SOPs Used</td>
                <td width="15%">Participate in EQA</td>
            </tr>
            <tr class="text-bg">
                <td>{{figure['TOTAL']}}</td>
                <td>{{figure['HIV']}}</td>
                <td>{{figure['HBSAG']}}</td>
                <td>{{figure['HCV']}}</td>
                <td>{{figure['RPR']}}</td>
                <td>{{figure['MALA']}}</td>
                <td>{{yn.sop ? 'Yes' : 'No'}}</td>
                <td>{{yn.eqa ? 'Yes' : 'No'}}</td>
            </tr>

        </tbody>
    </table>
</template>

<script>
export default {
    props : ['year','month'],
    data(){
        return {
            figure : {
                TOTAL : 0, HIV : 0, HBSAG : 0, HCV : 0, RPR : 0, MALA : 0
            }, loading : true, yn : {
                sop : null, eqa : null
            }
        }
    },
    mounted(){
        this.fetchData();
        this.fetchYesNoAnswer();
        this.loading = false;
    },
    methods : {
        fetchYesNoAnswer() {
            let {year,month} = this;
            this.$http.post(this, 'bsi/yesno', {
                facility_cd: this.$session.get('user').facility_cd,
                question_no: '3.7.1', year, month
            })
            .then(({data})=>{
                this.yn.sop = data;
            })
            this.$http.post(this, 'bsi/yesno', {
                facility_cd: this.$session.get('user').facility_cd,
                question_no: '3.7.2', year, month
            })
            .then(({data})=>{
                this.yn.eqa = data;
            })
        },
        fetchData() {
            let {year,month} = this;
            this.$http.post(this, 'bsi/item', {
                facility_cd: this.$session.get('user').facility_cd,
                question_no: '3.7.1', year, month
            })
            .then(({data})=>{
                this.figure = data;
            })
        }  
    }
}
</script>

<style scoped>
thead > tr {
    background-color: #ccc;
}
.text-bg {
    background-color: #FFFF99;
}
.section {
    background-color: #993366;
    color: #fff;  
    font-weight: bold;
}
</style>