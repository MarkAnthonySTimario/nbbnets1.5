<template>
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    Reprint Blood Bag Label
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" maxlength="16" placeholder="Donation ID" v-model="donation_id">
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-default" @click="checkDonationID">Print!</button>
                        </div>
                    </div>
                    <br/>
                    <textarea class="form-control" v-model="reason" placeholder="Reason for Label Reprint" resize="false"></textarea>
                    <br/>
                    <p class="alert alert-danger" v-if="invalid_donation_id">
                        {{invalid_donation_id}}
                    </p>
                </div>
            </div>
        </div>

        <div class="modal fade" id="bloodUnits">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title">Blood Units
                            <button class="close" data-dismiss="modal"><span>&times;</span></button>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" v-if="result">
                            <tbody>
                                <tr>
                                    <td>Blood Typing</td>
                                    <td>{{result.type.blood_type}}</td>
                                </tr>
                                <tr>
                                    <td>Blood Testing</td>
                                    <td>{{result.test.result}}</td>
                                </tr>
                                <tr v-for="u in result.units" :key="u.component_cd">
                                    <td>{{u.component.comp_name}}</td>
                                    <td>
                                        <span v-if="result.type && result.test">
                                            <a href="#" @click="reprint(u)" v-if="result.type && result.test.result == 'N'">Reprint</a>

                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer text-right">
                        <button class="btn btn-default btn-sm" @click="reprintDone">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return {
                donation_id : '', reason : null, result : null, loading : false, invalid_donation_id : false
            }
        },

        methods : {
            flagInvalidDonationID(msg){
                this.invalid_donation_id = msg;
                window.setTimeout(()=>{
                    this.invalid_donation_id = false;
                    this.donation_id = ''
                    this.reason = ''
                },3000)
            },
            checkDonationID(){
                if(!this.reason){
                    this.flagInvalidDonationID('Please provide reason for Label Reprint!');
                    return
                }
                if(!this.donation_id || this.donation_id.length < 16){
                    this.flagInvalidDonationID('Invalid Donation ID Format!')
                    return
                }
                let that = this;
                this.checkOwnedDonationID(this,this.donation_id,status=>{
                    if(!status){
                        that.flagInvalidDonationID('Donation ID not registered for use in your facility')
                        return
                    }else{
                        this.loading = true;
                        $("#bloodUnits").modal('show')
                        this.fetchBloodUnits()
                    }
                })
            },
            fetchBloodUnits(){
                this.$http.get(this,'LabelReprint/Units/'+this.donation_id)
                .then(({data}) => {
                    this.result = data
                })
            },
            reprint(unit){
                this.$http.post(this,'LabelReprint/ReprintFired',{
                    unit
                })
                const {facility_cd} = this.$session.get('user')
                this.printBloodBagLabel(facility_cd,unit.donation_id,unit.component_cd);
            },
            reprintDone(){
                this.donation_id = null
                this.result = null
                $('#bloodUnits').modal('hide')
            }
        },

        watch : {
            donation_id(){
                if(this.donation_id){
                    this.donation_id = this.donation_id.toUpperCase()
                }
            }
        }
    }
</script>
<style>

textarea{
    resize:none;
}
</style>
