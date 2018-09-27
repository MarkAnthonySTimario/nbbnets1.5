<template>
    <div>
        <div v-if="!loading && facility">
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-default btn-sm" @click="$emit('focusOut',true)"><span class="glyphicon glyphicon-arrow-left"></span> Back</button>
                </div>

            </div>
            <br/>
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="text-danger" style="font-size:20px;">{{facility.facility_name}}</h1><br/>
                    <p class="text-info">{{facility.distance.time}} | {{facility.distance.distance}} km</p>
                </div>
            </div>
            <br/>
            <div class="row" v-if="!show">
                <div class="col-lg-6">
                    <div style="font-size:12px;" class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> By pressing the button, you are sending an intent to request blood units to this facility.<br/>The Facility will not be informed yet, but will have your request details ready for processing.<br/>
                        You may call the Facility to inform about your request later, if you would like to proceed.<br/>
                        <br/>
                        <button class="btn btn-default btn-sm" @click="showDetails">Show Contact Details</button>
                    </div>
                </div>
            </div>
            <div class="row" v-if="show">
                <div class="col-lg-6">
                    <table class="table table-bordered table-stripped">
                        <tbody>
                            <tr>
                                <td width="200">Contact Person</td><td>{{facility.contact_name}}</td>
                            </tr>
                            <tr>
                                <td>Telephone No.</td><td>{{facility.tel_no}}</td>
                            </tr>
                            <tr>
                                <td>Mobile No.</td><td>{{facility.mobile_no}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table table-bordered table-striped" style="font-size:12px;">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th>Blood Type</th>
                                <th>Available Blood</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(b,i) in facility.bloods" :key="i">
                                <td>{{components[b.component_cd]}}</td>
                                <td>{{b.blood_type}}</td>
                                <td>{{b.quantity}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Loading v-if="loading" />
    </div>
</template>

<script>
export default {
    props : ['facilitycd','details'],
    data(){
        let components = this.$session.get('components')
        return {
            facility : false, loading : false, show : false, components
        }
    },
    mounted(){
        this.loading = true
        let {facility} = this.$session.get('user')
        this.$http.post(this,'networking/facility',{
            facility_cd : this.facilitycd,
            origin : facility
        })
        .then(({data}) => {
            this.facility = data
            this.loading = false
        })
    },
    methods : {
        showDetails(){
            this.show = true
            let user = this.$session.get('user')
            let {facility} = user
            
            this.$http.post(this,'networking/sendintent',{
                from : facility.facility_cd,
                to : this.facilitycd,
                by : user.username,
                details : this.details
            }).then(({data})=>console.log(data))
        }
    }
}
</script>
