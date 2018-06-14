<template>
  <div class="modal fade" id="flush-msg" v-if="$store.state.msg">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header bg-primary">
                  <h5 class="modal-title">Select Agency
                      <button class="close" data-dismiss="modal" @click="closeAndClear"><span>&times;</span></button>
                  </h5>
              </div>
              <div class="modal-body">
                  <div class="text-center">{{$store.state.msg.content}}</div>
              </div>
              <div class="modal-footer">
                  <div class="pull-right"><button class="btn btn-sm btn-primary" data-dismiss="modal" @click="closeAndClear" id="alert-close">Close</button></div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>

export default {
  updated(){
      if(this.$store.state.msg != null){
          $("#flush-msg").modal("show").focus();
          $("#alert-close").focus();
      }
  },
  methods : {
      closeAndClear(){
          this.$store.state.msg = null;
      }
  }
}
</script>
