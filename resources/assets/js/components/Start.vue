<template>
  <div>
      <navbar v-if="!isFacility"></navbar>
      <navbarfacility v-if="isFacility" :current="user"></navbarfacility>
      <div class="container">
          <div class="content" style="padding-top:80px;" v-if="!$store.state.error">
              <flushAlert></flushAlert>
              <router-view v-if="!guest"></router-view>
              <login v-if="guest"></login>
          </div>
          <div class="content" v-if="$store.state.error">
              <error></error>
          </div>
      </div>
  </div>
</template>

<script>
import navbar from './navbar.vue';
import navbarfacility from './navbarFacility.vue';
import login from './login.vue';

export default {
    components: {
        navbar, navbarfacility, login
    },
    data(){
        return {
            isFacility : true, user : {ulevel : 0}
        }
    },
    mounted(){
        if(this.$session.has('user')){
            let user = this.$session.get('user')
            this.user = user
            this.$store.commit('USER',user);
            this.$store.commit('TOKEN',this.$session.get('token'));
            if(user.ulevel == -1){
                this.isFacility = false
            }
        }
        
    },
    computed : {
        guest(){
            return !this.$store.state.user;
        }
    }
}
</script>

<style>
.control-label {
    margin-top:-.5em;
    font-size: 14px;
}
.form-group.required .control-label:after {
  content:"*";
  color:red;
  margin-left:1em;
}
.error {
    font-size:12px;
    margin-top:0.5em;
}
</style>