<template>
    <div class="text-center">
        <camera :show="cameraVisible" @oncapture="getPhoto"></camera>
        <div class="col-lg-12">
            <button class="btn btn-xs btn-default" @click.prevent="(cameraVisible++)"><span class="glyphicon glyphicon-camera"></span> Take Photo</button>
        </div>
        <div class="col-lg-12">
            <img :src="photo" alt="Donor Photo" v-if="photo" style="width:200px;height:150px; margin-top:1em;">
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return {
                photo : null, cameraVisible : 0
            }
        },
        methods : {
            getPhoto(photo){
                this.photo = photo;
                this.$emit("oncomplete",photo);
            }
        }
    }
</script>