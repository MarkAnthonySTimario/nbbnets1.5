<template>
    <div class="col-lg-12">
        <br/><h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">Blood Bag Label Template</h3><br/>
        <div class="col-lg-6">
            <div v-html="output" style="width:370px; margin-left:auto;margin-right:auto;">

            </div>
        </div>
        <div class="col-lg-6">
            <label-template @update="(raw) => {template = raw}" style="margin-top:0.5em;"></label-template>
        </div>
    </div>
</template>

<script>
    import LabelTemplate from './LabelTemplate.vue';
    export default{
        props : ['init'],
        data(){
            return {
                template : this.init
            }
        },
        components : {LabelTemplate},
        watch : {
            template(){
                this.$emit('update',this.template);
            }
        },
        computed : {
            output(){
                //   Source found in mixin
                return this.prepareTemplate(this.template);
            }
        }
    }
</script>