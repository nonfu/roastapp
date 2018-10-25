<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#app-layout {
        div.show-filters {
            height: 90px;
            width: 23px;
            position: absolute;
            left: 0px;
            background-color: white;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            line-height: 90px;
            top: 50%;
            cursor: pointer;
            margin-top: -45px;
            z-index: 9;
            text-align: center;
        }
    }
</style>

<template>
    <div id="app-layout">
        <div class="show-filters" v-show="( !showFilters && cafesView === 'map' )" v-on:click="toggleShowFilters()">
            <img src="/storage/img/grey-right.svg"/>
        </div>

        <success-notification></success-notification>
        <error-notification></error-notification>

        <navigation></navigation>

        <router-view></router-view>

        <login-modal></login-modal>

        <filters></filters>

        <pop-out></pop-out>
    </div>
</template>

<script>

    import Navigation from '../components/global/Navigation.vue';
    import LoginModal from '../components/global/LoginModal.vue';
    import SuccessNotification from '../components/global/SuccessNotification.vue';
    import ErrorNotification from '../components/global/ErrorNotification.vue';
    import Filters from '../components/global/Filters.vue';
    import PopOut from '../components/global/PopOut.vue';

    export default {
        components: {
            Navigation,
            LoginModal,
            SuccessNotification,
            ErrorNotification,
            Filters,
            PopOut
        },
        created() {
            this.$store.dispatch('loadCafes');
            this.$store.dispatch('loadUser');
            this.$store.dispatch('loadBrewMethods');
            this.$store.dispatch('loadCities');
            if (this.$store._modules.get(['admin'])) {
                this.$store.unregisterModule('admin', {});
            }
        },
        computed: {
            showFilters() {
                return this.$store.getters.getShowFilters;
            },

            addedCafe() {
                return this.$store.getters.getAddedCafe;
            },

            addCafeStatus() {
                return this.$store.getters.getCafeAddStatus;
            },

            cafesView() {
                return this.$store.getters.getCafesView;
            }
        },

        watch: {
            'addCafeStatus': function () {
                if (this.addCafeStatus === 2) {
                    EventBus.$emit('show-success', {
                        notification: this.addedCafe.name + ' 已经添加成功!'
                    });
                }
            }
        },

        methods: {
            toggleShowFilters() {
                this.$store.dispatch('toggleShowFilters', {showFilters: !this.showFilters});
            }
        }
    }
</script>