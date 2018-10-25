<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#admin-actions {
        div.actions-header {
            font-family: "Lato", sans-serif;
            border-bottom: 1px solid black;
            font-weight: bold;
            padding-bottom: 10px;
        }

        div.no-actions-available {
            text-align: center;
            font-family: "Lato", sans-serif;
            font-size: 20px;
            padding-top: 20px;
            padding-bottom: 20px;
        }
    }
</style>

<template>
    <div id="admin-actions">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-12 medium-12 cell">
                    <h3 class="page-header">动作列表</h3>
                </div>
            </div>
        </div>


        <div class="grid-container">
            <div class="grid-x actions-header">
                <div class="large-3 medium-3 cell">
                    公司
                </div>
                <div class="large-3 medium-3 cell">
                    咖啡店
                </div>
                <div class="large-3 medium-3 cell">
                    类型
                </div>
                <div class="large-3 medium-3 cell">
                    操作
                </div>
            </div>
            <action v-for="action in actions"
                    :key="action.id"
                    :action="action">
            </action>
            <div class="large-12 medium-12 cell no-actions-available" v-show="actions.length === 0">
                所有动作已处理!
            </div>
        </div>
    </div>
</template>

<script>
    import Action from '../../components/admin/actions/Action.vue';

    export default {
        components: {
            Action
        },

        created() {
            this.$store.dispatch('loadAdminActions');
        },

        computed: {
            actions() {
                return this.$store.getters.getActions;
            }
        }
    }
</script>