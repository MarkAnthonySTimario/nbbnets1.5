<template>
    <tr>
        <td v-if="item.level==0 && item.header" colspan="5" class="section">{{ item.question_no}} {{ item.question }}</td>   
        
        <td v-if="item.level==1"></td>  
        <td v-if="item.level==1 && item.header && item.question_no!='3.7.1' && item.question_no!='5.11.0' && item.question_no!='3.5.0' && item.question_no!='6.5.0'" colspan="4">
            {{ item.question_no}} {{ item.question }}</td>
        <td v-if="item.level==1 && !item.header && item.question_no!='3.7.1' && item.question_no!='5.11.0' && item.question_no!='3.5.0' && item.question_no!='6.5.0'" colspan="3">
            {{ item.question_no}} {{ item.question }}</td>
       
        
        <td v-if="item.question_no=='3.7.1' && item.header" colspan="4" style="padding:0px;">
            <sub-item-371 :year="year" :month="month"></sub-item-371>
        </td>
        <td v-if="item.question_no=='3.5.0' && item.header" colspan="4" style="padding:0px;">
            <sub-item-350 :year="year" :month="month"></sub-item-350>
        </td>                   

        <td v-if="item.level==2" colspan="2"></td>    
        <td v-if="item.level==2 && item.question_no != '5.11.0' && item.question_no !='6.5.0'" colspan="2">
            {{ item.question_no}} {{ item.question }}
        </td>

        <td v-if="item.level==2 && item.question_no=='5.11.0'" colspan="3" style="padding:0px;">
            <sub-item-511 :year="year" :month="month"></sub-item-511>
        </td> 
        <td v-if="item.level==2 && item.question_no=='6.5.0'" colspan="3" style="padding:0px;">
            <sub-item-650 :year="year" :month="month"></sub-item-650>
        </td>        

        <td v-if="item.level==3"></td>  
        <td v-if="item.level==3" colspan="2">{{ item.question_no}} {{ item.question }}</td>  
        <td v-if="item.level==3" colspan="2">{{ figure }}</td>  
        
        <!-- answers -->
        <td v-if="item.isYesNo" class="text-center">
            <span v-if="figure==1">Yes</span>
            <span v-if="figure==0">No</span>
        </td>
        <td v-else-if="!item.isYesNo && item.question_no!='3.7.1' && item.question_no!='5.11.0' && item.question_no!='3.5.0' && item.question_no!='6.5.0' && !item.header" class="text-center text-bg">
            <loadingInline v-if="loading" label=""></loadingInline>
            <span v-if="!loading">
                {{ figure }}
            </span>
        </td>
    </tr>

</template>

<script>
    let ignores = ['3.5.0', '3.7.1','5.11.0','5.4.4','6.5.0'];

    import SubItem350 from './item/SubItem3_5_0.vue';
    import SubItem371 from './item/SubItem3_7_1.vue';
    import SubItem511 from './item/SubItem5_11.vue';
    import SubItem650 from './item/SubItem6_5_0.vue';    

    export default {   
        components : {SubItem350,SubItem371,SubItem511,SubItem650},
        props : ['item','year','month'],
        data() {
            return { 
                figure: null , loading  : true
            }
        },
        mounted() {
            this.fetchInitiate();       
        },
        watch : {
            year(){
                this.fetchInitiate();
            },
            month(){
                this.fetchInitiate();
            },
            item : {
                handler(val){
                    this.figure = val.value;
                },
                deep : true
            }
        },
        methods :  {
            fetchInitiate(){
                // If item is in ignore list, ignore
                if(_.findIndex(ignores,this.item.question_no) !== -1){
                    this.loading = false;
                    this.callChildCallback(this.item);
                    return this.figure;
                } else if(this.item.isYesNo === true) {
                    this.fetchYesNoAnswer();
                } else if(this.item.isYesNo === false){
                    this.fetchData();
                } else{
                    this.callChildCallback(this.item);
                }

            },
            fetchYesNoAnswer() {
                let {year,month} = this;
                this.$http.post(this, 'bsi/yesno', {
                    facility_cd: this.$session.get('user').facility_cd,
                    question_no: this.item.question_no, year, month
                })
                .then(({data})=>{
                    this.figure = data;
                    this.loading = false;
                    this.callChildCallback(this.item);
                })
            },
            fetchData() {
                let {year,month} = this;               
                this.$http.post(this, 'bsi/item', {
                    facility_cd: this.$session.get('user').facility_cd,
                    user_id: this.$session.get('user').user_id,
                    question_no: this.item.question_no, year, month
                })
                .then(({data})=>{
                    this.figure = data;
                    this.item.value = data;
                    this.callChildCallback(this.item);
                    this.loading = false;
                })
            },
            callChildCallback(child){
                if(typeof child.callback === 'function'){
                    child.callback(this);
                }
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