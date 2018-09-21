<template>
  <div class="row" style="margin-top:50px;">
      <div class="col-lg-6 col-md-6 col-lg-offset-3 col-md-offset-3">
          <div class="panel panel-success">
              <div class="panel-heading">Change Password</div>
              <div class="panel-body form-horizontal" v-if="!done">
                  <div class="form-group"><label for="" class="control-label col-lg-4">Old Password</label>
                    <div class="col-lg-8">
                        <input type="password" class="form-control input-sm"  v-model="oldpassword">
                    </div>
                  </div>

                  <div class="form-group"><label for="" class="control-label col-lg-4">New Password</label>
                    <div class="col-lg-8"><input type="password" class="form-control input-sm"  v-model="newpassword"></div>
                  </div>

                  <div class="form-group"><label for="" class="control-label col-lg-4">Re-type New Password</label>
                    <div class="col-lg-8"><input type="password" class="form-control input-sm"  v-model="confirm"></div>
                  </div>

                  <div class="form-group">
                      <div class="col-lg-8 col-lg-offset-4">
                          <button class="btn btn-default btn-sm" :disabled="!formok" @click.prevent="changePassword">Change Password</button>
                          <div v-if="loading" >
                              <br/>
                              <loadingInline label="Saving, please wait.."></loadingInline>
                          </div>
                          <div v-if="error" class="text-danger">
                              <br/>
                              <span class="glyphicon glyphicon-warning-sign"></span> {{error}}
                          </div>
                      </div>
                  </div>
              </div>
              <div class="panel-body text-center" v-if="done">
                  <div class="alert alert-success">
                      <span class="glyphicon glyphicon-ok"></span> Password has been changed successfully.
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>

export default {
    data(){
        return {
            oldpassword : null, newpassword : null, confirm : null, loading : false, done : false, error : ''
        }
    },
    computed : {
        formok(){
            if(this.loading){
                return false;
            }
            if(!this.oldpassword || !this.newpassword){
                return false;
            }
            if(this.newpassword != this.confirm){
                return false;
            }
            return true;
        }
    },
    methods : {
        changePassword(){
            this.loading = true;
            let {oldpassword,newpassword} = this;
            this.$http.post(this,"changepassword",{
                oldpassword, newpassword, user_id : this.$store.state.user.username
            })
            .then(({data}) => {
                this.loading = false;
                if(data == 'wrong password'){
                    this.error = "Old password provided is invalid.";
                    return;
                }

                this.done = true;
            })
            .catch(error => {
                this.$store.state.error = error;
            });
        }
    }
}
</script>