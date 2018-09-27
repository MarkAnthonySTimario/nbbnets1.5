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
            <li class="dropdown" v-if="user.ulevel == -1 ||  user.ulevel == 1 || user.ulevel == 3 || user.ulevel == 7">
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

            <li class="dropdown" v-if="user.ulevel == -1 ||  user.ulevel == 1 || user.ulevel == 4 || user.ulevel == 7">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Blood Unit
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><router-link to="/RegisterUnit">Register Blood Unit</router-link></li>
                <li><router-link to="/Typing">Blood Typing</router-link></li>
                <li><router-link to="/Processing">Blood Processing</router-link></li>
                <li><router-link to="/Testing">Blood Testing</router-link></li>
                <li class="dropdown-submenu" v-if="user">
                  <a  v-show="user.facility.nat || user.facility.antibody ||user.facility.zika" href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Additional Testing</a>
                  <ul class="dropdown-menu">
                      <li><router-link to="/Testing/DonorAntibodyScreening">Donor Antibody Screening</router-link></li>
                      <li><router-link to="/Testing/NucliecAcidTesting">Nucliec Acid Testing</router-link></li>
                      <li><router-link to="/Testing/Zika">Zika</router-link></li>
                  </ul>
                </li>
                <li><router-link to="/ForConfirmatory">For Confirmatory Reactive Units</router-link></li>
                <li><router-link to="/Discard">Blood Discard</router-link></li>
                <li><router-link to="/Label">Blood Label</router-link></li>
                <li><router-link to="/Reprint">Label Reprint</router-link></li>
                <li><router-link to="/ShareUnscreenedUnits">Share Unscreened Units</router-link></li>
              </ul>
            </li>

            <li class="dropdown" v-if="user.ulevel == -1 ||  user.ulevel == 1 || user.ulevel == 4 || user.ulevel == 5 || user.ulevel == 6 || user.ulevel == 7">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Blood Stocks
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><router-link to="/ReleaseToInventory">Release to Inventory</router-link></li>
                <li><router-link to="/AvailableStocks">Available Blood Stocks</router-link></li>
                <li><router-link to="/StatusOfInventory">Inventory Status</router-link></li>
                <li><router-link to="/BloodBankNetworking">Blood Bank Networking</router-link></li>
                <li><router-link to="/ServeNetworking">Serve Blood Request</router-link></li>
                <li><router-link to="/EmergencyPool">Emergency Pool</router-link></li>
                <!-- <li><router-link to="/Transfusion">Transfusion</router-link></li>
                <li><router-link to="/BRM">Releasing</router-link></li> -->
              </ul>
            </li>

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Reports
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <!-- <li><router-link to="/Auc">Auc Report</router-link></li>
                <li><router-link to="/Auc/Report1">Auc Report 1</router-link></li> -->
                <li><router-link to="/BSI">BSI Report</router-link></li>
                <li><router-link to="/MBDReport">Agency Report</router-link></li>
                <monthly-report-group-links></monthly-report-group-links>
                <li><router-link to="/PrintableForms">Printable Forms</router-link></li>
                <!-- <li><router-link to="/Census">Census</router-link></li> -->
                <!-- <li><router-link to="/TransfusionReport">Transfusion Report</router-link></li> -->
              </ul>

            </li>

             <li class="dropdown" v-if="user.ulevel == -1 ||  user.ulevel == 1 || user.ulevel == 1 ||user.ulevel == -1">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Admistrations
                <span class="caret"></span>
              </a>

              <ul class="dropdown-menu">
                <li><router-link to="/ManageUsers">Manage Users</router-link></li>
                <li><router-link to="/FacilityLabelTemplate">Blood Bag Template</router-link></li>
                <li><router-link to="/RegisteredStickers">Registered Stickers</router-link></li>
              </ul>
            
            </li>
          </ul>
          
          <p class="navbar-text" v-if="!guest" style="font-size:12px;color:#fff;padding-left:2em;">
            Logged in as : {{user.user_fname}} {{user.user_mname}} {{user.user_lname}}<br/>{{user.level.userlevelname}}
          </p>
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
  props : ['current'],
  data(){
    return {
      user : this.$session.get('user')
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
      this.user = !this.guest ? this.$session.get('user') : null;
      // let user = this.$session.get('user');
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

.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}

.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}
</style>

