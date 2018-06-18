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
            <div class="col-lg-5 col-lg-offset-1" style="padding-top:0.5em;">
                <html-editor :config="config" :init="raw" @update="updateRaw" v-if="raw"></html-editor>
                <hr/>
                <button class="btn btn-default btn-sm" @click="saveChanges">Apply Changes</button>
            </div>
            <div class="col-lg-6">
                <loadingInline v-if="loading" label="Refreshing"></loadingInline>
                <div v-html="html" v-if="!loading" style="width:375px;">

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
                no_unit : false, page_loading : false, loading : true, facility_cd
            }
        },
        mounted(){
            this.refreshHTML();
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
            refreshHTML(){
                this.loading = true;
                let {facility_cd} = this;

                this.$http.get(this,"labeltemplate/gettemplate/"+facility_cd)
                .then(({data}) => {
                    this.raw = data;
                    this.loading = false;
                });
            }
        },
        computed : {
            html : {
                set(){
                    this.html = this.prepareTemplate(this.raw);
                },
                get(){
                    return this.prepareTemplate(this.raw);
                }
            }
        }
    }
</script>