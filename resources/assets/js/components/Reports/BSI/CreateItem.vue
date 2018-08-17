<template>
    <tr>
        <td v-if="item.level==0 && item.header && item.parent" class="section" colspan="5">{{ item.question_no }}. {{item.question}}</td>

        <td v-if="item.level==1 && item.parent" colspan="5">{{ item.question_no }}. {{item.question}}</td>

        <td v-if="item.level==1 && item.isYesNo" colspan="3">{{ item.question_no }}. {{item.question}}</td>

        <td v-if="item.level==2 && item.isYesNo"></td>
        <td v-if="item.level==2 && item.isYesNo" colspan="2">{{ item.question_no }}. {{item.question}}</td>

        <td class="text-center radio-bg" v-if="item.isYesNo">
            <input type="radio" :name="item.question_no" :value="1" v-model="item.value">
        </td>
        <td class="text-center radio-bg" v-if="item.isYesNo">
            <input type="radio" :name="item.question_no" :value="0" v-model="item.value" >
        </td>        
    </tr>   
</template>

<script>
    export default {   
        props : ['item']
    }
</script>

<style scoped>

thead > tr {
    background-color: #ccc;
}
.text-bg {
    background-color: #FFFF99;
}
.radio-bg {
    background-color: #C0C0C0;
}
.section {
    background-color: #993366;
    color: #fff;  
    font-weight: bold;
}



</style>