<template>
    <div>
        <div class="row">
            <div class="col-lg-5">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        Blood Unit
                        <router-link to="/AvailableStocks" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-arrow-left"></span> Back to List</router-link>
                    </div>
                    <div class="panel-footer text-center">
                        <iframe id="preview" :src="uri" width="400" height="270">

                        </iframe>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Aliqouted Units
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-lg-12">
                                <LoadingInline label="Please wait, fetching aliqouted units.." v-if="loading" />
                                <table class="table table-bordered table-condensed" v-if="!loading">
                                    <thead>
                                        <tr>
                                            <th>Volume</th>
                                            <th>Aliqouted By</th>
                                            <th>Date Created</th>
                                            <th>Status</th>
                                            <td width="50"></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(a,i) in aliqoutes" :key="i">
                                            <td>{{a.component_vol}}ml</td>
                                            <td>{{a.aliqoute_by}}</td>
                                            <td>{{a.aliqoute_dt.substr(0,10)}}</td>
                                            <td>
                                                <span v-if="a.comp_stat == 'AVA'" class="text-success">Available</span>
                                                <span v-if="a.comp_stat == 'EXP'" class="text-danger">Expired</span>
                                                <span v-if="a.comp_stat == 'ISS'" class="text-warning">Issued</span>
                                                <span v-if="a.comp_stat == 'DIS'" class="text-danger">Discarded</span>
                                            </td>
                                            <td>
                                                <button @click="printBloodBagLabel(facility_cd,a.donation_id,component_cd)" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-print"></span></button>
                                            </td>
                                        </tr>
                                        <tr v-if="aliqoutes.length == 0">
                                            <td class="text-center" colspan="5">No Aliqouted Units</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-left" colspan="5">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" placeholder="Aliqoute Volume" v-model="new_aliqoute_volume" @keyup="validateVolume" maxlength="3">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-default" :disabled="!new_aliqoute_volume || new_aliqoute_volume == 0" @click="addAliqoute">New Aliqoute</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
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
    props : ['donation_id','component_cd'],
    data(){
        let facility_cd = this.$session.get('user').facility_cd
        let {donation_id,component_cd} = this
            
        return {
            uri : 'label?facility_cd='+facility_cd+'&donation_id='+donation_id+"&component_cd="+component_cd,
            loading : false, aliqoutes : [],
            unit : null, new_aliqoute_volume : null, facility_cd
        }
    },
    mounted(){
        this.getUnitDetails()
        this.getAliqoutes()
    },
    methods : {
        getAliqoutes(){
            this.loading = true
            this.$http.get(this,'aliqoutes/'+this.donation_id+"/"+this.component_cd)
            .then(({data})=>{
                this.aliqoutes = data
                this.loading = false
            })
        },
        getUnitDetails(){
            this.loading = true
            this.$http.get(this,'unit/'+this.donation_id+"/"+this.component_cd)
            .then(({data})=>{
                this.unit = data
                this.loading = false
            })
        },
        validateVolume(){
            let {new_aliqoute_volume,unit} = this
            if(new_aliqoute_volume && unit){
                if(new_aliqoute_volume > unit.component_vol*1){
                    this.new_aliqoute_volume = unit.component_vol*1
                }
            }
        },
        addAliqoute(){
            this.loading = true
            let user = this.$session.get('user')
            let {user_id,facility_cd} = user
            let {donation_id,component_cd,new_aliqoute_volume} = this
            this.$http.post(this,'aliqoute/make',{
                user_id,facility_cd,donation_id,component_cd,new_aliqoute_volume
            })
            .then(({data}) => {
                document.getElementById('preview').src += ''
                this.loading = false
                this.getAliqoutes()
                this.new_aliqoute_volume = null
            })
        }
    }
}
</script>
