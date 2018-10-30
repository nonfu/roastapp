<style lang="scss">
    @import '~@/abstracts/_variables.scss';
</style>

<template>
    <div class="action-cafe-added action-cafe-detail">
        <div class="grid-x grid-padding-x">
            <div class="large-6 medium-6 cell">
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>公司名称</label>
                        <span class="action-content">{{ content.company_name }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>网站</label>
                        <span class="action-content">{{ content.website }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>位置名称</label>
                        <span class="action-content">{{ content.location_name }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>街道地址</label>
                        <span class="action-content">{{ content.address }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>城市</label>
                        <span class="action-content">{{ content.city }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>省份</label>
                        <span class="action-content">{{ content.state }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>邮编</label>
                        <span class="action-content">{{ content.zip }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>冲泡方法</label>
                        <div class="brew-method option" v-for="method in actionBrewMethods">
                            <div class="option-container">
                                <img v-bind:src="method.icon" class="option-icon"/> <span class="option-name">{{ method.method }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-x" v-if="content.tea == 1">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>茶包</label>
                        <div class="drink-option option">
                            <div class="option-container">
                                <img v-bind:src="'/storage/img/tea-bag.svg'" class="option-icon"/> <span
                                    class="option-name">茶包</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-x" v-if="content.matcha == 1">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>抹茶</label>
                        <div class="drink-option option">
                            <div class="option-container">
                                <img v-bind:src="'/storage/img/matcha-latte.svg'" class="option-icon"/> <span
                                    class="option-name">抹茶</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="large-12 medium-12 cell">
                <span class="action-information">Cafe Added by {{ action.by.name }} on {{ action.created_at }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['action'],

        data() {
            return {
                content: ''
            }
        },

        created() {
            this.content = JSON.parse(this.action.content);
        },

        computed: {

            brewMethods() {
                return this.$store.getters.getBrewMethods;
            },

            actionBrewMethods() {
                let actionBrewMethods = [];

                let contentBrewMethods = JSON.parse(this.content.brew_methods);

                for (let i = 0; i < contentBrewMethods.length; i++) {
                    for (let k = 0; k < this.brewMethods.length; k++) {
                        if (parseInt(contentBrewMethods[i]) === parseInt(this.brewMethods[k].id)) {
                            actionBrewMethods.push(this.brewMethods[k]);
                        }
                    }
                }

                return actionBrewMethods;
            }
        }
    }
</script>