<template>
    <div>
        <div class="panel-body">
            <a href="#" @click="$emit('close')" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-arrow-left"></span> Back to Contacts</a>
            <hr/>   
            <ul class="chat">
                <message v-for="(message,i) in messages" :key="i" :message="message" :contact="contact"></message>
            </ul>
        </div>
        <div class="panel-footer">
            <div class="input-group">
                <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." v-model="msg" @keyup.enter="send" />
                <span class="input-group-btn">
                    <button class="btn btn-warning btn-sm" id="btn-chat" @click="send">
                        Send</button>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import Message from './Message.vue';

export default {
    components : {Message},
    props : ['contact'],
    data(){
        let {messages} = this.$store.state;
        return {
            allMessages : messages, messages : [], msg : ''
        }
    },
    methods : {
        getRoomMessages(){
            this.messages = _.filter(this.$store.state.messages, message => {
                let {contact} = this;
                let user = this.$session.get('user');
                if(contact.user_id == 'ALL'){
                    return true;
                }else if(message.from.toUpperCase() == contact.user_id.toUpperCase() && message.to.toUpperCase() == user.user_id.toUpperCase()){
                    return true;
                }else if(message.to.toUpperCase() == contact.user_id.toUpperCase() && message.from.toUpperCase() == user.user_id.toUpperCase()){
                    return true;
                }
            });

            this.messages = _.orderBy(this.messages,['date'],['desc']);
            this.messages = _.slice(this.messages,0,20);
        },
        send(){
            if(this.msg.length == 0){
                return;
            }
            let {user_id} = this.$session.get('user');
            let message = {from : user_id, to : this.contact.user_id, text : this.msg};
            this.msg = '';
            Window.socket.emit('send',message);
        }
    },
    mounted(){
        this.allMessages = this.$store.state.messages;
        this.getRoomMessages();
    },
    watch : {
        allMessages(){
            this.getRoomMessages();
        },
        msg(){
            this.msg = this.msg.toUpperCase();
        }
    }
}
</script>

<style scoped>
@media (max-width: 500px) {
    #box{
        position:relative;
        margin-left:0px;
    }
}
@media (min-width: 1000px) {
    /* some CSS for large resolution */
    #box{
        position: absolute;
        margin-left:-300px;
    }
}
#box{
    z-index: 5000;
    width:500px;
}
.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

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
    height: 400px;
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
