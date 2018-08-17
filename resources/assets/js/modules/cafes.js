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
        cafeLoadStatus: 0
    },
    /**
     * Defines the actions used to retrieve the data.
     */
    actions: {
        loadCafes( { commit } ){
            commit( 'setCafesLoadStatus', 1 );

            CafeAPI.getCafes()
                .then( function( response ){
                    commit( 'setCafes', response.data );
                    commit( 'setCafesLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setCafes', [] );
                    commit( 'setCafesLoadStatus', 3 );
                });
        },

        loadCafe( { commit }, data ){
            commit( 'setCafeLoadStatus', 1 );

            CafeAPI.getCafe( data.id )
                .then( function( response ){
                    commit( 'setCafe', response.data );
                    commit( 'setCafeLoadStatus', 2 );
                })
                .catch( function(){
                    commit( 'setCafe', {} );
                    commit( 'setCafeLoadStatus', 3 );
                });

        }
    },
    /**
     * Defines the mutations used
     */
    mutations: {
        setCafesLoadStatus( state, status ){
            state.cafesLoadStatus = status;
        },

        setCafes( state, cafes ){
            state.cafes = cafes;
        },

        setCafeLoadStatus( state, status ){
            state.cafeLoadStatus = status;
        },

        setCafe( state, cafe ){
            state.cafe = cafe;
        }
    },
    /**
     * Defines the getters used by the module
     */
    getters: {
        getCafesLoadStatus( state ){
            return state.cafesLoadStatus;
        },

        getCafes( state ){
            return state.cafes;
        },

        getCafeLoadStatus( state ){
            return state.cafeLoadStatus;
        },

        getCafe( state ){
            return state.cafe;
        }
    }
};