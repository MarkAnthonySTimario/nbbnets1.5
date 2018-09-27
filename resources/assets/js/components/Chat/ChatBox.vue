<template>
    <div>
        <div class="col-md-12" id="box">
            <div class="panel panel-primary">
                <div class="panel-collapse collapse" id="collapseOne">
                    <div class="panel-body" v-if="!contact">

                        <search-contact :search="search" :contact="contact" @selectContact="selectContact"></search-contact>

                        <div>
                        
                            <!-- Recent New Messages -->
                            <contact-fetch 
                                v-for="(msg , pm) in mymessages_from" :key="pm" 
                                :userid="pm" 
                                :badge="msg.length" 
                                @selectContact="selectContact" >
                            </contact-fetch>

                            <!-- Hot Contacts (Public, Support) -->
                            <contact 
                                :contact="contact_all" 
                                :badge="mymessages_to['ALL'] ? mymessages_to['ALL'].length : 0" 
                                @selectContact="selectContact" >
                            </contact>

                            <contact 
                                v-if="user_id != 'admin'" 
                                :contact="contact_support" 
                                :badge="mymessages_to['admin'] ? mymessages_to['admin'].length : 0" 
                                @selectContact="selectContact" >
                            </contact>

                        </div>

                    </div>

                    <room v-if="contact" :contact="contact" @close="contact = null;search = '';"></room>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Room from './ChatBox/Room.vue';
import SearchContact from './ChatBox/SearchContact.vue';
import Contact from './ChatBox/Contact.vue';
import ContactFetch from './ChatBox/ContactFetch.vue';

export default {
    props : ['reset'],
    components : {Room,SearchContact,Contact,ContactFetch},
    data(){

        let contact_all = {
            user_id : 'ALL', 
            user_fname : 'PUBLIC' , 
            user_lname : 'CHAT' , 
            facility : {facility_name : 'PUBLIC'}
        };

        let contact_support = {
            user_id : 'admin', 
            user_fname : 'NBBNETS' , 
            user_lname : 'SUPPORT' , 
            facility : {facility_name : 'NVBSP - IMU'} 
        };

        let {username} = this.$session.get('user');

        return {
            search : null, contact : null, contact_all, contact_support ,user_id : username, loading : false
        };

    },
    watch : {
        reset(){
            this.search = ''; this.contact = null;
        }
    },
    methods : {
        mustReceive(message){
            let {user_id} = this;
            return (message.to == user_id || message.to == 'ALL' || message.to == 'FACILITY') && !message.seen && message.from != user_id;
        },
        selectContact(contact){
            this.contact = contact;
        }
    },
    computed : {
        mymessages(){
            let {messages} = this.$store.state;
            return _.filter(messages, message => {
                if( this.mustReceive(message)){
                    return message;
                }
            });
        },
        mymessages_from(){
            return _.groupBy(_.filter(this.mymessages, message => {
                if(message.to != 'ALL'){
                    if(this.user_id != 'admin' &&  message.to != 'admin'){
                        return message;
                    }else{
                        return message;
                    }
                }
            }),'from');
        },
        mymessages_to(){
            return _.groupBy(this.mymessages,'to');
        }
    }
}
</script>


<style scoped>
@media (max-width: 500px) {
    #box{
        position:fixed;
        margin-left:0px;
        width:100%;
        margin-top: -300px;
    }
    .panel-body{
        height: 100%;
    }
}
@media (min-width: 1000px) {
    /* some CSS for large resolution */
    #box{
        position: absolute;
        margin-left:-300px;
        width:500px;
    }
    .panel-body{
        height: 400px;
    }
}
#box{
    z-index: 5000;
    
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
