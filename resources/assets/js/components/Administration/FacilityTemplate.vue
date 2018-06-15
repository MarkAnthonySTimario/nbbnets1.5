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
                <div class="panel panel-success">
                    <div class="panel-heading">Blood Bag template</div>
                    <div class="panel-body">
                        <froala :tag="'textarea'" :config="config" v-model="raw"></froala>
                    </div>
                    <div class="panel-footer">
                        <button class="btn btn-default btn-sm" @click="saveChanges">Save Changes</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <loadingInline v-if="loading" label="Refreshing"></loadingInline>
                <div v-html="html" v-if="!loading" style="width:375px;margin-left:auto;margin-right:auto;">

                </div>
            </div>
        </div>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    </div>
</template>

<script>

    // Require Froala Editor js file.
    require('froala-editor/js/froala_editor.pkgd.min')

    // Require Froala Editor css files.
    require('froala-editor/css/froala_editor.pkgd.min.css')
    // require('font-awesome/css/font-awesome.css')
    require('froala-editor/css/froala_style.min.css')

    import VueFroala from 'vue-froala-wysiwyg';

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