<template>
  <div>
      <button class="btn btn-primary btn-lg" @click="getDistances">Get Distances</button>
      <table class="table table-bordered table-striped">
          <thead>
              <tr>
                  <th></th>
                  <th>Code</th>
                  <th>Agency Name</th>
                  <!-- <th>Barangay</th>
                  <th>City</th>
                  <th>Province</th> -->
                  <th></th>
              </tr>
          </thead>
          <tbody>
              <tr v-for="(agency,i) in agencies" :key="agency.agency_cd">
                  <td>{{i+1}}</td>
                  <td @click="copyToClipboard2(agency)">{{agency.agency_cd}}
                      <!-- <button class="btn btn-xs btn-success" @click="remote(agency,true)">0</button>
                      <button class="btn btn-xs btn-danger" @click="remote(agency,false)">1</button> -->
                      <button class="btn btn-xs btn-danger" @click="getDistance(agency)">Get Distance</button>
                      <button class="btn btn-xs btn-success" @click="getDistance2(agency)">GD</button>
                      <!-- <iframe :src="getUrl(agency)" frameborder="0" width="400" height="400"></iframe> -->
                  </td>
                  <!-- <td>{{agency.agency_name}}</td> -->
                  <td>{{agency.agency_name + ' ' + (agency.barangay ? agency.barangay.bgyname : null) + ' ' + agency.city.cityname + ' ' + agency.province.provname}}</td>
                  <!-- <td>{{agency.barangay ? agency.barangay.bgyname : null}} {{agency.adg_bgy}}</td>
                  <td>{{agency.city.cityname}}</td>
                  <td>{{agency.province.provname}}</td> -->
                  <!-- <td width='150'></td> -->
                  <!-- <td style="font-size:8px;width:200px;" @click="copyToClipboard(agency)">{{agency.agency_name}} {{agency.barangay ? agency.barangay.bgyname : null}} {{agency.city.cityname}} {{agency.province.provname}}</td> -->
                  <td>
                      <div class="input-group">
                        <input type="number" class="form-control input-sm" v-model="agency.distance">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-sm" @click="applyDistance(agency)">OK</button>
                        </div>
                      </div>
                  </td>
              </tr>
          </tbody>
      </table>
  </div>
</template>

<script>
export default {
  data(){
      return {
          agencies : []
      }
  },
  created(){
    this.$http.get(this,'auc').then(({data})=>{
        this.agencies = data;
    })
  },
  methods : {
    copyToClipboard : agency => {
        let brgy = agency.barangay ? agency.barangay.bgyname : '';
        let str = agency.agency_name +' '+ brgy +' '+agency.city.cityname+' '+agency.province.provname;
        const el = document.createElement('textarea');
        el.value = str;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    },
    copyToClipboard2 : agency => {
        let str = agency.agency_cd;
        const el = document.createElement('textarea');
        el.value = str;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    },
    remote(agency,noncityproper){
        this.$http.post(this,'auc/remote',{agency_cd:agency.agency_cd,noncityproper}).then(({data})=>{
            window.location.reload();
        })
    },
    getDistances(){
        this.agencies.map(agency => {
            this.getDistance2(agency);
        })
    },
    getDistance(agency){
        let brgy = agency.barangay ? agency.barangay.bgyname : ''
        let address = agency.agency_name + ' ' + brgy + ' ' + agency.city.cityname + ' ' + agency.province.provname;
        address = encodeURI(address)
        // window.open('https://www.mapdevelopers.com/distance_from_to.php?&from=Philippine%20Blood%20Center%2C%20Quezon%20Avenue%2C%20Diliman%2C%20Quezon%20City%2C%20Metro%20Manila&to='+address,'_blank')
        window.open('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=Davao+Blood+Center&destinations='+address,'_blank')
    },
    getDistance2(agency){
        let brgy = agency.barangay ? agency.barangay.bgyname : ''
        let address = agency.agency_name + ' ' + brgy + ' ' + agency.city.cityname + ' ' + agency.province.provname;
        // address = 'https://www.mapdevelopers.com/distance_from_to.php?&from=Philippine%20Blood%20Center%2C%20Quezon%20Avenue%2C%20Diliman%2C%20Quezon%20City%2C%20Metro%20Manila&to='+address;
        this.$http.post(this,'auc/distance2',{
            address,agency_cd : agency.agency_cd
        },({data}) => {
            console.log(data)
        })
    },
    getUrl(agency){
        let brgy = agency.barangay ? agency.barangay.bgyname : ''
        let address = agency.agency_name + ' ' + brgy + ' ' + agency.city.cityname + ' ' + agency.province.provname;
        address = encodeURI(address)
        return 'https://www.mapdevelopers.com/distance_from_to.php?&from=Philippine%20Blood%20Center%2C%20Quezon%20Avenue%2C%20Diliman%2C%20Quezon%20City%2C%20Metro%20Manila&to='+address
        // this.$http.post(this,'auc/distance2',{
        //     url : 'https://www.mapdevelopers.com/distance_from_to.php?&from=Philippine%20Blood%20Center%2C%20Quezon%20Avenue%2C%20Diliman%2C%20Quezon%20City%2C%20Metro%20Manila&to='+address
        // })
        // .then(({data}) => {
        //     console.log(data)
        // })
    },
    applyDistance(agency){
        if(!agency.distance){
            return
        }
        this.$http.post(this,'auc/distance',{
            agency_cd : agency.agency_cd,
            distance : agency.distance
        }).then(({data}) => {
            // window.location.reload()
        })
    }
  }
}
</script>
