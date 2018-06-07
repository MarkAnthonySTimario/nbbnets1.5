<template>
    <div>
        <input type="text" class="form-control input-sm" placeholder="Search Name of User" v-model="search">
        <hr/>   
        <loadingInline v-if="loading" label="searching for contact"></loadingInline>
        <ul class="chat" v-if="!loading">
            <contact @selectContact="selectContact" v-for="contact in filteredContacts" :key="contact.user_id" :contact="contact" ></contact>
        </ul>
    </div>
</template>

<script>
import Contact from './Contact.vue';

export default {
    components : {Contact},
    data(){
        return {
            search : null, loading : false,filteredContacts : [] 
        }
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
            this.$emit("selectContact",contact);
        }
    }
}
</script>
