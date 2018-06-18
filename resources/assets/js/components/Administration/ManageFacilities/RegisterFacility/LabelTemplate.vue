<template>
    <div>
        <html-editor v-if="!loading" :config="{width:420}" :init="template" @update="(raw) => {template = raw}"></html-editor>
        <loadingInline v-if="loading" label="Please wait.."></loadingInline>
    </div>
</template>

<script>
export default {
    data(){
        return {
            template : null, loading : true
        }
    },
    mounted(){
    this.$http.get(this,"labeltemplate/gettemplate/00000")
      .then(({data}) => {
          this.template = data;
          this.loading = false;
      })
    },
    watch : {
        template(){
            this.$emit('update',this.template);
        }
    }
}
</script>
