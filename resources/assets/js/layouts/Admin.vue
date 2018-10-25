<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#admin-layout {
        div#page-container {
            margin-top: 75px;
        }
    }
</style>

<template>
    <div id="admin-layout">
        <admin-header></admin-header>

        <success-notification></success-notification>
        <error-notification></error-notification>

        <div class="grid-container" id="page-container">
            <div class="grid-x grid-padding-x">
                <div class="large-3 medium-4 cell">
                    <navigation></navigation>
                </div>
                <div class="large-9 medium-8 cell">
                    <router-view></router-view>
                </div>
            </div>
        </div>

        <pop-out></pop-out>
    </div>
</template>

<script>
    import SuccessNotification from '../components/global/SuccessNotification.vue';
    import ErrorNotification from '../components/global/ErrorNotification.vue';

    import AdminHeader from '../components/admin/AdminHeader.vue';
    import Navigation from '../components/admin/Navigation.vue';
    import PopOut from '../components/global/PopOut.vue';

    // Import admin Vuex modules
    import {actions} from '../modules/admin/actions.js';

    export default {
        components: {
            SuccessNotification,
            ErrorNotification,
            AdminHeader,
            Navigation,
            PopOut
        },

        created() {
            this.$store.dispatch('loadBrewMethods');

            if (!this.$store._modules.get(['admin'])) {
                this.$store.registerModule('admin', {});
            }

            if (!this.$store._modules.get(['admin', 'actions'])) {
                this.$store.registerModule(['admin', 'actions'], actions);
            }
        },

        computed: {
            user() {
                return this.$store.getters.getUser;
            }
        }
    }
</script>