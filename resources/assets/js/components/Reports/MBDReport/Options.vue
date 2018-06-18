<template>
    <div>
        <div class="panel panel-default form-horizontal">
            <div class="panel-body">
                <div class="form-group">
                    <label for="" class="col-lg-2 control-label">Select Agency/Walk-in</label>
                    <div class="col-lg-5 input-group">
                        <input @click.prevent="$emit('selectMBDAgency')" type="text" class="form-control input-sm" placeholder="Click here to select MBD/Walk-in" readonly v-if="!sched.agency_cd" style="background-color:#fff;margin-left:1em;">
                        <div class="form-control input-sm" v-if="sched.agency_cd" @click.prevent="$emit('selectMBDAgency')">
                            <span v-if="sched.agency_name == 'Walk-in'">{{sched.agency_name}} - FROM {{sched.from}} TO {{sched.to}}</span>
                            <span v-if="sched.agency_name != 'Walk-in'">{{sched.agency_name}} - {{sched.donation_dt.substr(0,10)}}</span>
                        </div>
                        <div class="input-group-btn">
                            <button class="btn btn-default btn-sm" @click.prevent="$emit('selectMBDAgency')"><span class="glyphicon glyphicon-search"></span></button>
                        </div>
                    </div>
                    <mbdSelector @selected="selectedMBDAgency" :walkinDates="true"></mbdSelector>
                </div>
                <div class="form-group">
                    <label for="" class="col-lg-2 control-label">Fields</label>
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-2" v-for="(field,i) in opts" :key="i" style="font-size:12px;">
                                <span style="cursor:pointer;" @click="field.value = !field.value"><input type="checkbox" v-model="field.value"> {{field.label}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <button class="btn btn-default btn-sm" @click="$emit('fetch')" :disabled="!sched.sched_id">Generate Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props : ['sched','opts'],
    methods : {
        selectedMBDAgency(sched){
            this.$emit('selectedMBDAgency',sched);
        }
    }
}
</script>

<style scoped>
.my-card {
	background: #fff;
	position: relative;

	-webkit-box-shadow: 0px 1px 10px 0px rgba(207,207,207,1);
	-moz-box-shadow: 0px 1px 10px 0px rgba(207,207,207,1);
	box-shadow: 0px 1px 10px 0px rgba(207,207,207,1);

	-webkit-transition: all 0.5s ease;
	-moz-transition: all 0.5s ease;
	-ms-transition: all 0.5s ease;
	-o-transition: all 0.5s ease;
	transition: all 0.5s ease;	
}
</style>
