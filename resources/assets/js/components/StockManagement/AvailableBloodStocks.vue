<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Available Blood Stocks
                    </div>
                    <div class="panel-body">
                        <br/>
                        <div class="row form-horizontal">
                            <label for="" class="control-label col-lg-2 col-lg-offset-8" style="margin-top:-0.5em;">Search Donation ID</label>
                            <div class="col-lg-2">
                                <input type="text" class="form-control input-sm" v-model="search" @keyup="search = search.toUpperCase()">
                            </div>
                        </div>
                        <br/>
                    </div>
                    <table class="table table-condensed table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Blood Type</th>
                                <th class="text-center">Component</th>
                                <th class="text-center">Donation ID</th>
                                <th class="text-center">Volume</th>
                                <th class="text-center">Date Collected</th>
                                <th class="text-center">Expiration Date</th>
                                <th class="text-center">Days Old</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="loading">
                                <td colspan="8" class="text-center">
                                    <loadingInline label="Please wait, loading.."></loadingInline>
                                </td>
                            </tr>
                            <tr v-if="!result.length && !loading">
                                <td colspan="8" class="text-center">No Available Blood Units Yet</td>
                            </tr>
                            <tr v-if="result.length && !loading" v-for="(unit,i) in currentVisibleUnits" :key="i" class="text-center">
                                <td>{{unit.blood_type}}</td>
                                <td>{{unit.component_min.comp_name | abbrev}}</td>
                                <td>{{unit.donation_id.toUpperCase()}}</td>
                                <td>{{unit.component_vol}} ml</td>
                                <td>{{getCollectedDate(unit) ? getCollectedDate(unit).substr(0,10) : null}}</td>
                                <td>{{unit.expiration_dt ? unit.expiration_dt.substr(0,10) : null}}</td>
                                <td>{{getDaysOld(unit)}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <div class="col-lg-6">
                                        <span class="text-info">Total : {{filteredResult.length}}</span>
                                    </div>
                                    <p class="pull-right">
                                        <button class="btn btn-default btn-sm" @click="prevPage">Previous</button> 
                                        <button class="btn btn-default btn-sm" @click="nextPage" :disabled="!((currentPage*pageSize) < result.length)">Next</button>
                                    </p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>                        
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return {
                loading : true, result : [], search : null,
                pageSize:20, currentPage:1,
            }
        },
        mounted(){
            let {facility_cd} = this.$session.get('user');
            this.$http.post(this,'available/list',{
                facility_cd
            })
            .then(({data}) => {
                this.result = data;
                this.loading = false;
            });
        },
        methods : {
            // paging
            nextPage() {
                if((this.currentPage*this.pageSize) < this.result.length) {
                    this.currentPage++;
                }
            },
            prevPage() {
                if(this.currentPage > 1) {
                    this.currentPage--;
                }
            },
            getCollectedDate(unit){
                if(!unit.donation_min){
                    return unit.created_dt;
                }
                if(unit.donation_min.sched_id == 'Walk-in'){
                    return unit.donation_min.created_dt;
                }else{
                    return unit.donation_min.mbd_min.donation_dt;
                }
            },
            getDaysOld(unit){
                let collected_dt = this.getCollectedDate(unit);
                if(typeof collected_dt == 'undefined'){
                    return 0
                }
                // let cd = collected_dt.substr(0,10).split('-');
                
                let oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
                let firstDate = new Date(collected_dt.substr(0,10));
                firstDate = new Date(firstDate.getFullYear(),firstDate.getMonth(),firstDate.getDate());
                let secondDate = new Date();
                secondDate = new Date(secondDate.getFullYear(),secondDate.getMonth(),secondDate.getDate());
                // return secondDate;
                // return Math.abs(firstDate.getTime()-secondDate.getTime())/oneDay;
                let diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
                return diffDays;
            }
        },
        computed : {
            filteredResult(){
                if(!this.search){
                    return this.result;
                }
                return _.filter(this.result,(unit) => {
                    if(unit.donation_id.toUpperCase().indexOf(this.search.toUpperCase())  > -1){
                        return unit;
                    }
                });
            },
            currentVisibleUnits(){
                return _.filter(this.filteredResult,(unit,index) => {
                    let start = (this.currentPage-1)*this.pageSize;
                    let end = this.currentPage*this.pageSize;
                    return index >= start && index < end;
                })
            },
        }
    }
</script>

<style scoped>
tr{
    font-size: 12px;
}
</style>
