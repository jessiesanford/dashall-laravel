
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/ui/widgets/autocomplete.js';
import 'jquery-ui/ui/widgets/draggable.js';
import 'jquery-ui/ui/widgets/droppable.js';

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

// account vues
Vue.component('login-form', require('./components/loginForm.vue'));
Vue.component('change-email-form', require('./components/userChangeEmailForm.vue'));
Vue.component('change-password-form', require('./components/userChangePasswordForm.vue'));
Vue.component('change-phone-form', require('./components/userChangePhoneForm.vue'));
Vue.component('account-info-form', require('./components/accountInfoForm.vue'));

// manage vues
Vue.component('manage-filter-orders', require('./components/manageFilterOrders.vue'));

require('./order_address');
require('./order');
require('./account');
require('./driver');
require('./schedule');
require('./manage');

// require('./transactions');
// require('./admin_drivers');
// require('./admin_functions');
// require('./admin_orders');
// require('./admin_settings');
// put any app code here