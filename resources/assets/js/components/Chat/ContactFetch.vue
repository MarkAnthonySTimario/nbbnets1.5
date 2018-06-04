<template>
    <li>
        <div v-if="contact">
            <span class="chat-img pull-left">
                <img :src="'http://placehold.it/50/55C1E7/fff&text=' + contact.user_fname.substr(0,1)" alt="User Avatar" class="img-circle" />
            </span>
            <div class="chat-body clearfix">
                <div class="header">
                    <a href="#" @click="$emit('select',contact)"><b class="primary-font" style="margin-left:1em;">{{contact.user_fname}} {{contact.user_lname}}</b></a> 
                    <span class="badge badge-danger" style="background-color:#dc3545;margin-left:2em;" >+1</span>
                    <br/>
                    <b class="primary-font" style="margin-left:1em;">{{contact.facility.facility_name}}</b><br/>
                    <b class="primary-font" style="margin-left:1em;">{{contact.position}}</b>
                </div>
            </div>
        </div>
        <div v-if="!contact">
            <loadingInline label="Loading"></loadingInline>
        </div>
    </li>
</template>

<script>
export default {
    props : ['userid'],
    data(){
        return {
            contact : null
        }
    },
    mounted(){
        this.$http.get(this,"contact/"+this.userid)
        .then(({data}) => {
            this.contact = data;
        });
    }
}
</script>
