<template>
    <tr>
        <td><router-link :to="'BloodBankNetworking/'+facility.facility_cd" class="text-primary">{{facility.facility_name}}</router-link></td>
        <td>{{facility.distance.distance}} km</td>
        <td>{{facility.distance.time}}</td>
    </tr>
</template>
<script>
    export default {
        props : ['facility']
    }
</script>
