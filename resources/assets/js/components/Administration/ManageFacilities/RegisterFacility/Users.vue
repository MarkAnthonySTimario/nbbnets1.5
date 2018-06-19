<template>
    <div>
        <center  v-if="loading"><br/><loadingInline label="Please Wait."></loadingInline></center>
        <div class="col-lg-12 form-horizontal" v-if="!loading">
            <br/><h3 class="text-success" style="border-bottom:1px solid #c1c1c1;">Facility Users</h3><br/>
            
            <table class="table table-condensed table-bordered table-striped">
                <thead>
                    <tr class="bg-primary" style="font-size:12px;">
                        <th class="text-center">User ID</th>
                        <th class="text-center">User Level</th>
                        <th class="text-center">First Name</th>
                        <th class="text-center">Middle Name</th>
                        <th class="text-center">Last Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row,i) in rows" :key="i">
                        <td><input type="text" class="form-control input-sm" placeholder="User ID" v-model="row.user_id" :disabled="!topIsDone(i)"></td>
                        <td>
                            <select class="form-control input-sm" placeholder="User Level" v-model="row.ulevel" :disabled="!topIsDone(i)">
                                <option :value="l.userlevelid" v-for="l in userlevels" :key="l.userlevelid">{{l.userlevelname}}</option>
                            </select>
                        </td>
                        <td><input type="text" class="form-control input-sm" placeholder="First Name" v-model="row.fname" :disabled="!topIsDone(i)"></td>
                        <td><input type="text" class="form-control input-sm" placeholder="Middle Name" v-model="row.mname" :disabled="!topIsDone(i)"></td>
                        <td><input type="text" class="form-control input-sm" placeholder="Last Name" v-model="row.lname" :disabled="!topIsDone(i)"></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <button class="btn btn-default btn-sm" @click="rows.push(newRow())">New Row</button>
                            <span class="text-info" style="font-size:12px;"><span class="glyphicon glyphicon-info-sign"></span> All User IDs will have the Generated Facility Code as prefix, Example : bobong = 00000_bobong</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script>
export default {

    props : ['facility'],

    data(){
        let rows = [];
        for(let i = 0; i < 2; i++){
            rows.push(this.newRow());
        }

        return {
            rows, loading : true, userlevels : []
        }
    },

    mounted(){
        this.$http.get(this,'keyvalues/userlevels')
        .then(({data}) => {
            this.userlevels = data;
            this.loading = false;
        });

        if(this.facility.users.length > 0){
            this.rows = this.facility.users;
        }
    },

    methods : {
        newRow(){
            return {
                user_id : null, ulevel : null, fname : null, mname : null, lname : null
            };
        },
        topIsDone(i){
            if(!this.rows.length){
                return false;
            }
            if(i == 0){
                return true;
            }

            let top = this.rows[i-1];
            // console.log(top);
            let {user_id,ulevel,fname,mname,lname} = top;

            if(user_id && ulevel !== null && fname && mname && lname){
                return true;
            }
            
            let current = this.rows[i];
            this.rows[i].user_id = null;
            this.rows[i].ulevel = null;
            this.rows[i].fname = null;
            this.rows[i].mname = null;
            this.rows[i].lname = null;
            return false;
        }
    },

    watch : {
        
        rows : {
            handler(){
                this.facility.users = this.rows;
            },
            deep : true
        }

    }
}
</script>
