<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">Manage Facilities</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <router-link to="/ManageFacilities/RegisterFacility" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Register New Facility</router-link>
                            </div>
                            <div class="col-lg-2">
                                <input type="text" v-model="search" placeholder="Search Facility Name / Code" class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                    <table class="table table-condensed table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Facility Code</th>
                                <th>Name of Facility</th>
                                <th>Region</th>
                                <th>Province</th>
                                <th>City</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!loading && untouched">
                                <td colspan="6" class="text-center">Search for Facility Name or Facility Code</td>
                            </tr>
                            <tr v-if="loading">
                                <td colspan="6" class="text-center"><loadingInline label="Search Facility Record"></loadingInline></td>
                            </tr>
                            <tr v-if="!loading && search" v-for="row in result" :key="row.facility_cd" style="font-size:12px;">
                                <td>{{row.facility_cd}}</td>
                                <td>{{row.facility_name}}</td>
                                <td><span v-if="row.region">{{row.region.regname}}</span></td>
                                <td><span v-if="row.province">{{row.province.provname}}</span></td>
                                <td><span v-if="row.city">{{row.city.cityname}}</span></td>
                                <td>
                                    <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-search"></span></button>
                                </td>
                            </tr>
                            <tr v-if="!loading && !untouched && !result.length">
                                <td colspan="6" class="text-center">No Facility with that Name or Code</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                untouched : true, search : this.$store.state.facilities_search, loading : false, result : []
            }
        },
        mounted(){
            if(this.search){
                this.performSearch(this);
            }
        },
        watch : {
            search(){
                this.$store.state.facilities_search = this.search;
                this.doSearch(this);
            }
        },
        methods : {
            doSearch : _.debounce((that) => {
                that.performSearch();
            },1500),
            performSearch(){
                this.untouched = false;
                this.loading = true;
                this.$http.post(this,'facility/search',{
                    search : this.search
                })
                .then(({data}) => {
                    this.loading = false;
                    this.result = data;
                })
            }
        }
    }
</script>