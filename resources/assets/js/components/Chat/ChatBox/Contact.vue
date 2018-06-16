<template>
    <li>
        <span class="chat-img pull-left">
            <!-- <img :src="avatarUri + contact.user_fname.substr(0,1) + contact.user_lname.substr(0,1)" alt="User Avatar" class="img-circle" /> -->
        </span>
        <div class="chat-body clearfix">
            <div class="header">
                <a href="#" @click="$emit('selectContact',contact)"><b class="primary-font" style="margin-left:1em;">{{contact.user_fname}} {{contact.user_lname}}</b></a> 
                <span class="badge badge-danger" style="background-color:#dc3545;margin-left:2em;" v-if="badge">{{badge}}</span>
                <br/>
                <b class="primary-font" style="margin-left:1em;">{{contact.facility.facility_name}}</b><br/>
                <b class="primary-font" style="margin-left:1em;">{{contact.position}}</b>
            </div>
        </div>
    </li>
</template>

<script>
export default {
    props : ['contact','badge'],
    computed : {
        avatarUri(){
            let color = _.filter(this.$store.state.users,{user_id : this.contact.user_id}).length ? 'd9534f' : '55C1E7';
            return 'http://placehold.it/50/'+color+'/fff&text=';
        }
    }
}
</script>
