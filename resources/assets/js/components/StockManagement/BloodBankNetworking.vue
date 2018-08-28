<template>
    <div>
        <div class="row" v-if="!loading">
            <div class="col-lg-5">
                <Search @apply="applyDetails" />
            </div>
            <div class="col-lg-7">
                <Result :details="details" :search="search" :facilities="facilities" />
            </div>
        </div>
        <Loading v-if="loading" />
    </div>
</template>

<script>
    import Search from './BloodBankNetworking/Search'
    import Result from './BloodBankNetworking/Result'

    export default{
        components : {Search, Result},
        data(){
            return {
                details : null, search : 0, facilities : [], loading : false,
            }
        },
        mounted(){
            this.facilities = [{"facility_cd":"00000","facility_name":"Department of Health","bloods":[],"distance":{"time":"0 Minutes","distance":0}},{"facility_cd":"03002","facility_name":"Angeles University Foundation Medical Center","bloods":[],"distance":{"time":"35.83 Hours","distance":1397.04}},{"facility_cd":"03003","facility_name":"Dr. Jose B. Lingad General Hospital","bloods":[],"distance":{"time":"35.57 Hours","distance":1381.38}},{"facility_cd":"03047","facility_name":"Bataan General Hospital","bloods":[{"component_cd":10,"blood_type":"A pos","count(*)":276},{"component_cd":10,"blood_type":"AB pos","count(*)":81},{"component_cd":10,"blood_type":"B pos","count(*)":335},{"component_cd":10,"blood_type":"O neg","count(*)":1},{"component_cd":10,"blood_type":"O pos","count(*)":537},{"component_cd":20,"blood_type":"A neg","count(*)":1},{"component_cd":20,"blood_type":"A pos","count(*)":127},{"component_cd":20,"blood_type":"AB pos","count(*)":40},{"component_cd":20,"blood_type":"B pos","count(*)":153},{"component_cd":20,"blood_type":"O pos","count(*)":208},{"component_cd":30,"blood_type":"A neg","count(*)":1},{"component_cd":30,"blood_type":"A pos","count(*)":121},{"component_cd":30,"blood_type":"AB pos","count(*)":43},{"component_cd":30,"blood_type":"B pos","count(*)":152},{"component_cd":30,"blood_type":"O pos","count(*)":207},{"component_cd":40,"blood_type":"A neg","count(*)":1},{"component_cd":40,"blood_type":"A pos","count(*)":120},{"component_cd":40,"blood_type":"AB pos","count(*)":43},{"component_cd":40,"blood_type":"B pos","count(*)":151},{"component_cd":40,"blood_type":"O pos","count(*)":207}],"distance":{"time":"36.66 Hours","distance":1433.99}},{"facility_cd":"04002","facility_name":"Batangas Medical Center","bloods":[],"distance":{"time":"32.9 Hours","distance":1209.76}},{"facility_cd":"05002","facility_name":"Bicol Regional Blood Center","bloods":[],"distance":{"time":"26.33 Hours","distance":1202.86}},{"facility_cd":"06002","facility_name":"Corazon L. Montelibano Memorial Regional Hospital","bloods":[],"distance":{"time":"15.92 Hours","distance":595.53}},{"facility_cd":"06004","facility_name":"Negros First Provincial Blood Center","bloods":[],"distance":{"time":"15.83 Hours","distance":593.78}},{"facility_cd":"07005","facility_name":"DOH - Region 7 Blood Center","bloods":[{"component_cd":10,"blood_type":"A neg","count(*)":2},{"component_cd":10,"blood_type":"A pos","count(*)":172},{"component_cd":10,"blood_type":"AB pos","count(*)":37},{"component_cd":10,"blood_type":"B pos","count(*)":159},{"component_cd":10,"blood_type":"O neg","count(*)":1},{"component_cd":10,"blood_type":"O pos","count(*)":302},{"component_cd":20,"blood_type":"A neg","count(*)":8},{"component_cd":20,"blood_type":"A pos","count(*)":810},{"component_cd":20,"blood_type":"AB neg","count(*)":1},{"component_cd":20,"blood_type":"AB pos","count(*)":184},{"component_cd":20,"blood_type":"B neg","count(*)":2},{"component_cd":20,"blood_type":"B pos","count(*)":826},{"component_cd":20,"blood_type":"O neg","count(*)":8},{"component_cd":20,"blood_type":"O pos","count(*)":1530},{"component_cd":30,"blood_type":"A pos","count(*)":180},{"component_cd":30,"blood_type":"AB pos","count(*)":58},{"component_cd":30,"blood_type":"B neg","count(*)":1},{"component_cd":30,"blood_type":"B pos","count(*)":185},{"component_cd":30,"blood_type":"O neg","count(*)":1},{"component_cd":30,"blood_type":"O pos","count(*)":340},{"component_cd":40,"blood_type":"A pos","count(*)":144},{"component_cd":40,"blood_type":"AB pos","count(*)":50},{"component_cd":40,"blood_type":"B neg","count(*)":1},{"component_cd":40,"blood_type":"B pos","count(*)":116},{"component_cd":40,"blood_type":"O neg","count(*)":1},{"component_cd":40,"blood_type":"O pos","count(*)":247}],"distance":{"time":"16.14 Hours","distance":550.63}},{"facility_cd":"11001","facility_name":"Davao Blood Center","bloods":[],"distance":{"time":"5.07 Hours","distance":234.4}},{"facility_cd":"11003","facility_name":"Davao Regional Medical Center","bloods":[{"component_cd":40,"blood_type":"A pos","count(*)":3},{"component_cd":40,"blood_type":"AB pos","count(*)":1},{"component_cd":40,"blood_type":"B pos","count(*)":5},{"component_cd":40,"blood_type":"O pos","count(*)":6}],"distance":{"time":"6.16 Hours","distance":282.2}},{"facility_cd":"11007","facility_name":"DBC - Partner - Tagum","bloods":[],"distance":{"time":"6.14 Hours","distance":281.22}},{"facility_cd":"11008","facility_name":"Peedo Blood Center","bloods":[],"distance":{"time":"0 Minutes","distance":0}},{"facility_cd":"12001","facility_name":"Caraga Regional Hospital Blood Bank","bloods":[],"distance":{"time":"11.09 Hours","distance":533.92}},{"facility_cd":"12002","facility_name":"Cotabato Regional Medical Center - Blood Bank","bloods":[{"component_cd":10,"blood_type":"A pos","count(*)":255},{"component_cd":10,"blood_type":"AB pos","count(*)":52},{"component_cd":10,"blood_type":"B neg","count(*)":1},{"component_cd":10,"blood_type":"B pos","count(*)":262},{"component_cd":10,"blood_type":"O neg","count(*)":1},{"component_cd":10,"blood_type":"O pos","count(*)":459},{"component_cd":20,"blood_type":"","count(*)":2},{"component_cd":20,"blood_type":"A neg","count(*)":12},{"component_cd":20,"blood_type":"A pos","count(*)":1831},{"component_cd":20,"blood_type":"AB neg","count(*)":1},{"component_cd":20,"blood_type":"AB pos","count(*)":521},{"component_cd":20,"blood_type":"B neg","count(*)":3},{"component_cd":20,"blood_type":"B pos","count(*)":2049},{"component_cd":20,"blood_type":"O neg","count(*)":9},{"component_cd":20,"blood_type":"O pos","count(*)":3902},{"component_cd":30,"blood_type":"A neg","count(*)":4},{"component_cd":30,"blood_type":"A pos","count(*)":676},{"component_cd":30,"blood_type":"AB neg","count(*)":1},{"component_cd":30,"blood_type":"AB pos","count(*)":176},{"component_cd":30,"blood_type":"B pos","count(*)":838},{"component_cd":30,"blood_type":"O neg","count(*)":3},{"component_cd":30,"blood_type":"O pos","count(*)":1552},{"component_cd":40,"blood_type":"A neg","count(*)":2},{"component_cd":40,"blood_type":"A pos","count(*)":681},{"component_cd":40,"blood_type":"AB neg","count(*)":1},{"component_cd":40,"blood_type":"AB pos","count(*)":160},{"component_cd":40,"blood_type":"B pos","count(*)":810},{"component_cd":40,"blood_type":"O neg","count(*)":2},{"component_cd":40,"blood_type":"O pos","count(*)":1332}],"distance":{"time":"0 Minutes","distance":0}},{"facility_cd":"13006","facility_name":"Philippine Blood Center","bloods":[{"component_cd":20,"blood_type":"A pos","count(*)":284},{"component_cd":20,"blood_type":"AB pos","count(*)":45},{"component_cd":20,"blood_type":"B pos","count(*)":186},{"component_cd":20,"blood_type":"O pos","count(*)":534},{"component_cd":30,"blood_type":"A neg","count(*)":8},{"component_cd":30,"blood_type":"A pos","count(*)":597},{"component_cd":30,"blood_type":"AB neg","count(*)":1},{"component_cd":30,"blood_type":"AB pos","count(*)":207},{"component_cd":30,"blood_type":"B neg","count(*)":4},{"component_cd":30,"blood_type":"B pos","count(*)":556},{"component_cd":30,"blood_type":"O neg","count(*)":15},{"component_cd":30,"blood_type":"O pos","count(*)":1120},{"component_cd":40,"blood_type":"A neg","count(*)":3},{"component_cd":40,"blood_type":"A pos","count(*)":726},{"component_cd":40,"blood_type":"AB pos","count(*)":104},{"component_cd":40,"blood_type":"B neg","count(*)":1},{"component_cd":40,"blood_type":"B pos","count(*)":699},{"component_cd":40,"blood_type":"O neg","count(*)":6},{"component_cd":40,"blood_type":"O pos","count(*)":1140},{"component_cd":50,"blood_type":"A pos","count(*)":139},{"component_cd":50,"blood_type":"AB pos","count(*)":68},{"component_cd":50,"blood_type":"B pos","count(*)":53},{"component_cd":50,"blood_type":"O neg","count(*)":2},{"component_cd":50,"blood_type":"O pos","count(*)":278}],"distance":{"time":"34.63 Hours","distance":1317.65}},{"facility_cd":"13109","facility_name":"The Medical City","bloods":[{"component_cd":20,"blood_type":"A pos","count(*)":10},{"component_cd":20,"blood_type":"B pos","count(*)":10},{"component_cd":20,"blood_type":"O pos","count(*)":30}],"distance":{"time":"0 Minutes","distance":0}},{"facility_cd":"13112","facility_name":"Philippine General Hospital","bloods":[],"distance":{"time":"34.48 Hours","distance":1310.28}},{"facility_cd":"13126","facility_name":"Victor Potenciano Medical Center","bloods":[{"component_cd":10,"blood_type":"A pos","count(*)":3},{"component_cd":10,"blood_type":"B pos","count(*)":3},{"component_cd":10,"blood_type":"O pos","count(*)":5},{"component_cd":20,"blood_type":"A pos","count(*)":26},{"component_cd":20,"blood_type":"AB pos","count(*)":8},{"component_cd":20,"blood_type":"B pos","count(*)":26},{"component_cd":20,"blood_type":"O pos","count(*)":52},{"component_cd":30,"blood_type":"A pos","count(*)":15},{"component_cd":30,"blood_type":"AB pos","count(*)":5},{"component_cd":30,"blood_type":"B pos","count(*)":15},{"component_cd":30,"blood_type":"O pos","count(*)":30},{"component_cd":40,"blood_type":"A pos","count(*)":23},{"component_cd":40,"blood_type":"AB pos","count(*)":7},{"component_cd":40,"blood_type":"B pos","count(*)":25},{"component_cd":40,"blood_type":"O pos","count(*)":48}],"distance":{"time":"34.41 Hours","distance":1309.97}},{"facility_cd":"13138","facility_name":"De Los Santos Medical Center","bloods":[{"component_cd":10,"blood_type":"A pos","count(*)":21},{"component_cd":10,"blood_type":"AB pos","count(*)":2},{"component_cd":10,"blood_type":"B pos","count(*)":7},{"component_cd":10,"blood_type":"O pos","count(*)":2},{"component_cd":20,"blood_type":"B pos","count(*)":1}],"distance":{"time":"34.63 Hours","distance":1314.21}},{"facility_cd":"13148","facility_name":"Dr. Fe Del Mundo Medical Center","bloods":[{"component_cd":20,"blood_type":"A pos","count(*)":1},{"component_cd":20,"blood_type":"AB pos","count(*)":2},{"component_cd":20,"blood_type":"B pos","count(*)":1},{"component_cd":20,"blood_type":"O pos","count(*)":4},{"component_cd":30,"blood_type":"A pos","count(*)":1},{"component_cd":30,"blood_type":"AB pos","count(*)":2},{"component_cd":30,"blood_type":"B pos","count(*)":1},{"component_cd":30,"blood_type":"O pos","count(*)":4},{"component_cd":40,"blood_type":"AB pos","count(*)":2},{"component_cd":40,"blood_type":"B pos","count(*)":1},{"component_cd":40,"blood_type":"O pos","count(*)":3}],"distance":{"time":"34.65 Hours","distance":1314.52}},{"facility_cd":"13151","facility_name":"FEU-NICANOR REYES MEDICAL FOUNDATION","bloods":[{"component_cd":10,"blood_type":"A pos","count(*)":1},{"component_cd":10,"blood_type":"B pos","count(*)":1},{"component_cd":20,"blood_type":"A pos","count(*)":2},{"component_cd":20,"blood_type":"B pos","count(*)":1},{"component_cd":30,"blood_type":"B pos","count(*)":1},{"component_cd":40,"blood_type":"B pos","count(*)":1}],"distance":{"time":"34.98 Hours","distance":1328.12}},{"facility_cd":"17001","facility_name":"Region 4b Dry Run Medical Center","bloods":[],"distance":{"time":"55.51 Hours","distance":1846.24}}]
            this.loadDistances()
            // this.loadFacilities(()=>{
            //     this.loadDistances()
            // })
        },
        methods : {
            applyDetails(details){
                this.details = details
                this.search++
            },
            loadFacilities(callback){
                let {facility} = this.$session.get('user')
                this.loading = true;
                this.$http.get(this,'networking/facilities/'+facility.facility_name)
                .then(({data}) => {
                    this.facilities = data;
                    this.loading = false;
                    callback()
                })
            },
            loadDistances(){
                this.loading = true;
                let {facility : {facility_name}} = this.$session.get('user')
                let i = this.facilities.length
                this.facilities.map(f=>{
                    this.$http.post(this,'networking/distance',{
                        from : facility_name,
                        to : f.facility_name
                    })
                    .then(({data}) => {
                        i--;
                        f.distance = data
                        if(i <= 0){
                            this.loading = false
                        }
                    })
                })
            }
        }
    }
</script>