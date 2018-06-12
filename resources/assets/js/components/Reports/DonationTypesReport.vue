<template>
    <div>
        <table class="table table-condensed table-bordered" v-if="!loading">
            <thead>
                <tr>
                    <th></th>
                    <th v-for="(blood_type,i) in blood_types" :key="i">{{blood_type}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(code_val,codedtl_cd) in donation_types" :key="codedtl_cd">
                    <td>{{code_val}}</td>
                    <td v-for="(blood_type,i) in blood_types" :key="i">
                        {{codedtl_cd}} {{blood_type}}
                    </td>
                </tr>
            </tbody>
        </table>
        <loading v-if="loading"></loading>
    </div>
</template>

<script>
export default {
    data(){
        let {components,blood_types} = this.$session.getAll();
        return {
            loading : false, components,blood_types, donation_types : []
        }
    },
    mounted(){
        this.loading = true;
        this.$http.get(this,'keyvalues/donationtypes')
        .then(({data}) => {
            this.donation_types = data;
            this.loading = false;
        });
    }
}
</script>
