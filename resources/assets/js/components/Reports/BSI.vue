<template>
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="row" v-if="exist == 0">
                <div class="col-lg-6 col-lg-offset-3">
                    <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span> Kindly update all Yes or No Questions 
                        <router-link to="/BSI/Create" class="pull-right btn btn-xs btn-default">
                            <span class="glyphicon glyphicon-arrow-left"></span> Update</router-link>                                     
                    </div>
                </div>
            </div>            
            <div class="row" v-if="exist != 0">
                <div class="col-lg-6 col-lg-offset-3">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">BSI Report</h3>
                        </div>
                        <div class="panel-body text-center">                     
                            <form class="form-inline" id="dates">
                                <div class="form-group">
                                    <label for="from">Year : </label>
                                    <select v-model="year" class="form-control input-sm">
                                        <option v-for="year in year_options" :key="year" :value="year">{{year}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="to">Month :</label>
                                    <select v-model="month" class="form-control input-sm">
                                        <option v-for="month in month_options" :key="month" :value="month">{{month}}</option>
                                    </select>
                                </div>
                            </form>  
                        </div>
                        <div class="panel-footer text-center">
                            <button class="btn btn-default btn-xs" @click="item_year = year; item_month = month;">Display BSI Report</button>
                            <router-link to="/BSI/Create" class="btn btn-xs btn-success" v-if="this.$session.get('user').ulevel == 1">Update BSI Report</router-link>  
                            <br>   
                        </div>
                    </div>                     
                </div>
            </div>                     
            <div class="row">
                <div class="col-lg-12">                   
                    <div class="row" v-if="year && month">
                        <div class="col-lg-12">
                            <table class="table table-hover table-condensed table-bordered">
                                    <tr>
                                        <th class="text-center" width="5%"></th>            
                                        <th class="text-center" width="5%"></th>
                                        <th class="text-center" width="30%"></th>
                                        <th class="text-center" width="50%"></th>
                                        <th class="text-center" width="10%"></th>
                                    </tr>
                                <tbody v-if="item_year && item_month">
                                    <item v-for="(item,i) in items" 
                                        :key="i" 
                                        :item="item" 
                                        :year="item_year" 
                                        :month="item_month"
                                        @next="next"></item>
                                </tbody>
                            </table>
                        </div>
                    </div>                
                </div>
            </div>                
        </div>
    </div>
</template>

<script>

    import QuestionModule from './BSI/Questions';
    import item from './BSI/Item';
    export default {
        components: { item },
        data(){
            QuestionModule.Questions.forEach((item) => {
                item.callback = function(child){
                    child.$emit('next',1);
                }
            });

            return {
                exist : null,
                // items : this.sampleFilter(), 
                items : QuestionModule.Questions.splice(0,1),
                year : 2016, 
                month : '02',
                item_year : null,
                item_month : null, 
                year_options : this.getYearOptions(), 
                month_options : this.getMonthOptions()
            }
        },
        mounted() {
            this.$http.post(this, 'bsi/exist', {
                facility_cd: this.$session.get('user').facility_cd,
            })
            .then(({data})=>{
                this.exist = data;
                // console.log(data);
            })
        },

        methods : {
            getYearOptions(){
                let year_options = [];
                let cur_year = (new Date()).getFullYear();
                for(let i = cur_year - 3; i <= cur_year; i++){
                    year_options.push(i);
                }
                return year_options.reverse();
            },

            getMonthOptions(){
                let month_options = [];
                for(let m = 1; m <= 12; m++) {
                    m = (m < 10) ? '0' + m.toString() : m.toString();
                    month_options.push(m);
                }
                return month_options;
            },

            sampleFilter(){
                return _.filter(QuestionModule.Questions,(item) => {
                    let {question_no} = item;
                    if(question_no == '2.4.1' || question_no == '2.4.2' || question_no == '2.4.3') {
                        return item;    
                    }
                })
            },

            next(){
                this.items = this.items.concat(QuestionModule.Questions.splice(0,1));
            },
        }

    }
</script>

<style scoped>
body {
    font-size: 12px;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
}

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