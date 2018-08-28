<template>
    <tr>
        <td><p class="text-primary" style="cursor:pointer;" @click="$emit('showFacility',facility)">{{facility.facility_name}}</p></td>
        <td>{{facility.distance.distance}} km</td>
        <td>{{facility.distance.time}}</td>
    </tr>
</template>
<script>
    export default {
        props : ['facility','details']
    }
</script>
