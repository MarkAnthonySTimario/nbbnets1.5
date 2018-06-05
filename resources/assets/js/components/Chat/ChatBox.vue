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
                        <contact @select="selectContact" v-for="contact in filteredContacts" :key="contact.user_id" :contact="contact" ></contact>
                    </ul>
                    <div v-if="!loading && filteredContacts.length == 0">
                       <contact-fetch @select="selectContact" :userid="pm" :badge="msg.length" v-for="(msg , pm) in pms" :key="pm" ></contact-fetch>
                       <!-- <contact @select="selectContact" :contact="{user_id : 'FACILITY', user_fname : 'FACILITY' , user_lname : 'CHAT' , facility : {facility_name : $session.get('user').facility.facility_name} }" :badge="pms2['FACILITY'] ? pms2['FACILITY'].length : 0" ></contact> -->
                       <contact @select="selectContact" :contact="{user_id : 'ALL', user_fname : 'PUBLIC' , user_lname : 'CHAT' , facility : {facility_name : 'PUBLIC'} }" :badge="pms2['ALL'] ? pms2['ALL'].length : 0" ></contact>
                    </div>
                </div>

                <room v-if="contact" :contact="contact" @close="contact = null;search = '';"></room>
                
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Room from './Room.vue';
import Contact from './Contact.vue';
import ContactFetch from './ContactFetch.vue';

export default {
    props : ['reset'],
    components : {Room,Contact,ContactFetch},
    data(){
        let {user_id} = this.$session.get('user');
        return {contact : null,
        search : null, filteredContacts : [], loading : false, 
        user_id
        };
    },
    watch : {
        search(){
            this.doSearch(this);
        },
        reset(){
            this.search = ''; this.contact = null;
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
            let {user_id} = this.$session.get('user');
            return _.filter(this.$store.state.messages, message => {
                if((message.to.toUpperCase() == user_id.toUpperCase() || message.to == 'ALL' || message.to == 'FACILITY') && !message.seen){
                    if(message.from != user_id){
                        return message;
                    }
                }
            });
        },
        pms2(){
            return _.groupBy(this.mymessages,'to');
        },
        pms(){
            return _.groupBy(_.filter(this.mymessages, message => {
                if(message.to != 'ALL' && message.to != 'FACILITY'){
                    return message;
                }
            }),'from');
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
