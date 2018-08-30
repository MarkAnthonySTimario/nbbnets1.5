import axios from 'axios';

export default{
    get(el,url){
        let {access_token} = el.$session.get('token');
        return axios.get('api/'+url,{headers : {Authorization : access_token}});
    },
    post(el,url,data){
        try {
            let {access_token} = el.$session.get('token');
            return axios.post('api/'+url,data,{headers : {Authorization : access_token}});
        }catch(error){
            //el.$store.state.error = error;
        }
    }
}

