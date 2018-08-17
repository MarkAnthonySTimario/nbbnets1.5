<template>   
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">                    
            <div class="row">
                <div class="col-lg-12">  
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Update BSI Report</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                <table class="table table-hover table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%"></th>            
                                            <th class="text-center" ></th>
                                            <th class="text-center" width="50%"></th>
                                            <th class="text-center"  width="5%"></th>
                                            <th class="text-center"  width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>                                
                                        <item v-for="(item,i) in YesNoFromQuestions" 
                                            :key="i" 
                                            :item="item"></item>
                                    </tbody>
                                </table>
                                    <div class="form-group">
                                        <div class="col-lg-8 col-lg-offset-4">
                                            <button class="btn btn-success btn-sm" @click="create">Update BSI Report</button>
                                        </div>
                                    </div> 
                                    <br><br><br>                         
                                </div>
                            </div>                
                        </div>
                    </div>                 
                </div>
            </div>                
        </div>
    </div>
</template>

<script>
    import Questions from './Questions';
    import item from './CreateItem';
    export default {
        components: { item },
        data(){
            let items = Questions.Questions;
            items.map((question) => {
                question.value = 0;
            });
            return {
                Questions : items
            }
        },
        methods :  {
            create() {               
                let {YesNoFromQuestions} = this;
                this.$http.post(this,'bsi/create', {
                    user_id: this.$session.get('user').user_id,
                    facility_cd: this.$session.get('user').facility_cd,
                    items : YesNoFromQuestions
                })
                .then(({data}) => {
                    this.$router.replace('/BSI/');
                    this.loading = false;
                })
            }         

        },
        computed : {
            YesNoFromQuestions(){
                return this.Questions;
                // return _.filter(this.Questions,{isYesNo : true});
            }
        }

    }
</script>

<style>
body {
    font-size: 12px;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
}
</style>