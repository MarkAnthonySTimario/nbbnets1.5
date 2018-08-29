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
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="r in requests" :key="r.id">
                                <td>{{r.request_no}}</td>
                                <td>{{r.facility_from.facility_name}}</td>
                                <td>
                                    <span v-if="!r.reserved_by && !r.released_by" class="text-info">For Look Up</span>
                                    <span v-if="r.reserved_by && !r.released_by" class="text-primary">Reserved</span>
                                    <span v-if="r.reserved_by && r.released_by" class="text-primary">Released</span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-xs" @click="deleteRequest(r)"><span class="glyphicon glyphicon-minus"></span></button>
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
                    this.requests = data
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
