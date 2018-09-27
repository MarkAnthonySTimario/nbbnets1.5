<template>
    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" >
        <span class="glyphicon glyphicon-chevron-down"></span>
        <span class="badge badge-danger" style="background-color:#dc3545;" v-if="myMessages.length">{{myMessages.length}}</span>
    </a>
</template>

<script>
export default {
  data(){
    let {username} = this.$session.get('user');
    return {
      sound : null, user_id : username
    }
  },
  mounted(){
    this.initSound();
  },
  methods : {
    initSound(){
      axios.get("api/notificationsound")
      .then(({data}) => {
        this.sound = new Audio(data);
        Window.notificationsound = this.sound;
      })
    }
  },
  watch : {
    myMessages(){
      if(this.myMessages.length){
        if(!this.sound){
          this.initSound();
        }else{
          this.sound.play();
        }
      }
    }
  },
  computed : {
    myMessages(){
      let {user_id} = this;
      let {messages} = this.$store.state;
      return _.filter(messages, message => {
        if((message.to.toUpperCase() == user_id.toUpperCase() || message.to.toUpperCase() == 'ALL' || message.to.toUpperCase() == 'FACILITY') && message.seen == false){
          if(message.from != user_id){
            return message;
          }
        }
      })
    }
  }
}
</script>
