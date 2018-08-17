<template>
    <tr>
        <td>Question</td>
        <td><input type="radio" name="q" value="Yes"> </td>
        <td><input type="radio" name="q" value="No"> </td>
    </tr>
</template>

<script>
    export default {
        name: 'questions',
        data() {
            return {
                ask: 'yes or no'
            }
        }
    }
</script>

<style>

</style>