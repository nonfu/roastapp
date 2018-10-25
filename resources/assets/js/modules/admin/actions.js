/*
|-------------------------------------------------------------------------------
| VUEX modules/admin/actions.js
|-------------------------------------------------------------------------------
| The Vuex data store for the admin actions
*/
import ActionsAPI from '../../api/admin/actions.js';

export const actions = {
    /*
      Defines the state being monitored for the module.
    */
    state: {
        actions: [],
        actionsLoadStatus: 0,

        actionApproveStatus: 0,
        actionDeniedStatus: 0
    },

    actions: {
        loadAdminActions({commit}) {
            commit('setActionsLoadStatus', 1);

            ActionsAPI.getActions()
                .then(function (response) {
                    commit('setActions', response.data);
                    commit('setActionsLoadStatus', 2);
                })
                .catch(function () {
                    commit('setActions', []);
                    commit('setActionsLoadStatus', 3);
                });
        },

        approveAction({commit, state, dispatch}, data) {
            commit('setActionApproveStatus', 1);

            ActionsAPI.putApproveAction(data.id)
                .then(function (response) {
                    commit('setActionApproveStatus', 2);
                    dispatch('loadAdminActions');
                })
                .catch(function () {
                    commit('setActionApproveStatus', 3);
                });

        },

        denyAction({commit, state, dispatch}, data) {
            commit('setActionDeniedStatus', 1);

            ActionsAPI.putDenyAction(data.id)
                .then(function (response) {
                    commit('setActionDeniedStatus', 2);
                    dispatch('loadAdminActions');
                })
                .catch(function () {
                    commit('setActionDeniedStatus', 3);
                });

        }
    },

    mutations: {
        setActionsLoadStatus(state, status) {
            state.actionsLoadStatus = status;
        },

        setActions(state, actions) {
            state.actions = actions;
        },

        setActionApproveStatus(state, status) {
            state.actionApproveStatus = status;
        },

        setActionDeniedStatus(state, status) {
            state.actionDeniedStatus = status;
        }
    },

    getters: {
        getActions(state) {
            return state.actions;
        },

        getActionsLoadStatus(state) {
            return state.actionsLoadStatus;
        },

        getActionApproveStatus(state) {
            return state.actionApproveStatus;
        },

        getActionDeniedStatus(state) {
            return state.actionDeniedStatus;
        }
    }
};