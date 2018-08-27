<template>
    <div class="panel panel-warning">
        <div class="panel-heading">
            Look for Blood Units
            <span class="pull-right">
                <button class="btn btn-default btn-xs" @click="addDetail"><span class="glyphicon glyphicon-plus"></span> Add Detail</button>
            </span>
        </div>
        
        <table class="table table-bordered">
            <tbody>
                <tr v-for="(d,i) in details" :key="i">
                    <td width="150">
                        <select class="form-control input-sm" v-model="d.blood_type">
                            <option v-for="(bt,i) in blood_types" :key="i" :value="bt">{{bt}}</option>
                        </select>
                    </td>
                    <td width="150">
                        <select class="form-control input-sm" v-model="d.component_cd">
                            <option v-for="(cn,cd) in components" :key="cd" :value="cd">{{cn}}</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control input-sm" v-model="d.quantity"></td>
                </tr>
            </tbody>
        </table>

        <div class="panel-footer">
            &nbsp;
            <span class="pull-right">
                <button class="btn btn-default btn-xs" @click="clear"><span class="glyphicon glyphicon-remove"></span> Clear</button>
                <button class="btn btn-default btn-xs" @click="applyDetails"><span class="glyphicon glyphicon-search"></span> Search Units</button>
            </span>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            let components = this.$session.get('components')
            let blood_types = this.$session.get('blood_types')
            return {
                details : [], components, blood_types
            }
        },
        mounted(){
            this.addDetail()
        },
        methods : {
            clear(){
                this.details = []
                this.addDetail()
            },
            addDetail(){
                this.details.push({
                    blood_type : 'A pos', component_cd : 10, quantity : 0
                })
            },
            applyDetails(){
                this.$emit('apply',this.details)
            }
        }
    }
</script>