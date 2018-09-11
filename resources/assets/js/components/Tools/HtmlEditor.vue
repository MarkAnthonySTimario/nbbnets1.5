<template>
    <div>
        <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous"> -->
        <!-- <froala :tag="'textarea'" :config="config" v-model="raw"></froala> -->
        <textarea cols="30" rows="10" class="form-control input-sm" v-model="raw"></textarea>
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
    
    export default {
        props : ['config','init'],
        data(){
            return {
                raw : this.init
            }
        },
        watch : {
            raw(){
                this.$emit('update',this.raw);
            }
        }
    }
</script>