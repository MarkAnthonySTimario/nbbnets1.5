<template>
    <div>
        <div class="panel panel-success">
            <div class="panel-heading">
                BSFs with Blood Units
            </div>
            <table class="table table-bordered" style="font-size:12px;">
                <thead>
                    <tr>
                        <th @click="sortResult(0)">Facility Name <span class="glyphicon glyphicon-sort-by-alphabet"></span></th>
                        <th width="100" @click="sortResult(1)">Distance <span class="glyphicon glyphicon-sort-by-order"></span></th>
                        <th width="100">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading">
                        <td colspan="3" class="text-center">
                            <LoadingInline label="Please wait, searching.." />
                        </td>
                    </tr>
                    <ResultRow v-if="!loading && results.length > 0" 
                        v-for="r in results" 
                        :facility="r" 
                        :key="r.facility_cd"  />
                    <tr v-if="!loading && results.length == 0 && !fresh">
                        <td colspan="3" class="text-center">No Result Found</td>
                    </tr>
                    <tr v-if="!loading && results.length == 0 && fresh">
                        <td colspan="3" class="text-center">Search for Blood Units</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import ResultRow from './ResultRow.vue'
    export default{
        props : ['details','search','facilities'],
        components : {ResultRow},
        data(){
            return {
                loading : false, results : [], fresh : true
            }
        },
        methods : {
            doSearch(){
                // this.loading = true
                this.fresh = false
                this.results = _.filter(this.facilities,f => {
                    let pass = false;
                    this.details.map(d => {
                        pass = _.filter(f.bloods,{component_cd : d.component_cd*1, blood_type : d.blood_type}).length > 0
                    })
                    return pass
                })
                this.sortResult(1)
            },
            sortResult(type){
                if(type == 0){
                    this.results = _.orderBy(this.results,['facility_name'],['asc'])
                }else if(type == 1){
                    this.results = _.orderBy(this.results,r => {
                        return r.distance.distance;
                    })
                }
            }
        },
        watch : {
            search(){
                this.doSearch()
            } 
        }
    }
</script>
