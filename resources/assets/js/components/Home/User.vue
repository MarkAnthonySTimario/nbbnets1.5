<template>
  <div class="panel panel-danger table-responsive">
      <div class="panel-heading">User Details
          <router-link to="/changepassword" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-lock"></span> Change Password</router-link>
      </div>
      <table class="table table-condensed" style="font-size:12px;">
          <tbody>
            <tr>
                <th nowrap>User Name</th><td>{{user.username}}</td>
            </tr>
            <tr>
                <th>Name</th><td style="font-size:14px;" nowrap>
                    {{user.user_fname}} 
                    {{user.user_mname}} 
                    {{user.user_lname}} 
                </td>
            </tr>
            <tr>
                <th>User Level</th>
                <td>{{user.level.userlevelname}}</td>
            </tr>
          </tbody>
      </table>
  </div>
</template>

<script>
    export default{
        data(){
            let user = this.$session.get('user')
            return {
                user
            }
        }
    }
</script>