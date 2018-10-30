<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div.action {
        font-family: "Lato", sans-serif;
        border-bottom: 1px solid black;
        padding-bottom: 15px;
        padding-top: 15px;

        span.approve-action {
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
            margin-right: 20px;
        }

        span.deny-action {
            color: $secondary-color;
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
        }

        img.more-info {
            cursor: pointer;
            float: right;
            margin-top: 10px;
            margin-right: 10px;
        }
    }
</style>

<template>
    <div class="action">
        <div class="grid-x">
            <div class="large-3 medium-3 cell">
                {{ action.company != null ? action.company.name : '' }}
            </div>
            <div class="large-3 medium-3 cell">
                {{ action.cafe != null ? action.cafe.location_name : '' }}
            </div>
            <div class="large-3 medium-3 cell">
                {{ type }}
            </div>
            <div class="large-3 medium-3 cell">
                <span class="approve-action" v-on:click="approveAction()">通过</span>
                <span class="deny-action" v-on:click="denyAction()">拒绝</span>
                <span v-on:click="showDetails = !showDetails">
                    <img src="/storage/img/more-info-closed.svg" class="more-info" v-show="!showDetails"/>
                    <img src="/storage/img/more-info-open.svg" class="more-info" v-show="showDetails"/>
                </span>
            </div>
        </div>
        <div class="grid-x" v-show="showDetails">
            <div class="large-12 medium-12 cell">
                <action-cafe-added v-if="action.type === 'cafe-added'" :action="action"></action-cafe-added>
                <action-cafe-edited v-if="action.type === 'cafe-updated'" :action="action"></action-cafe-edited>
                <action-cafe-deleted v-if="action.type === 'cafe-deleted'" :action="action"></action-cafe-deleted>
            </div>
        </div>
    </div>
</template>

<script>
    import ActionCafeAdded from './ActionCafeAdded.vue';
    import ActionCafeEdited from './ActionCafeEdited.vue';
    import ActionCafeDeleted from './ActionCafeDeleted.vue';

    import {EventBus} from '../../../event-bus.js';

    export default {
        props: ['action'],

        components: {
            ActionCafeAdded,
            ActionCafeEdited,
            ActionCafeDeleted
        },

        data() {
            return {
                showDetails: false
            }
        },

        computed: {
            type() {
                switch (this.action.type) {
                    case 'cafe-added':
                        return '添加咖啡店';
                    case 'cafe-updated':
                        return '更新咖啡店';
                    case 'cafe-deleted':
                        return '删除咖啡店';
                }
            },

            actionApproveStatus() {
                return this.$store.getters.getActionApproveStatus;
            },

            actionDeniedStatus() {
                return this.$store.getters.getActionDeniedStatus;
            }
        },

        watch: {
            'actionApprovedStatus': function () {
                if (this.actionApproveStatus === 2) {
                    EventBus.$emit('show-success', {
                        notification: 'Action approved successfully!'
                    });
                }
            },

            'actionDeniedStatus': function () {
                if (this.actionDeniedStatus === 2) {
                    EventBus.$emit('show-success', {
                        notification: 'Action denied successfully!'
                    });
                }
            }
        },

        methods: {
            approveAction() {
                this.$store.dispatch('approveAction', {
                    id: this.action.id
                });
            },

            denyAction() {
                this.$store.dispatch('denyAction', {
                    id: this.action.id
                });
            }
        }
    }
</script>