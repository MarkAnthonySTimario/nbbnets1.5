<template>
  <div>
      <list v-show="!editing" @hasSelected="setSelections" :updated="refresh" @setSchedId="setSchedId"></list>
      <editing v-if="editing" :schedId="sched_id" :donationids="selections" @cancel="selections = []; editing = false; refresh++;"></editing>
  </div>
</template>

<script>
import List from './Typing/List.vue';
import Editing from './Typing/Editing.vue';

export default {
    components : {List,Editing},
    data(){
        return {
            selections : [], editing : false, refresh : 0, sched_id : null
        }
    },
    methods : {
        setSelections(selected){
            this.selections = selected;
            this.editing = true;
        },
        setSchedId(sched_id){
            this.sched_id = sched_id
        }
    }
}
</script>
