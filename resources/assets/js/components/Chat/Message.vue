<template>
    <li :class="(message.from != user_id ? 'left' : 'right') +'clearfix'">
        <span class="chat-img pull-left" v-if="message.from != user_id && message.to == 'ALL'">
            <img v-if="contactFetch" :src="'http://placehold.it/50/55C1E7/fff&text=' + contactFetch.user_fname.substr(0,1) + contactFetch.user_lname.substr(0,1)" alt="User Avatar" class="img-circle" />
        </span>
        <span class="chat-img pull-left" v-if="message.from != user_id && message.to != 'ALL'">
            <img :src="'http://placehold.it/50/55C1E7/fff&text=' + contact.user_fname.substr(0,1) + contact.user_lname.substr(0,1)" alt="User Avatar" class="img-circle" />
        </span>
        <span class="chat-img pull-right" v-if="message.from == user_id">
            <img :src="'http://placehold.it/50/FA6F57/fff&text=' + user.user_fname.substr(0,1) + user.user_lname.substr(0,1)" alt="User Avatar" class="img-circle" />
        </span>
        <div class="chat-body clearfix">
            <div class="header" v-if="message.from != user_id">
                <b class="primary-font" style="margin-left:1em;">{{message.from}}</b>
                <small class="pull-right text-muted">
                    <span class="glyphicon glyphicon-time"></span><timeago :since="message.date"></timeago>
                </small>
            </div>
            <div class="header" v-if="message.from == user_id">
                <small class=" text-muted"><span class="glyphicon glyphicon-time"></span><timeago :since="message.date"></timeago></small>
                <b class="pull-right primary-font" style="margin-right:1em;">{{message.from}}</b>
            </div>
            <p v-if="message.from != user_id">
                &nbsp;&nbsp;&nbsp;{{message.text}}
            </p>
            <p v-if="message.from == user_id">
                {{message.text}}
            </p>
        </div>
    </li>
</template>

<script>
import VueTimeago from 'vue-timeago'

Vue.use(VueTimeago, {
  name: 'Timeago', // Component name, `Timeago` by default
  locale: undefined, // Default locale
  locales: {
    'th': require('date-fns/locale/th'),
  }
});

export default {
    props : ['message','contact'],
    data(){
        let user = this.$session.get('user');
        let {user_id} = user;
        return {
            user_id, user, contactFetch : null
        }
    },
    mounted(){
        let {user_id} = this.$session.get('user');
        if(this.message.from != user_id){
            Window.socket.emit("seen",this.message);
        }
        this.$http.get(this,"contact/"+this.message.from)
            .then(({data}) => {
                this.contactFetch = data;
            });
    }
}
</script>

<style scoped>
.chat li
{
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
{
    margin-right: 60px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}

.panel .slidedown .glyphicon, .chat .glyphicon
{
    margin-right: 5px;
}

.panel-body
{
    overflow-y: scroll;
    height: 250px;
}

::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}
</style>
