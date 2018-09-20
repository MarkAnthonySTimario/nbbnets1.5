<template>
    <div>
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">Facility Users</div>
                    <div class="panel-body">
                        <button class="btn btn-default btn-xs" @click="createNew = true">New User</button>
                    </div>
                    <table class="table table-condensed table-striped" style="font-size:12px;">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Access Level</th>
                                <th>Active</th>
                                <th width="100"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(u,i) in users" :key="i">
                                <td>{{u.username}}</td>
                                <td>{{u.user_fname}} {{u.user_lname}}</td>
                                <td>{{u.level.userlevelname}}</td>
                                <td>
                                    <span class="text-success" v-if="u.disable_flag == 'N'">Active</span>
                                    <span class="text-danger" v-if="u.disable_flag == 'Y'">Disactivated</span>
                                </td>
                                <td>
                                    <button @click="current=u" :disabled="createNew || current" class="btn btn-primary btn-xs" title="Update User Information"><span class="glyphicon glyphicon-user"></span></button>
                                    <button @click="resetPassword(u)" :disabled="createNew || current" class="btn btn-info btn-xs" title="Reset Password"><span class="glyphicon glyphicon-lock"></span></button>
                                    <button v-if="u.disable_flag == 'N'" @click="disable(u)" :disabled="createNew || current" class="btn btn-danger btn-xs" title="Disable User Account"><span class="glyphicon glyphicon-remove"></span></button>
                                    <button v-if="u.disable_flag == 'Y'" @click="activate(u)" :disabled="createNew || current" class="btn btn-success btn-xs" title="Activate User Account"><span class="glyphicon glyphicon-ok"></span></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6" v-if="createNew">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Create New User
                    </div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">User ID</label>
                            <div class="col-lg-8"><input v-model="user_id" type="text" placeholder="User ID" class="form-control input-sm"></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">Access Level</label>
                            <div class="col-lg-8">
                                <select class="form-control input-sm" v-model="ulevel">
                                    <option v-for="(l,i) in levels" :key="i" :value="l.userlevelid">{{l.userlevelname}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">First Name</label>
                            <div class="col-lg-8"><input v-model="user_fname" type="text" placeholder="First Name" class="form-control input-sm"></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">Middle Name</label>
                            <div class="col-lg-8"><input v-model="user_mname" type="text" placeholder="Middle" class="form-control input-sm"></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">Last Name</label>
                            <div class="col-lg-8"><input v-model="user_lname" type="text" placeholder="Last Name" class="form-control input-sm"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-3">
                                <button class="btn btn-default btn-sm" @click="save">Save</button>
                                <button class="btn btn-danger btn-sm" @click="cancel">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" v-if="current">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Update User
                    </div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">User ID</label>
                            <div class="col-lg-8"><div class="form-control input-sm">{{current.username}}</div></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">Access Level</label>
                            <div class="col-lg-8">
                                <select class="form-control input-sm" v-model="current.ulevel">
                                    <option v-for="(l,i) in levels" :key="i" :value="l.userlevelid">{{l.userlevelname}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">First Name</label>
                            <div class="col-lg-8"><input v-model="current.user_fname" type="text" placeholder="First Name" class="form-control input-sm"></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">Middle Name</label>
                            <div class="col-lg-8"><input v-model="current.user_mname" type="text" placeholder="Middle" class="form-control input-sm"></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-lg-3">Last Name</label>
                            <div class="col-lg-8"><input v-model="current.user_lname" type="text" placeholder="Last Name" class="form-control input-sm"></div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-3">
                                <button class="btn btn-default btn-sm" @click="update">Save</button>
                                <button class="btn btn-danger btn-sm" @click="cancel">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            let user = this.$session.get('user')
            let {facility_cd} = user
            return {
                users : [], user, facility_cd, current : null, createNew : false, levels : [],
                user_id : null, ulevel : null, user_fname : null, user_mname : null, user_lname : null,
            }
        },
        mounted(){
            this.getLevels()
            this.getUsers()
        },
        methods : {
            getUsers(){
                this.$http.get(this,'admin/users/'+this.facility_cd)
                .then(({data}) => {
                    this.users = data
                })
            },
            getLevels(){
                this.$http.get(this,'admin/levels')
                .then(({data}) => {
                    this.levels = data
                })
            },
            save(){
                let {facility_cd,user_id,ulevel,user_fname,user_mname,user_lname} = this
                if(!user_id || !ulevel || !user_fname || !user_lname){
                    this.$store.state.msg = {content : 'Please provide all User Information'};
                    return 
                }
                this.checkUserId()

            },
            update(){
                let {user_fname,user_lname} = this.current
                if(!user_fname || !user_lname){
                    this.$store.state.msg = {content : 'Please provide all User Information'};
                    return 
                }
                this.doUpdate()
            },
            doUpdate(){
                let {facility_cd,username,ulevel,user_fname,user_mname,user_lname} = this.current
                this.$http.post(this,'admin/updateuser',{
                    facility_cd,username,ulevel,user_fname,user_mname,user_lname
                })
                .then(({data}) => {
                    this.clear()
                    this.getUsers()
                    this.createNew = false
                    this.current = null
                })
            },
            checkUserId(){
                let {facility_cd,user_id} = this
                this.$http.get(this,'admin/checkuserid/'+facility_cd+"_"+user_id)
                .then(({data}) => {
                    if(data){
                        this.$store.state.msg = {content : 'User ID already taken'}
                    }else{
                        this.doSave()
                    }
                })
            },
            doSave(){
                let {facility_cd,user_id,ulevel,user_fname,user_mname,user_lname} = this
                this.$http.post(this,'admin/adduser',{
                    facility_cd,user_id,ulevel,user_fname,user_mname,user_lname
                })
                .then(({data}) => {
                    this.clear()
                    this.getUsers()
                    this.createNew = false
                })
            },
            cancel(){
                this.clear()
                this.createNew = false
                this.current = null
            },
            clear(){
                this.user_id = null
                this.ulevel = null
                this.user_fname = null
                this.user_mname = null
                this.user_lname = null
            },
            resetPassword(u){
                if(confirm("Reset User Password")){
                    let {facility_cd} = this
                    
                    this.$http.post(this,'admin/resetuserpassword',{
                        username : u.username,facility_cd
                    })
                    .then(({data}) => {
                        this.$store.state.msg = {content : "User password has been reset"}
                    })
                }
            },
            disable(u){
                if(confirm("Disable User Account?")){
                    let {facility_cd} = this
                    
                    this.$http.post(this,'admin/disableuser',{
                        username : u.username,facility_cd
                    })
                    .then(({data}) => {
                        this.getUsers()
                        this.$store.state.msg = {content : "User Account Disabled"}
                    })
                }
            },
            activate(u){
                if(confirm("Activate User Account?")){
                    let {facility_cd} = this
                    
                    this.$http.post(this,'admin/activateuser',{
                        username : u.username,facility_cd
                    })
                    .then(({data}) => {
                        this.getUsers()
                        this.$store.state.msg = {content : "User Account Activated"}
                    })
                }
            }
        }
    }
</script>