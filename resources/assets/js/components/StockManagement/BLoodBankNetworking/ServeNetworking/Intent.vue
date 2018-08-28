<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Blood Request Details
                    </div>
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Blood Type</th>
                                <th>Component</th>
                                <th>Quantity</th>
                                <th>Available</th>
                                <th width="100"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(d,i) in details" :key="i">
                                <td>{{d.blood_type}}</td>
                                <td>{{components[d.component_cd]}}</td>
                                <td>{{d.quantity}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        props : ['intent'],
        data(){
            return {
                details : JSON.parse(this.intent.details),
                components : this.$session.get('components')
            }
        }
    }
</script>