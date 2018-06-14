<template>
  <div class="panel panel-success table-responsive">
      <div class="panel-heading">User Details
          <router-link to="/changepassword" class="btn btn-default btn-xs pull-right"><span class="glyphicon glyphicon-lock"></span> Change Password</router-link>
      </div>
      <table class="table table-condensed" style="font-size:12px;">
          <tbody>
            <tr>
                <th nowrap>User Name</th><td>{{$store.state.user.user_id}}</td>
            </tr>
            <tr>
                <th>Name</th><td style="font-size:14px;" nowrap>
                    {{$store.state.user.user_fname}} 
                    {{$store.state.user.user_mname}} 
                    {{$store.state.user.user_lname}} 
                </td>
            </tr>
            <tr>
                <th>User Level</th>
                <td>{{$store.state.user.level.userlevelname}}</td>
            </tr>
          </tbody>
      </table>
  </div>
</template>
