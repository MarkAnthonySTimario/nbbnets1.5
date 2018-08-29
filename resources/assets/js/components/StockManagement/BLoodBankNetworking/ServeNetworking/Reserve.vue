<template>
    <div>
        <LoadingInline label="Please wait, saving.." v-show="loading" />
        <div v-show="!loading">
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Reserve Blood Units</div>
                        <table class="table table-striped" style="font-size:14px;">
                            <tbody>
                                <tr v-for="(d,i) in ddetails" :key="i">
                                    <td width="150">{{d.blood_type}}</td>
                                    <td width="150">{{components[d.component_cd]}}</td>
                                    <td>
                                        <div class="input-group">
                                            <div class="form-control input-sm" >
                                                {{d.donation_id}}
                                            </div>
                                            <div class="input-group-btn">
                                                <button class="btn btn-default btn-xs" @click="showLookup(d,i)">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <button class="btn btn-primary btn-xs" @click="proceed" :disabled="hasPending">
                                            <span class="glyphicon glyphicon-arrow-right"></span>
                                            Reserve
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <Lookup 
            :blood_type="blood_type" 
            :component_cd="component_cd" 
            :index="selectIndex"
            :used="donation_ids"
            @selectUnit="unitSelected"
            v-show="blood_type && component_cd && selectIndex > -1" />
        </div>
    </div>
</template>

<script>
    import Lookup from './Lookup'
    export default{
        components : {Lookup},
        props : ['details'],
        data(){
            let ddetails = []
            this.details.map(d => {
                for(let i = 0; i< d.quantity; i++){
                    ddetails.push({
                        blood_type : d.blood_type, 
                        component_cd : d.component_cd, 
                        donation_id : null
                    })
                }
            })

            return {
                ddetails, 
                components : this.$session.get('components'),
                blood_type : null, 
                component_cd : null,
                selectIndex : null,
                loading : false
            }
        },
        computed : {
            hasPending(){
                return _.filter(this.ddetails,{donation_id : null}).length != 0
            },
            donation_ids(){
                return this.ddetails.map(d=>d.donation_id)
            }
        },
        methods : {
            showLookup(d,i){
                let {blood_type,component_cd} = d
                this.selectIndex = i
                this.blood_type = blood_type
                this.component_cd = component_cd
                $('#lookupUnits').modal('show')
            },
            unitSelected(selection){
                $('#lookupUnits').modal('hide')
                let {index,unit : {donation_id}} = selection
                this.ddetails[this.selectIndex].donation_id = donation_id
                this.selectIndex = null
                this.blood_type = null
                this.component_cd = null
            },
            proceed(){
                let units = this.ddetails
                this.loading = true
                this.post(this,'networking/reserveunits',{
                    units
                })
                .then(({data})=>{
                    this.loading = false
                    this.$emit('proceed')
                })
            }
        }
    }
</script>