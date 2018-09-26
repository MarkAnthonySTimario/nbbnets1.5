<template>
  <div class="table-responsive">
      <div class="panel-body" v-if="loading" >
        <loadingInline label="Loading Inventory"></loadingInline>  
      </div>
      <table class="table table-condensed table-striped table-bordered" v-if="!loading">
          <thead>
              <tr class="text-center">
                  <th></th>
                  <th v-for="(name,code) in components" :key="code" class="text-center" width="100">{{name | abbrev}}</th>
                  <th nowrap>TOTAL</th>
              </tr>
          </thead>
          <tbody class="text-center">
              <tr v-for="bt in blood_types" :key="bt" style="font-size:12px;">
                  <td nowrap>{{bt}}</td>
                  <td v-for="(name,code) in components" :key="code" :name="name" :component="code">{{getStock(code,bt)}}</td>
                  <td>{{getStocksOfBloodType(bt)}}</td>
              </tr>
          </tbody>
          <tfoot>
              <tr style="font-size:12px;">
                  <td>TOTAL</td>
                  <td v-for="(name,code) in components" :key="code" class="text-center">{{getStocksOfComponent(code)}}</td>
                  <td class="text-center">{{getStocksTotal()}}</td>
              </tr>
          </tfoot>
      </table>
  </div>
</template>

<script>

export default {
  filters : this.$filters,
  data(){
      let {components,blood_types} = this.$session.getAll();
      
      return {
          loading : true,
          components, 
          blood_types, 
          stocks : []
      }
  },
  mounted(){
      this.fetchData();
  },
  methods : {
      fetchData(){
        this.$http.post(this,"stocks",{
            facility_cd : this.$store.state.user.facility_cd
        })
        .then(({data}) => {
            this.stocks = data;
            this.loading = false;
        }).catch(error => {
            this.$store.state.error = error;
        });
      },
      getStock(c,b){
        
          let x = _.findLast(this.stocks,{
              'blood_type' : b, 'component_cd' : (c*1)
          });

          return x ? x.cnt : 0;
      },
      getStocksOfBloodType(b){
          let stocks = _.filter(this.stocks,{
              'blood_type' : b
          })

          let total = 0
          _.each(stocks,s=>{
              total += s.cnt
          })

          return total
      },
      getStocksOfComponent(c){
          let stocks = _.filter(this.stocks,{
              'component_cd' : (c*1)
          })

          let total = 0
          _.each(stocks,s=>{
              total += s.cnt
          })

          return total
      },
      getStocksTotal(){
          let total = 0
          _.each(this.stocks,s=>{
              total += s.cnt
          })

          return total
      }
  }
}
</script>
