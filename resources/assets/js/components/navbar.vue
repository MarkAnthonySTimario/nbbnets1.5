<template>
  <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="./" class="navbar-brand">NBBNETS</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav" v-if="!guest">
            <li><router-link to="/">Home</router-link></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Donor & Donation
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><router-link to="/Agency">Manage Agency</router-link></li>
                <li><router-link to="/Verifier">Donor Status Verifier</router-link></li>
                <!-- <li><router-link to="/Verifier">Manage Donors</router-link></li> -->
                <li><router-link to="/MBD">Mobile Blood Donation</router-link></li>
                <li><router-link to="/Walkin">Walk-in Donation</router-link></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Blood Unit
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><router-link to="/RegisterUnit">Register Blood Unit</router-link></li>
                <li><router-link to="/Typing">Blood Typing</router-link></li>
                <li><router-link to="/Processing">Blood Processing</router-link></li>
                <li><router-link to="/Testing">Blood Testing</router-link></li>
                <li><router-link to="/ForConfirmatory">For Confirmatory Reactive Units</router-link></li>
                <li><router-link to="/Discard">Blood Discard</router-link></li>
                <li><router-link to="/Label">Blood Label</router-link></li>
                <li><router-link to="/Reprint">Label Reprint</router-link></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Blood Stocks
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><router-link to="/ReleaseToInventory">Release to Inventory</router-link></li>
                <li><router-link to="/AvailableStocks">Available Blood Stocks</router-link></li>
                <li><router-link to="/StatusOfInventory">Inventory Status</router-link></li>
                <li><router-link to="/Transfusion">Transfusion</router-link></li>
                <li><router-link to="/BRM">Releasing</router-link></li>
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Reports
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><router-link to="/Auc">Auc Report</router-link></li>
                <li><router-link to="/BSI">BSI Report</router-link></li>
                <monthly-report-group-links></monthly-report-group-links>
                <li><router-link to="/Census">Census</router-link></li>
                <li><router-link to="/MBDReport">Agency Report</router-link></li>
                <li><router-link to="/TransfusionReport">Transfusion Report</router-link></li>
              </ul>

            </li>

             <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Admistrations
                <span class="caret"></span>
              </a>

              <ul class="dropdown-menu">
                <li><router-link to="/ManageFacilities/RegisterFacility">Register Facility</router-link></li>
                <li><router-link to="/ManageFacilities">Manage Facilities</router-link></li>
                <li><router-link to="/Labels">Manage Labels</router-link></li>
                <li><router-link to="/FacilityLabelTemplate">Blood Bag Template</router-link></li>
              </ul>
            
            </li>
            
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li v-if="!guest">
              <notification-toggle></notification-toggle>
              <chat-box></chat-box>
            </li>
            <li v-if="!guest"><a href="#" @click.prevent="logout">Logout</a></li>
          </ul>

        </div>
      </div>
    </div>
</template>

<script>
import NotificationToggle from './Chat/NotificationToggle.vue';
import ChatBox from './Chat/Chatbox.vue';
import MonthlyReportGroupLinks from './navbar/MonthlyReportGroupLinks.vue';

export default {
  components : {NotificationToggle,ChatBox,MonthlyReportGroupLinks},
  data(){
    return {
      user : null
    }
  },
  methods : {
    logout(){
      this.$session.clear();
      this.$store.commit('USER',null);
      this.$store.commit('TOKEN',null);
      window.location.reload();
    }
  },
  computed : {
    guest(){
      return !this.$store.state.user;
    }
  },
  watch : {
    guest(){
      let user = !this.guest ? this.$store.state.user : this.$session.get('user');
    }
  }
}
</script>

<style scoped>
@media (min-width: 768px) and (max-width: 1000px) {
    .navbar-header {
        float: none;
    }
    .navbar-left,.navbar-right {
        float: none !important;
    }
    .navbar-toggle {
        display: block;
    }
    .navbar-collapse {
        border-top: 1px solid transparent;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
    }
    .navbar-fixed-top {
        top: 0;
        border-width: 0 0 1px;
    }
    .navbar-collapse.collapse {
        display: none!important;
    }
    .navbar-nav {
        float: none!important;
        margin-top: 7.5px;
    }
    .navbar-nav>li {
        float: none;
    }
    .navbar-nav>li>a {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .collapse.in{
        display:block !important;
    }
}
</style>

