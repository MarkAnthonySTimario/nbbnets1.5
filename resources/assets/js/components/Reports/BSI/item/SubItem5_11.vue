<template>
    <table class="table table-condensed table-bordered" v-if="!loading">
        <thead class="text-center">
            <tr>
                <td>Component</td>
                <td colspan="7">Reason for Discard</td>
            </tr>
            <tr>
                <td></td>
                <td width="10%">TTI</td>
                <td width="10%">Processing<br>Problem</td>
                <td width="10%">Storage<br>Problem</td>
                <td width="10%">Transport<br>Problem</td>
                <td width="10%">Date Expiry</td>
                <td width="10%">Total</td>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr v-for="item in scope" :key="item.question_no">
                <td class="text-left">{{item.question_no}} {{item.label}}</td>
                <td class="text-bg">{{item.data['tti']}}</td>
                <td class="text-bg">{{item.data['pro']}}</td>
                <td class="text-bg">{{item.data['sto']}}</td>
                <td class="text-bg">{{item.data['tran']}}</td>
                <td class="text-bg">{{item.data['exp']}}</td>  
                <td class="text-bg">{{item.data['total']}}</td>  
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props : ['year','month'],
    data(){
        let scope = [
            {question_no : '5.11.1', label : 'Whole Blood', data : {}},
            {question_no : '5.11.2', label : 'Red Cells', data : {}},
            {question_no : '5.11.3', label : 'Platelets', data : {}},
            {question_no : '5.11.4', label : 'Plasma', data : {}},
            {question_no : '5.11.5', label : 'Fresh frozen plasma', data : {}},
            {question_no : '5.11.6', label : 'Cryoprecipitate', data : {}},
            {question_no : '5.11.7', label : 'Total component discarded', data : {}},
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