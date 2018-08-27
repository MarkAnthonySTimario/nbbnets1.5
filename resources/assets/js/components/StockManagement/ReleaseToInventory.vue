<template>
  <div>
      <list @hasSelected="setSelections" :refresh="refresh"></list>
  </div>
</template>

<script>
import List from './ReleaseToInventory/List.vue';

export default {
    components : {List},
    data(){
        return {
            selections : [],refresh : 0
        }
    },
    methods : {
        setSelections(selected){
            this.selections = selected;
        }
    }
}
</script>
