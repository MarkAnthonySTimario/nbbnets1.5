<template>
    <div class="row">
        <div :class="full ? 'col-lg-12' : 'col-lg-6 col-lg-offset-3'" style="margin-top:50px;">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Confirmatory Verification Check</h3>
                </div>
                <div class="panel-body">
                    <form action="" class="form-horizontal">
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-4">
                                <i class="text-success" style="font-size:12px;">Please call a Verifier to enter his/her Access Details</i>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="" class="control-label col-lg-4">User Name</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control input-sm" id="user_id" placeholder="User Name" v-model="username">
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="" class="control-label col-lg-4">Password</label>
                            <div class="col-lg-8">
                                <input type="password" class="form-control input-sm" placeholder="Password" v-model="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-4" v-if="error">
                                <p class="text-danger" style="font-size:12px;">{{error}}</p><br/>
                            </div>
                            <div class="col-lg-8 col-lg-offset-4" v-if="!loading">
                                <button class="btn btn-default" @click.prevent="verify">Proceed</button>
                                <button class="btn btn-danger" @click.prevent="cancel">Cancel</button>
                            </div>
                            <div class="col-lg-8 col-lg-offset-4" v-if="loading && !error">
                                <loadingInline label="Please wait, checking verifier validity."></loadingInline>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
  props : ['full'],
  data(){
      return { username : null, password : null, loading : false, error : null };
  },
  methods : {
      cancel(){
          this.username = null;
          this.password = null;
          this.$emit("cancel",null);
      },
      verify(){
          let {username,password} = this;
          let {user_id,facility_cd} = this.$session.get('user');
          
          this.loading = true;
          this.$http.post(this,"verify",{
              username, password, facility_cd, current_user_id : user_id
          })
          .then(({data}) => {
              if(!data){
                  this.error = "Verifier Username/Password is invalid";
              }else{
                  this.cancel();
                  this.$emit("proceed",{
                      username
                  });
              }
              this.loading = false;
          })
      }
  },
  mounted(){
      $("#user_id").focus();
  }
}
</script>