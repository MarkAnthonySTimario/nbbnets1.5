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
import Message from './Room/Message.vue';

export default {
    components : {Message},
    props : ['contact'],
    data(){
        let {messages} = this.$store.state;
        let {username} = this.$session.get('user');
        return {
            allMessages : messages, messages : [], msg : '', buffer_size : 50, user_id : username
        }
    },
    methods : {
        getRoomMessages(){
            let {contact,user_id} = this;
            this.messages = _.filter(this.$store.state.messages, message => {
                // if(contact.user_id == 'ALL'){
                //     return true;
                // }else 
                if(message.from == contact.user_id && message.to == user_id){
                    return true;
                }else if(message.to == contact.user_id && message.from == user_id){
                    return true;
                }
            });

            this.messages = _.orderBy(this.messages,['date'],['desc']);
            this.messages = _.slice(this.messages,0,this.buffer_size);
        },
        send(){
            if(this.msg.length == 0){
                return;
            }
            let {username} = this.$session.get('user');
            let message = {from : username, to : this.contact.user_id, text : this.msg};
            this.msg = '';
            Window.socket.emit('send',message);
        }
    },
    mounted(){
        let {contact,user_id} = this;
        if(contact.from != user_id){
            Window.socket.emit("seen",{from : contact.user_id, to : user_id});
        }
        this.allMessages = this.$store.state.messages;
        this.getRoomMessages();
    },
    watch : {
        allMessages(){
            this.getRoomMessages();
        }
    }
}
</script>

<style scoped>
#btn-input{
    text-transform: none !important;
}
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
