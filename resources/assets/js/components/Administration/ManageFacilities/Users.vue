<template>
    <div>
        <Loading v-if="loading" />
        <div class="row" v-if="!loading">
            <div class="col-lg-8">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Facility Users
                    </div>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Access Level</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="u in users" :key="u.user_id">
                                <td>{{u.user_id}}</td>
                                <td>{{u.user_fname}} {{u.user_lname}}</td>
                                <td>{{u.level ? u.level.userlevelname : null}}</td>
                                <td>
                                    <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-edit"></span></button>
                                </td>
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
        props : ['facility_cd'],
        data(){
            return {
                users : [], loading : false
            }
        },
        mounted(){
            this.loadUsers()
        },
        methods : {
            loadUsers(){
                this.loading = true
                this.$http.get(this,'admin/facilityusers/'+this.facility_cd)
                .then(({data}) => {
                    this.users = data
                    this.loading = false
                })
            }
        }
    }
</script>