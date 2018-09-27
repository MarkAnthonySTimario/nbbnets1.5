<template>
    <div>
        <LoadingInline label="Please wait.." v-if="loading" />
        <div class="row" v-if="!loading">
            <div class="col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Reserved Blood Request
                    </div>
                    <table class="table table-bordered table-condensed" style="font-size:14px;">
                        <thead>
                            <tr>
                                <th>Request No.</th>
                                <th>From</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="r in requests" :key="r.id">
                                <td>{{r.request_no}}</td>
                                <td>{{r.facility_from.facility_name}}</td>
                                <td>{{r.created_at ? r.created_at.substr(0,10) : null }}</td>
                                <td>
                                    <span v-if="!r.reserved_by && !r.released_by" class="text-info">For Look Up</span>
                                    <span v-if="r.reserved_by && !r.released_by" class="text-primary">Reserved</span>
                                    <span v-if="r.reserved_by && r.released_by" class="text-primary">Released</span>
                                </td>
                                <td>
                                    <a title="Print Release Form" v-if="r.released_by" :href="'releaseform/'+r.id" class="btn btn-info btn-xs" target="_blank"><span class="glyphicon glyphicon-print"></span></a>
                                    <router-link title="Release Reserved Units" v-if="r.reserved_by && !r.released_by" :to="'/ServeNetworking/release/'+r.id" class="btn btn-success btn-xs" @click="deleteRequest(r)"><span class="glyphicon glyphicon-share-alt"></span></router-link>
                                    <button title="Remove from List" class="btn btn-warning btn-xs" @click="deleteRequest(r)"><span class="glyphicon glyphicon-minus"></span></button>
                                </td>
                            </tr>
                            <tr v-if="requests.length == 0">
                                <td class="text-center" colspan="4">No Requests Yet</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props : ['update'],
        data(){
            return {
                user : this.$session.get('user'),
                requests : [],
                loading : false,
            }
        },
        mounted(){
            this.getRequests()
        },
        methods : {
            getRequests(){
                let {facility} = this.user
                this.loading = true
                this.$http.get(this,'networking/getservedintent/'+facility.facility_cd)
                .then(({data})=>{
                    this.loading = false
                    this.requests = _.orderBy(data,['request_no'],['desc'])
                })
            },
            deleteRequest(r){
                this.$http.get(this,'networking/deleterequest/'+r.id)
                .then(({data})=>{
                    this.getRequests()
                })
            }
        },
        watch : {
            update(){
                this.getRequests()
            }
        }
    }
</script>
