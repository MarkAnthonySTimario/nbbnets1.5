import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './components/Home.vue';
import ChangePassword from './components/Home/ChangePassword.vue';
import AgencyManger from './components/DonorAndDonation/Agency/Manager.vue';
import AgencyInfo from './components/DonorAndDonation/Agency/Info.vue';
import AgencyCreate from './components/DonorAndDonation/Agency/Create.vue';
import AgencyEdit from './components/DonorAndDonation/Agency/Edit.vue';

import MBD from './components/DonorAndDonation/MBD/List.vue';
import MBDManager from './components/DonorAndDonation/MBD/Manager.vue';
import MBDCreate from './components/DonorAndDonation/MBD/Create.vue';
import MBDAddDonor from './components/DonorAndDonation/MBD/SelectDonor.vue';
import MBDNewDonor from './components/DonorAndDonation/Donor/selector/Create.vue';
import MBDUpdateDonor from './components/DonorAndDonation/Donor/selector/Update.vue';
import MBDDonorInfo from './components/DonorAndDonation/Donor/selector/Info.vue';

import DonorVerifier from './components/DonorAndDonation/Donor/Verifier.vue';
import DonorProfile from './components/DonorAndDonation/Donor/Info.vue';
import DonorUpdate from './components/DonorAndDonation/Donor/Update.vue';
import DonorCreate from './components/DonorAndDonation/Donor/Create.vue';

import WalkinCreate from './components/DonorAndDonation/Walk-in/Create.vue';
import Walkin from './components/DonorAndDonation/Walk-in/List.vue';

import RegisterUnit from './components/BloodUnit/RegisterUnit.vue';
import Typing from './components/BloodUnit/Typing.vue';
import Processing from './components/BloodUnit/Processing.vue';
import Testing from './components/BloodUnit/Testing.vue';
import ForConfirmatory from './components/BloodUnit/ForConfirmatory.vue';
import Discard from './components/BloodUnit/Discard.vue';
import Labeling from './components/BloodUnit/Labeling.vue';

import Templates from './components/Administration/Labels.vue';

import MBDReport from './components/Reports/MBDReport.vue';
import DonationTypesReport from './components/Reports/DonationTypesReport.vue';

import AvailableBloodStocks from './components/StockManagement/AvailableBloodStocks.vue';

import FacilityLabelTemplate from './components/Administration/FacilityLabelTemplate.vue';
import ManageFacilities from './components/Administration/ManageFacilities/List.vue';
import RegisterFacility from './components/Administration/ManageFacilities/RegisterFacility.vue';

Vue.use(VueRouter);

export default new VueRouter({
    routes : [
        { path : '/', component : Home },
        { path : '/ChangePassword', component : ChangePassword },

        { path : '/Agency', component : AgencyManger },
        { path : '/Agency/create', component : AgencyCreate },
        { path : '/Agency/:agency_cd', component : AgencyInfo ,  props : true },
        { path : '/Agency/update/:agency_cd', component : AgencyEdit ,  props : true },

        { path : '/MBD', component : MBD },
        { path : '/MBD/create', component : MBDCreate },
        { path : '/MBD/:sched_id', component : MBDManager, props : true },
        { path : '/MBD/:sched_id/SearchDonor', component : MBDAddDonor, props : true },
        { path : '/MBD/:sched_id/:donation_id/SearchDonor', component : MBDAddDonor, props : true },
        { path : '/MBD/:sched_id/donor/create', component : MBDNewDonor, props : true },
        { path : '/MBD/:sched_id/donor/:seqno/update', component : MBDUpdateDonor, props : true },
        { path : '/MBD/:sched_id/donor/:seqno', component : MBDDonorInfo, props : true },
        { path : '/MBD/:sched_id/AddDonor/:donation_id/donor/:seqno', component : MBDDonorInfo, props : true },

        { path : '/Verifier', component : DonorVerifier },
        { path : '/donor/create', component : DonorCreate },
        { path : '/donor/:seqno', component : DonorProfile, props : true },
        { path : '/donor/:seqno/update', component : DonorUpdate, props : true },
        
        { path : '/donor/:seqno/donate', component : WalkinCreate, props : true },
        { path : '/Walkin', component : Walkin },
        { path : '/SelectWalkinDonor', component : DonorVerifier},
        { path : '/RegisterUnit', component : RegisterUnit },
        { path : '/Typing', component : Typing },
        { path : '/Processing', component : Processing },
        { path : '/Testing', component : Testing },
        { path : '/ForConfirmatory', component : ForConfirmatory },
        { path : '/Discard', component : Discard },
        { path : '/Label', component : Labeling },
        { path : '/Labels', component : Templates },

        { path : '/MBDReport', component : MBDReport },
        { path : '/Reports/Monthly/DonationTypesReport', component : DonationTypesReport },
        { path : '/AvailableStocks', component : AvailableBloodStocks },
        { path : '/FacilityLabelTemplate', component : FacilityLabelTemplate },
        { path : '/ManageFacilities', component : ManageFacilities },
        { path : '/ManageFacilities/RegisterFacility', component : RegisterFacility },
    ]
})