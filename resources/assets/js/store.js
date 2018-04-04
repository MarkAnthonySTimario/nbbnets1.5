import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state : {
        error : null,
        user : null, token : null,
        components : null, blood_types : null, 
        AgencyManager : {
            search : null
        },
        MBD : {
            search : null,
            donor_search : {
                fname : null, mname : null, lname : null
            }
        },
        msg : null
    },
    mutations : {
        USER : (state,user) => {
            state.user = user;
        },
        TOKEN : (state,token) => {
            state.token = token;
        }
    }
});