/*
  Imports the Roast API URL from the config.
*/
import {ROAST_CONFIG} from '../../config.js';

export default {
    /*
      GET   /api/v1/admin/actions
    */
    getActions: function () {
        return axios.get(ROAST_CONFIG.API_URL + '/admin/actions');
    },

    /*
      PUT   /api/v1/admin/actions/{action}/approve
    */
    putApproveAction: function (id) {
        return axios.put(ROAST_CONFIG.API_URL + '/admin/actions/' + id + '/approve');
    },

    /*
      PUT   /api/v1/admin/actions/{action}/deny
    */
    putDenyAction: function (id) {
        return axios.put(ROAST_CONFIG.API_URL + '/admin/actions/' + id + '/deny');
    }
}