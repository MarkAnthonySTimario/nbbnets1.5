<template>
<div>
    <div class="col-md-12" id="box">
        <div class="panel panel-primary">
            <div class="panel-collapse collapse" id="collapseOne">
                <div class="panel-body" v-if="!contact">
                    <input type="text" class="form-control input-sm" placeholder="Search Name of User" v-model="search">
                    <hr/>   
                    <loadingInline v-if="loading" label="searching for contact"></loadingInline>
                    <ul class="chat" v-if="!loading">
                        <contact @select="selectContact" v-for="contact in filteredContacts" :key="contact.user_id" :contact="contact"></contact>
                    </ul>
                    <div v-if="!loading && filteredContacts.length == 0">
                       <contact-fetch @select="selectContact" :userid="pm" badge="1+" v-for="(msg , pm) in pms" :key="pm" ></contact-fetch>
                       <contact @select="selectContact" :contact="{user_id : 'FACILITY', user_fname : 'FACILITY' , user_lname : 'CHAT' , facility : {facility_name : $session.get('user').facility.facility_name} }" :badge="facilityCount" ></contact>
                       <contact @select="selectContact" :contact="{user_id : 'ALL', user_fname : 'PUBLIC' , user_lname : 'CHAT' , facility : {facility_name : 'PUBLIC'} }" :badge="publicCount" ></contact>
                    </div>
                </div>
                <div class="panel-body" v-if="contact">
                    <a href="#" @click="contact = null" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-arrow-left"></span> Back to Contacts</a>
                    <hr/>   
                    <ul class="chat">
                        <message v-for="(message,i) in mymessages" :key="i" :message="message"></message>
                    </ul>
                </div>
                <div class="panel-footer" v-if="contact">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
                                Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Message from './Message.vue';
import Contact from './Contact.vue';
import ContactFetch from './ContactFetch.vue';

export default {
    components : {Message,Contact,ContactFetch},
    data(){
        let {user_id} = this.$session.get('user');
        return {contact : null, publicCount: 0, facilityCount: 0,
        search : null, filteredContacts : [], loading : false, 
        user_id
        };
    },
    mounted(){
        Window.socket.emit("load-messages",this.user_id);
    },
    watch : {
        search(){
            this.doSearch(this);
        }
    },
    methods : {
        doSearch : _.debounce((that) => {
            if(that.search.length == 0){
                that.filteredContacts = [];
                return;
            }
            that.loading = true;
            that.$http.post(that,'contacts/search',{search : that.search})
            .then(({data}) => {
                that.filteredContacts = data;
                that.loading = false;
            });
        },500),
        selectContact(contact){
            this.contact = contact;
        }
    },
    computed : {
        mymessages(){
            return _.filter(this.messages, message => {
                if(message.from.upperCase() == user_id.upperCase()){
                    return message;
                }else if(message.to.upperCase() == user_id.upperCase()){
                    return message;
                }
            });
        },
        pmCount(){
            return this.$store.state.messages.length();
        },
        pms(){
            return _.groupBy(this.$store.state.messages,'from');
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
