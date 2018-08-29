<template>
    <div>
        <LoadingInline v-if="loading" />
        <div class="row" v-if="!loading">
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Blood Request Details
                    </div>
                    <table class="table table-condensed" style="font-size:12px;">
                        <thead>
                            <tr>
                                <th>Blood Type</th>
                                <th>Component</th>
                                <th class="text-center">Available</th>
                                <th>Qty.<br/>Requested</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(d,i) in details" :key="i">
                                <td>
                                    <select class="form-control input-sm" v-model="d.blood_type" style="width:100px;">
                                        <option v-for="(bt,i) in blood_types" :key="i" :value="bt">{{bt}}</option>
                                    </select></td>
                                <td>
                                    <select class="form-control input-sm" v-model="d.component_cd" style="width:200px;">
                                        <option v-for="(cn,cd) in components" :key="cd" :value="cd">{{cn}}</option>
                                    </select>
                                </td>
                                <td class="text-center" style="vertical-align:middle;">
                                    {{d.available}}
                                </td>
                                <td><input type="number" class="form-control input-sm" v-model="d.quantity" style="width:80px;"></td>
                                <td class="text-right">
                                    <button @click.prevent="removeDetail(i)" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right">
                                    <button @click="addDetail" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-plus"></span> Add Row</button>
                                    <button @click="proceed" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-arrow-right"></span> Reserve Units</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props : ['intent'],
        data(){
            return {
                details : JSON.parse(this.intent.details),
                components : this.$session.get('components'),
                loading : false,
                components : this.$session.get('components'),
                blood_types : this.$session.get('blood_types'),
                availables : []
            }
        },
        mounted(){
            this.getAvailable()
        },
        methods : {
            getAvailable(){
                this.loading = true
                this.$http.post(this,'networking/intentavailable',{
                    facility_cd : this.$session.get('user').facility.facility_cd
                })
                .then(({data})=>{
                    this.availables = data
                    this.details.map(d=>{
                        let available = _.findLast(data,{blood_type : d.blood_type, component_cd : d.component_cd*1})
                        d.available = available ? available.quantity : 0
                    })
                    this.loading = false
                })
            },
            addDetail(){
                this.details.push({
                    blood_type : 'A pos',
                    component_cd : 10,
                    available : null,
                    quantity : 1
                })
            },
            removeDetail(i){
                this.details.splice(i,1)
            },
            proceed(){
                this.$emit('proceed',{
                    details : this.details
                })
            }
        },
        watch : {
            details : {
                handler(){
                    this.details.map(d=>{
                        let available = _.findLast(this.availables,{blood_type : d.blood_type, component_cd : d.component_cd*1})
                        d.available = available ? available.quantity : 0
                    })
                },
                deep : true
            }
        }
    }
</script>