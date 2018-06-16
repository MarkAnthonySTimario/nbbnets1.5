<template>
    <div>
        <loading v-if="page_loading"></loading>
        <div class="row" v-if="!page_loading && no_unit">
            <div class="col-lg-4 col-lg-offset-4">
                <div class="panel panel-warning">
                    <div class="panel-heading">Warning!</div>
                    <div class="panel-body">
                        <small>
                            System has detected that there are no blood unit in the facility inventory
                             to preview a blood bag label, do you want system to create a dummy unit?
                        </small>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-default btn-sm pull-right" @click="createDummy">Okay</button>
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="!page_loading && !no_unit">
            <div class="col-lg-6">
                <html-editor :config="config" :init="raw" @update="updateRaw"></html-editor>
                <hr/>
                <button class="btn btn-default btn-sm" @click="saveChanges">Apply Changes</button>
            </div>
            <div class="col-lg-6">
                <loadingInline v-if="loading" label="Refreshing"></loadingInline>
                <div v-html="html" v-if="!loading" style="width:375px;margin-left:auto;margin-right:auto;">

                </div>
            </div>
        </div>
    </div>
</template>

<script>

    

    export default{
        data(){
            let {facility_cd} = this.$session.get('user');
            return {
                config: {
                    width : 425
                }, raw : null,
                no_unit : true, page_loading : true, loading : true, html : null, facility_cd
            }
        },
        mounted(){
            this.checkForUnit();
            this.$http.get(this,'labeltemplate/gettemplate/'+this.facility_cd)
            .then(({data}) => {
                this.raw = data;
            })
            // this.refreshHTML();
        },
        methods : {
            updateRaw(raw){
                this.raw = raw;
            },
            saveChanges(){
                this.loading = true;
                let { facility_cd , raw } = this; 
                this.$http.post(this,'labeltemplate/savefacilitytemplate',{
                    facility_cd , template : raw
                })
                .then(({data}) => {
                    this.refreshHTML();
                })
            },
            checkForUnit(){
                
                this.$http.post(this,'labeltemplate/checkunit',{
                    facility_cd : this.facility_cd
                })
                .then(({data}) => {
                    this.page_loading = false;
                    if(data){
                        this.no_unit = false;
                        this.refreshHTML();
                    }else{
                        this.no_unit = true;
                    }
                });
            },
            createDummy(){
                this.page_loading = true;
                this.$http.post(this,'labeltemplate/createdummy',{
                    facility_cd : this.facility_cd
                })
                .then(({data}) => {
                    this.page_loading = false;
                    this.no_unit = false;
                    this.refreshHTML();
                });
            },
            refreshHTML(){
                this.loading = true;
                let {facility_cd} = this;
                let url = 'http://'+window.location.hostname+window.location.pathname+'labelpreview?facility_cd='+facility_cd;
                let that = this;

                axios.get(url)
                .then(({data}) => {
                    that.loading = false;
                    that.html = data;
                });

            }
        }
    }
</script>