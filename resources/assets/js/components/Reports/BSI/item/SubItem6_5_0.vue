<template>
    <table class="table table-condensed table-bordered" style="border:none !important;" v-if="!loading">
        <tbody>
            <tr class="text-center">
                <td></td>
                <td width="10%">Male</td>
                <td width="10%">Female</td>
            </tr>
            <tr v-for="item in scope" :key="item.question_no">
                <td class="text-left">{{item.question_no}} {{item.label}}</td>
                <!-- <td>{{item.data['tti']}}</td> -->
                <td class="text-center text-bg">{{item.data['male']}}</td>
                <td class="text-center text-bg">{{item.data['female']}}</td>
            </tr>

        </tbody>
    </table>
</template>

<script>
export default {
    props : ['year','month'],
    data(){
        let scope = [
            {question_no : '6.5.1', label : 'Patients under 5 years', data : {}},
            {question_no : '6.5.2', label : 'Patients aged 5 to 14 years', data : {}},
            {question_no : '6.5.3', label : 'Patients aged 15 to 44', data : {}},
            {question_no : '6.5.4', label : 'Patients aged 45 to 59 years', data : {}},
            {question_no : '6.5.5', label : 'Patients aged 60 year and older', data : {}},
            {question_no : '6.5.6', label : 'Total number of patients transfused by gender', data : {}},            
        ];
        return {
            figure : [], loading : true, scope
        }
    },
    mounted(){
        this.scope.forEach((item) => {
            this.fetchData(item.question_no);
        })
        this.loading = false;
    },
    methods : {
        fetchData(question_no) {
            let {year,month} = this;
            this.$http.post(this, 'bsi/item', {
                facility_cd: this.$session.get('user').facility_cd,
                question_no, year, month
            })
            .then(({data})=>{
                _.find(this.scope,{question_no}).data = data;
            })
        },
        fetchFigure(question_no){
            return _.findFirst(this.figure,{question_no});
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
