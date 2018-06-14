<template>
  <div class="modal" tabindex="-1" role="dialog" id="preview">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <b class="modal-title">Blood Label Preview</b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <loadingInline label="Pleae wait.." v-if="loading"></loadingInline>
        <div v-if="!loading" >
            <div v-html="html" style="width:375px; height:250px; margin-left:auto; margin-right:auto;">
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>

export default {
  props : ['preview'],
  data(){
      let {facility_cd} = this.$session.get('user');
      return {
          loading : true, html : null, facility_cd
      }
  },
  mounted(){

    $("#preview").modal("show");
    let {facility_cd,preview : {donation_id,component_cd}} = this;
    let url =  'http://'+window.location.host+window.location.pathname+'label?facility_cd='+facility_cd+'&donation_id='+donation_id+'&component_cd='+component_cd;
    let that = this;
    axios.get(url)
    .then(({data}) => {
        that.html = data;
        that.loading = false;
    })
    let w = window.open(url,'winname','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=375,height=270');
    w.onload = () => {
            w.print();
        w.close();
    };
  }
}
</script>