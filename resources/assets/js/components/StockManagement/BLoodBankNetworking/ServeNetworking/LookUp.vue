<template>
    <div class="modal fade" id="lookupUnits">
        <div class="modal-dialog" v-if="blood_type && component_cd">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        Select Blood Unit
                    </h5>
                </div>
                <div class="modal-body" v-if="loading">
                    <center >
                        <LoadingInline label="Please wait, this could take a while.." />
                    </center>
                </div>
                <table class="table table-condensed table-striped" v-if="!loading" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th>Donation ID</th>
                            <th>Expiration Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(u,i) in currentVisibleUnits" :key="i">
                            <td>{{u.donation_id}}</td>
                            <td>{{u.expiration_dt}}</td>
                            <td class="text-right">
                                <button class="btn btn-success btn-xs" @click="selectThisUnit(u)"><span class="glyphicon glyphicon-ok"></span></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">
                                <button class="btn btn-default btn-xs" @click="prevPage"><span class="glyphicon glyphicon-arrow-left"></span>Previous</button> 
                                <button class="btn btn-default btn-xs" @click="nextPage">Next<span class="glyphicon glyphicon-arrow-right"></span></button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props : ['blood_type','component_cd','used','index'],
        data(){
            return {
                facility_cd : this.$session.get('user').facility.facility_cd,
                units : [], orig_units : [], loading : false,
                pageSize:10,
                currentPage:1,
                
            }
        },
        mounted(){
            this.getAvailableUnits()
        },
        watch : {
            blood_type(){
                this.getAvailableUnits()
            },
            component_cd(){
                this.getAvailableUnits()
            },
        },
        computed : {
            currentVisibleUnits(){
                return _.filter(this.units,(unit,index) => {
                    let start = (this.currentPage-1)*this.pageSize;
                    let end = this.currentPage*this.pageSize;
                    return index >= start && index < end;
                })
            },
        },
        methods : {
            nextPage() {
                if((this.currentPage*this.pageSize) < this.units.length) {
                    this.currentPage++;
                }
            },
            prevPage() {
                if(this.currentPage > 1) {
                    this.currentPage--;
                }
            },
            getAvailableUnits(){
                let {blood_type,component_cd,facility_cd} = this
                this.loading = true
                this.$http.post(this,'networking/lookupunits',{
                    blood_type, component_cd, facility_cd
                })
                .then(({data})=>{
                    // var nonMatchingItems = response.filter(item => !arrayOfIDs.includes(item.id));
                    this.units = _.orderBy(data.filter(u=> !this.used.includes(u.donation_id)),['expiration_dt'],['asc'])
                    this.loading = false
                })
            },
            selectThisUnit(unit){
                this.$emit('selectUnit',{
                    unit, index : this.index
                })
            }
        }
    }
</script>