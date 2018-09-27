<template>
    <div>
        <Loading v-if="loading" />
        <Verifier v-if="verify" @proceed="releaseRequest" />
        <div class="row" v-if="!loading && intent && !verify">
            <div class="col-lg-8">
                <router-link to="/ServeNetworking" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-arrow-left"></span> Back to List
                </router-link>
                <br/><br/>
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Release Reserved Units
                    </div>
                    <table class="table table-bordered table-condensed" style="font-size:12px;">
                        <tbody>
                            <tr style="font-size:20px;">
                                <td width="200">Release to</td>
                                <td colspan="5">{{intent.facility_from.facility_name}}</td>
                            </tr>
                            <tr v-for="d in notCanceled" :key="d.id">
                                <td style="vertical-align:middle;">{{components[d.component_cd]}}</td>
                                <td  style="vertical-align:middle;">{{d.blood_type}}</td>
                                <td width="100"  style="vertical-align:middle;">
                                    <span v-if="!d.forChange">{{d.donation_id}}</span>
                                    <span v-if="d.forChange" style="text-decoration: line-through;color:#c1c1c1;">{{d.donation_id}}</span>
                                </td>
                                <td style="vertical-align:middle;">
                                    <button class="btn btn-danger btn-xs" 
                                        title="Cancel Unit Reservation" 
                                        @click="d.cancel = true">
                                        <span class="glyphicon glyphicon-arrow-left"></span>
                                    </button>
                                    <button v-if="!d.forChange" 
                                        class="btn btn-success btn-xs" 
                                        @click="d.forChange = true" 
                                        title="Change Unit to Release">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                    </button>
                                    <!-- <span class="text-warning" v-if="d.forChange">Scan New Unit <span class="glyphicon glyphicon-arrow-right"></span></span> -->
                                </td>
                                <td class="align-middle"  style="vertical-align:middle;">
                                    <input placeholder="Donation ID" 
                                    type="text" 
                                    class="form-control input-sm" 
                                    maxlength="16" 
                                    @keyup="d.confirm ? d.confirm = d.confirm.toUpperCase() : null; checkDonationID(d)" 
                                    v-model="d.confirm">
                                </td>
                                <td class="align-middle"  style="vertical-align:middle;">
                                    <span class="glyphicon glyphicon-ok text-success" v-if="d.ok"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="panel-footer text-right">
                        <button class="btn btn-primary btn-sm" @click="verify = true" :disabled="hasPending">Release Blood Units</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props : ['intent_id'],
        data(){
            return {
                intent : null, loading : false,
                components : this.$session.get('components'),
                verify : false
            }
        },
        mounted(){
            this.loadIntent()
        },
        methods : {
            loadIntent(){
                this.loading  = true
                this.$http.get(this,'networking/intent/'+this.intent_id)
                .then(({data})=>{
                    data.units.map(d=>{
                        d.forChange = false
                        d.confirm = null
                        d.ok = false
                        d.cancel = false
                    })
                    this.intent = data
                    this.loading = false
                })
            },
            checkDonationID(d){
                if(!d.confirm){
                    return
                }
                if(d.confirm.length == 16){
                    if(!d.forChange){
                        d.ok = d.donation_id == d.confirm
                    }else{
                        this.$http.post(this,'networking/checkDonationId',{
                            blood_type : d.blood_type,
                            component_cd : d.component_cd,
                            confirm : d.confirm
                        })
                        .then(({data}) => {
                            d.ok = data.status
                        })

                    }
                }
            },
            releaseRequest(verifier){
                let user = this.$session.get('user')

                this.loading = true;
                this.$http.post(this,'networking/proceedrelease',{
                    verifier, user : user.username, units : this.intent.units, intent_id : this.intent.id
                })
                .then(({data}) => {
                    this.printReleaseForm(this.intent.id)
                    this.$router.push('/ServeNetworking');
                })
            }
        },
        computed : {
            notCanceled(){
                return _.filter(this.intent.units,{cancel:false})
            },
            hasPending(){
                return _.filter(this.intent.units,u=>!u.ok).length > 0
            }
        }
    }
</script>