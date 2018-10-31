<style lang="scss">
    @import "~@/abstracts/_variables.scss";

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
    import SuccessNotification from "../components/global/SuccessNotification.vue";
    import ErrorNotification from "../components/global/ErrorNotification.vue";

    import AdminHeader from "../components/admin/AdminHeader.vue";
    import Navigation from "../components/admin/Navigation.vue";
    import PopOut from "../components/global/PopOut.vue";

    // Import admin Vuex modules
    import {actions} from "../modules/admin/actions.js";
    import {companies} from '../modules/admin/companies.js';
    import {cafes} from '../modules/admin/cafes.js';
    import {users} from '../modules/admin/users.js';
    import {brewMethods} from '../modules/admin/brewMethods.js';
    import {cities} from '../modules/admin/cities.js';

    export default {
        components: {
            SuccessNotification,
            ErrorNotification,
            AdminHeader,
            Navigation,
            PopOut
        },

        created() {
            this.$store.dispatch("loadBrewMethods");

            if (!this.$store._modules.get(["admin"])) {
                this.$store.registerModule("admin", {});
            }

            if (!this.$store._modules.get(["admin", "actions"])) {
                this.$store.registerModule(["admin", "actions"], actions);
            }

            if (!this.$store._modules.get(['admin', 'companies'])) {
                this.$store.registerModule(['admin', 'companies'], companies);
            }

            if (!this.$store._modules.get(['admin', 'cafes'])) {
                this.$store.registerModule(['admin', 'cafes'], cafes);
            }

            if (!this.$store._modules.get(['admin', 'users']) && this.user.permission >= 2) {
                this.$store.registerModule(['admin', 'users'], users);
            }

            if (!this.$store._modules.get(['admin', 'brewMethods']) && this.user.permission === 3) {
                this.$store.registerModule(['admin', 'brewMethods'], brewMethods);
            }

            if (!this.$store._modules.get(['admin', 'cities']) && this.user.permission === 3) {
                this.$store.registerModule(['admin', 'cities'], cities);
            }
        },

        computed: {
            user() {
                return this.$store.getters.getUser;
            }
        }
    };
</script>