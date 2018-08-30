<template>
    <div>
        <div class="row">
            <div class="col-lg-4">
                <div class="panel panel-success">
                    <div class="panel-heading">Registered Donation ID Stickers</div>
                    <table class="table table-bordered table-condensed" style="font-size:12px;">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="s in stickers" :key="s.id" v-if="!loading">
                                <td>{{s.year}}</td>
                                <td>{{s.start}}</td>
                                <td>{{s.end}}</td>
                            </tr>
                            <tr v-if="!loading && stickers.length == 0">
                                <td colspan="3" class="text-center">No Registered Stickers</td>
                            </tr>
                            <tr v-if="loading">
                                <td colspan="3" class="text-center"><LoadingInline label="Please wait.." /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return {
                stickers : [], loading : false
            }
        },
        mounted(){
            this.loadStickers()
        },
        methods : {
            loadStickers(){
                this.loading = true
                let {facility_cd} = this.$session.get('user').facility
                this.$http.get(this,'sticker/list/'+facility_cd)
                .then(({data}) => {
                    this.stickers = data
                    this.loading = false
                })
            }
        }
    }
</script>