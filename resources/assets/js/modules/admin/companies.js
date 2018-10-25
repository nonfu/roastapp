/*
|-------------------------------------------------------------------------------
| VUEX modules/admin/companies.js
|-------------------------------------------------------------------------------
| The Vuex data store for the admin companies
*/
import CompaniesAPI from '../../api/admin/companies.js';

export const companies = {
    state: {
        companies: [],
        companiesLoadStatus: 0,

        company: {},
        companyLoadStatus: 0,

        companyEditStatus: 0
    },

    actions: {

        loadAdminCompanies({commit}) {
            commit('setCompaniesLoadStatus', 1);

            CompaniesAPI.getCompanies()
                .then(function (response) {
                    commit('setCompanies', response.data);
                    commit('setCompaniesLoadStatus', 2);
                })
                .catch(function () {
                    commit('setCompanies', []);
                    commit('setCompaniesLoadStatus', 3);
                });
        },

        loadAdminCompany({commit}, data) {
            commit('setCompanyLoadStatus', 1);

            CompaniesAPI.getCompany(data.id)
                .then(function (response) {
                    commit('setCompany', response.data);
                    commit('setCompanyLoadStatus', 2);
                })
                .catch(function () {
                    commit('setCompany', {});
                    commit('setCompanyLoadStatus', 3);
                });
        },

        updateAdminCompany({commit}, data) {
            commit('setCompanyEditStatus', 1);

            CompaniesAPI.putUpdateCompany(data.id, data.name, data.type, data.website, data.instagram_url, data.facebook_url, data.twitter_url, data.subscription, data.owners, data.deleted)
                .then(function (response) {
                    commit('setCompany', response.data);
                    commit('setCompanyEditStatus', 2);
                })
                .catch(function () {
                    commit('setCompanyEditStatus', 3);
                });
        }
    },

    mutations: {
        setCompaniesLoadStatus(state, status) {
            state.companiesLoadStatus = status;
        },

        setCompanies(state, companies) {
            state.companies = companies;
        },

        setCompanyLoadStatus(state, status) {
            state.companyLoadStatus = status;
        },

        setCompany(state, company) {
            state.company = company;
        },

        setCompanyEditStatus(state, status) {
            state.companyEditStatus = status;
        }
    },

    getters: {
        getCompanies(state) {
            return state.companies;
        },

        getCompaniesLoadStatus(state) {
            return state.companiesLoadStatus;
        },

        getCompany(state) {
            return state.company;
        },

        getCompanyLoadStatus(state) {
            return state.companyLoadStatus;
        },

        getCompanyEditStatus(state) {
            return state.companyEditStatus;
        }
    }
};