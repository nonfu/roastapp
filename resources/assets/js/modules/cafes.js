/*
 |-------------------------------------------------------------------------------
 | VUEX modules/cafes.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the cafes
 */

import CafeAPI from '../api/cafe.js';

export const cafes = {
    /**
     * Defines the state being monitored for the module.
     */
    state: {
        cafes: [],
        cafesLoadStatus: 0,

        cafe: {},
        cafeLoadStatus: 0,

        cafeLiked: false,
        cafeLikeActionStatus: 0,
        cafeUnlikeActionStatus: 0,

        cafeAdded: {},
        cafeAddStatus: 0,
        cafeAddText: '',

        cafeEdit: {},
        cafeEditLoadStatus: 0,
        cafeEditStatus: 0,
        cafeEditText: '',

        cafeDeletedStatus: 0,
        cafeDeleteText: '',

        cafesView: 'map'
    },
    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {
        loadCafes({commit, rootState, dispatch}) {
            commit('setCafesLoadStatus', 1);

            CafeAPI.getCafes()
                .then(function (response) {
                    commit('setCafes', response.data);
                    dispatch('orderCafes', {
                        order: rootState.filters.orderBy,
                        direction: rootState.filters.orderDirection
                    });
                    commit('setCafesLoadStatus', 2);
                })
                .catch(function () {
                    commit('setCafes', []);
                    commit('setCafesLoadStatus', 3);
                });
        },

        loadCafe({commit}, data) {
            commit('setCafeLikedStatus', false);
            commit('setCafeLoadStatus', 1);

            CafeAPI.getCafe(data.id)
                .then(function (response) {
                    commit('setCafe', response.data);
                    if (response.data.user_like_count > 0) {
                        commit('setCafeLikedStatus', true);
                    }
                    commit('setCafeLoadStatus', 2);
                })
                .catch(function () {
                    commit('setCafe', {});
                    commit('setCafeLoadStatus', 3);
                });
        },

        addCafe({commit, state, dispatch}, data) {
            commit('setCafeAddStatus', 1);

            CafeAPI.postAddNewCafe(data.company_name, data.company_id, data.company_type, data.subscription, data.website, data.location_name, data.address, data.city, data.state, data.zip, data.brew_methods, data.matcha, data.tea)
                .then(function (response) {
                    if (typeof response.data.cafe_add_pending !== 'undefined') {
                        commit('setCafeAddedText', response.data.cafe_add_pending + ' 审核通过才能添加!');
                    } else {
                        commit('setCafeAddedText', response.data.name + ' 已经添加!');
                    }

                    commit('setCafeAddStatus', 2);
                    commit('setCafeAdded', response.data);

                    dispatch('loadCafes');
                })
                .catch(function () {
                    commit('setCafeAddStatus', 3);
                });
        },

        loadCafeEdit({commit}, data) {
            commit('setCafeEditLoadStatus', 1);

            CafeAPI.getCafeEdit(data.id)
                .then(function (response) {
                    commit('setCafeEdit', response.data);
                    commit('setCafeEditLoadStatus', 2);
                })
                .catch(function () {
                    commit('setCafeEdit', {});
                    commit('setCafeEditLoadStatus', 3);
                });
        },

        editCafe({commit, state, dispatch}, data) {
            commit('setCafeEditStatus', 1);

            CafeAPI.putEditCafe(data.id, data.company_name, data.company_id, data.company_type, data.subscription, data.website, data.location_name, data.address, data.city, data.state, data.zip, data.brew_methods, data.matcha, data.tea)
                .then(function (response) {
                    if (typeof response.data.cafe_updates_pending !== 'undefined') {
                        commit('setCafeEditText', response.data.cafe_updates_pending + ' 审核通过才能更新!');
                    } else {
                        commit('setCafeEditText', response.data.name + ' 已经编辑成功!');
                    }

                    commit('setCafeEditStatus', 2);

                    dispatch('loadCafes');
                })
                .catch(function (error) {
                    commit('setCafeEditStatus', 3);
                });
        },

        deleteCafe({commit, state, dispatch}, data) {
            commit('setCafeDeleteStatus', 1);

            CafeAPI.deleteCafe(data.id)
                .then(function (response) {

                    if (typeof response.data.cafe_delete_pending !== 'undefined') {
                        commit('setCafeDeletedText', response.data.cafe_delete_pending + ' 审核通过才能删除!');
                    } else {
                        commit('setCafeDeletedText', '咖啡店删除成功!');
                    }

                    commit('setCafeDeleteStatus', 2);

                    dispatch('loadCafes');
                })
                .catch(function () {
                    commit('setCafeDeleteStatus', 3);
                });
        },

        likeCafe({commit, state}, data) {
            commit('setCafeLikeActionStatus', 1);

            CafeAPI.postLikeCafe(data.id)
                .then(function (response) {
                    commit('setCafeLikedStatus', true);
                    commit('setCafeLikeActionStatus', 2);
                })
                .catch(function () {
                    commit('setCafeLikeActionStatus', 3);
                });
        },

        unlikeCafe({commit, state}, data) {
            commit('setCafeUnlikeActionStatus', 1);

            CafeAPI.deleteLikeCafe(data.id)
                .then(function (response) {
                    commit('setCafeLikedStatus', false);
                    commit('setCafeUnlikeActionStatus', 2);
                })
                .catch(function () {
                    commit('setCafeUnlikeActionStatus', 3);
                });
        },

        clearLikeAndUnlikeStatus({commit}, data) {
            commit('setCafeLikeActionStatus', 0);
            commit('setCafeUnlikeActionStatus', 0);
        },

        changeCafesView({commit, state, dispatch}, view) {
            commit('setCafesView', view);
        },

        orderCafes({commit, state, dispatch}, data) {
            let localCafes = state.cafes;

            switch (data.order) {
                case 'name':
                    localCafes.sort(function (a, b) {
                        if (data.direction === 'desc') {
                            return ((a.company.name === b.company.name) ? 0 : ((a.company.name < b.company.name) ? 1 : -1));
                        } else {
                            return ((a.company.name === b.company.name) ? 0 : ((a.company.name > b.company.name) ? 1 : -1));
                        }
                    });
                    break;
                case 'most-liked':
                    localCafes.sort(function (a, b) {
                        if (data.direction === 'desc') {
                            return ((a.likes_count === b.likes_count) ? 0 : ((a.likes_count < b.likes_count) ? 1 : -1));
                        } else {
                            return ((a.likes_count === b.likes_count) ? 0 : ((a.likes_count > b.likes_count) ? 1 : -1));
                        }
                    });
                    break;
            }

            commit('setCafes', localCafes);
        }
    },
    /**
     * Defines the mutations used
     */
    mutations: {
        setCafesLoadStatus(state, status) {
            state.cafesLoadStatus = status;
        },

        setCafes(state, cafes) {
            state.cafes = cafes;
        },

        setCafeLoadStatus(state, status) {
            state.cafeLoadStatus = status;
        },

        setCafe(state, cafe) {
            state.cafe = cafe;
        },

        setCafeAddStatus(state, status) {
            state.cafeAddStatus = status;
        },

        setCafeAdded(state, cafe) {
            state.cafeAdded = cafe;
        },

        setCafeAddedText(state, text) {
            state.cafeAddText = text;
        },

        setCafeLikedStatus(state, status) {
            state.cafeLiked = status;
        },

        setCafeLikeActionStatus(state, status) {
            state.cafeLikeActionStatus = status;
        },

        setCafeUnlikeActionStatus(state, status) {
            state.cafeUnlikeActionStatus = status;
        },

        setCafesView(state, view) {
            state.cafesView = view
        },

        setCafeEdit(state, cafe) {
            state.cafeEdit = cafe;
        },

        setCafeEditStatus(state, status) {
            state.cafeEditStatus = status;
        },

        setCafeEditText(state, text) {
            state.cafeEditText = text;
        },

        setCafeEditLoadStatus(state, status) {
            state.cafeEditLoadStatus = status;
        },

        setCafeDeleteStatus(state, status) {
            state.cafeDeletedStatus = status;
        },

        setCafeDeletedText(state, text) {
            state.cafeDeleteText = text;
        }
    },
    /**
     * Defines the getters used by the module
     */
    getters: {
        getCafesLoadStatus(state) {
            return state.cafesLoadStatus;
        },

        getCafes(state) {
            return state.cafes;
        },

        getCafeLoadStatus(state) {
            return state.cafeLoadStatus;
        },

        getCafe(state) {
            return state.cafe;
        },

        getCafeAddStatus(state) {
            return state.cafeAddStatus;
        },

        getAddedCafe(state) {
            return state.cafeAdded;
        },

        getCafeAddText(state) {
            return state.cafeAddText;
        },

        getCafeLikedStatus(state) {
            return state.cafeLiked;
        },

        getCafeLikeActionStatus(state) {
            return state.cafeLikeActionStatus;
        },

        getCafeUnlikeActionStatus(state) {
            return state.cafeUnlikeActionStatus;
        },

        getCafesView(state) {
            return state.cafesView;
        },

        getCafeEdit(state) {
            return state.cafeEdit;
        },

        getCafeEditStatus(state) {
            return state.cafeEditStatus;
        },

        getCafeEditText(state) {
            return state.cafeEditText;
        },

        getCafeEditLoadStatus(state) {
            return state.cafeEditLoadStatus;
        },

        getCafeDeletedStatus(state) {
            return state.cafeDeletedStatus;
        },

        getCafeDeletedText(state) {
            return state.cafeDeleteText;
        }
    }
};