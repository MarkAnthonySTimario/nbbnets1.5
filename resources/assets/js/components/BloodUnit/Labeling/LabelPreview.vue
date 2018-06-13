<template>
  <div id="label">
      <loading v-if="loading"></loading>
      <div class="paper" v-if="!loading">
          <table class="barcode-table">
              <tr>
                  <td width="100"></td>
                  <td class="title">
                      <b>THE MEDICAL CITY</b> 
                      <p>Blood Bank and Transfusion Services</p>
                      <p>Ortigas Avenue, Pasig City, Metro Manila</p>
                      <p>Tel. No. 9881000 local 6128</p>
                  </td>
              </tr>
          </table>
          <table class="barcode-tab" style="border-top:1px solid #000;">
              <tr>
                  <td>
                      <img src="barcode/NVBSP20181231231" width="300" height="50" alt="">
                  </td>
              </tr>
          </table>
          <table class="barcode-table" style="border-top:1px solid #000;">
              <tr>
                  <td rowspan="2" class="abo" width="100"><center>B</center></td>
                  <td rowspan="2" class="rh">
                      <center>Rh(D)<br/>POSITIVE</center>
                  </td>
                  <td class="collection-expiration">
                      Collection Date:<br/>
                      January 06, 2018
                  </td>
              </tr>
              <tr>
                  <td class="collection-expiration">
                      Expiration Date:<br/>
                      January 06, 2019 23:59:00
                  </td>
              </tr>
              <tr>
                  <td colspan="3">FRESH FROZEN PLASMA VOLUME: 150ml</td>
              </tr>
              <tr>
                  <td colspan="2">Human Blood Product</td>
                  <td>Store at -18 to -89 &deg;C</td>
              </tr>
              <tr>
                <td colspan="2">From a volunteer blood donor</td><td >CPDA-1 Anticoagulant</td>
            </tr>
            <tr>
                <td colspan="3"><p>NON-REACTIVE Donation tested for specific markers for HIV 1 &amp; 2, hepatitis B &amp; C, malaria and syphilis, ANTIBODY SCREEN: NEGATIVE</p></td>
            </tr>
          </table>
      </div>
  </div>
</template>

<script>
import printJS from 'print-js';

export default {
  props : ['preview'],
  data(){
      return {
          loading : false
      }
  },
  mounted(){
    //   printJS('label','html');
  }
}
</script>

<style scoped>
.paper{
    background: #e1e1e1;
    border:1px solid #000;
    padding:0px;
    width:356px;
    margin-left:auto;
    margin-right:auto;
}
.barcode-table{
    width:100%;
    font-size: 8px;
}
.barcode-table td{
    border-right:1px solid #000;
    border-bottom:1px solid #000;
}
.title{
    font-size: 10px;
    text-align: center;
    padding:2px;
}
.abo{
    font-size:36px;
    font-weight: bold;
    vertical-align: middle;
}
.rh{
    vertical-align: middle;
}
.collection-expiration{
    font-size:8px;
}
</style>
