<template>
    <table class="table table-condensed table-bordered" v-if="!loading">
        <thead class="text-center">
            <tr>
                <td>TTI Markers</td>
                <td width="20%" colspan="2">Screening test reactive</td>
                <td width="20%" colspan="2">Transport<br>Problem</td>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr v-for="item in scope" :key="item.question_no">
                <td class="text-left">{{item.question_no}} {{item.label}}</td>
                <td class="text-bg" width="10%">{{item.data}}</td>
                <td width="10%"></td>
                <td width="10%"></td>
                <td width="10%"></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props : ['year','month'],
    data(){
        let scope = [
            {question_no : '3.5.1', label : 'HIV', data : {}},
            {question_no : '3.5.2', label : 'Hepatitis B', data : {}},
            {question_no : '3.5.3', label : 'Hepatitis C', data : {}},
            {question_no : '3.5.4', label : 'Syphilis', data : {}},
            {question_no : '3.5.5', label : 'Malaria', data : {}},
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