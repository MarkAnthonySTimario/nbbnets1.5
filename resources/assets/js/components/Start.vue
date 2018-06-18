<template>
  <div>
      <navbar></navbar>
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
import login from './login.vue';

export default {
    components: {
        navbar, login
    },
    mounted(){
        if(this.$session.has('user')){
            this.$store.commit('USER',this.$session.get('user'));
            this.$store.commit('TOKEN',this.$session.get('token'));
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