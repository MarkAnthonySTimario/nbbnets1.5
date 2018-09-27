<template>
    <div>
        <mbdSelector @selected="selectedMBDAgency" :walkinDates="true"></mbdSelector>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Printable Forms
                    </div>
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="" class="control-label col-lg-3">MBD / Walk-in</label>
                                <div class="col-lg-7 input-group">
                                    <input @click.prevent="selectMBDAgency" type="text" class="form-control input-sm" placeholder="Click here to select MBD/Walk-in" readonly v-if="!sched.agency_cd" style="background-color:#fff;">
                                    <div class="form-control input-sm" v-if="sched.agency_cd" @click.prevent="selectMBDAgency">
                                        <span v-if="sched.agency_cd == 'Shared'">{{sched.agency_name}}</span>
                                        <span v-if="sched.agency_name == 'Walk-in'">{{sched.agency_name}} - FROM {{sched.from}} TO {{sched.to}}</span>
                                        <span v-if="sched.agency_name != 'Walk-in' && sched.agency_cd != 'Shared'">{{sched.agency_name}} - {{sched.donation_dt.substr(0,10)}}</span>
                                    </div>
                                    <div class="input-group-btn">
                                        <button class="btn btn-default btn-sm" @click.prevent="selectMBDAgency"><span class="glyphicon glyphicon-search"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-center">
                        <div class="text-warning" v-if="!sched.sched_id"><span class="glyphicon glyphicon-exclamation-sign"></span> Please select MBD / Agency First<br/></div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="btn-group-vertical">
                                    <a :disabled="!sched.sched_id" target="_blank" class="btn btn-info btn-sm" :href="sched.sched_id ? 'form/release/'+sched.sched_id : void(0)">Blood Typing</a>
                                    <a :disabled="!sched.sched_id" target="_blank" class="btn btn-info btn-sm" :href="sched.sched_id ? 'form/release/'+sched.sched_id : void(0)">Blood Testing</a>
                                    <a :disabled="!sched.sched_id" target="_blank" class="btn btn-info btn-sm" :href="sched.sched_id ? 'form/release/'+sched.sched_id : void(0)">Blood Processing</a>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="btn-group-vertical">
                                    <a :disabled="!sched.sched_id" target="_blank" class="btn btn-info btn-sm" :href="sched.sched_id ? 'form/release/'+sched.sched_id : void(0)">Blood Labeling</a>
                                    <a :disabled="!sched.sched_id" target="_blank" class="btn btn-info btn-sm" :href="sched.sched_id ? 'form/release/'+sched.sched_id : void(0)">Blood Discard</a>
                                    <a :disabled="!sched.sched_id" target="_blank" class="btn btn-info btn-sm" :href="sched.sched_id ? 'form/release/'+sched.sched_id : void(0)">For Confirmatory Reactive Units</a>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="btn-group-vertical">
                                    <a :disabled="!sched.sched_id" target="_blank" class="btn btn-info btn-sm" :href="sched.sched_id ? 'form/release/'+sched.sched_id : void(0)">Release to Inventory</a>
                                    <a :disabled="!sched.sched_id" target="_blank" class="btn btn-info btn-sm" :href="sched.sched_id ? 'form/release/'+sched.sched_id : void(0)">Blood Releasing</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        let {sched} = this.$store.state;
        return {
            sched, selected : [], donations : [], loading : false
        };
    },
    methods : {
        selectMBDAgency(){
            $("#MBDSelector").modal("show");
        },
        selectedMBDAgency(sched){
            this.sched = sched;
            this.$store.state.sched = sched;
            $("#MBDSelector").modal("hide");
            this.$emit('setSchedId',sched.sched_id);
        },
    }
}
</script>
