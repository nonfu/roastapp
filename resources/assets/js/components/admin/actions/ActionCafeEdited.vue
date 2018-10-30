<style lang="scss">
    div.action-cafe-edited {
        span.change {
            color: red;
        }
    }
</style>

<template>
    <div class="action-cafe-edited action-cafe-detail">
        <div class="grid-x grid-padding-x">
            <div class="large-6 medium-6 cell">
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <span class="action-detail-header">当前数据</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>公司名称</label>
                        <span class="action-content">{{ action.company.name }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>网站</label>
                        <span class="action-content">{{ action.company.website }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>位置名称</label>
                        <span class="action-content">{{ action.cafe.location_name }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>街道地址</label>
                        <span class="action-content">{{ action.cafe.address }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>城市</label>
                        <span class="action-content">{{ action.cafe.city }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>省份</label>
                        <span class="action-content">{{ action.cafe.state }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>邮编</label>
                        <span class="action-content">{{ action.cafe.zip }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>冲泡方法</label>
                        <div class="brew-method option" v-for="method in action.cafe.brew_methods">
                            <div class="option-container">
                                <img v-bind:src="method.icon" class="option-icon"/> <span class="option-name">{{ method.method }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-x" v-if="action.cafe.tea === 1">
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
                <div class="grid-x" v-if="action.cafe.matcha === 1">
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
            <div class="large-6 medium-6 cell">
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <span class="action-detail-header">更新后数据</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>公司名称</label>
                        <span class="action-content"
                              v-bind:class="{'change': content.after.company_name !== action.company.name }">{{ content.after.company_name }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>网站</label>
                        <span class="action-content"
                              v-bind:class="{'change': content.after.website !== action.company.website }">{{ content.after.website }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>位置名称</label>
                        <span class="action-content"
                              v-bind:class="{'change': content.after.location_name !== action.cafe.location_name }">{{ content.after.location_name }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>街道地址</label>
                        <span class="action-content"
                              v-bind:class="{'change': content.after.address !== action.cafe.address }">{{ content.after.address }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>城市</label>
                        <span class="action-content"
                              v-bind:class="{'change': content.after.city !== action.cafe.city }">{{ content.after.city }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>省份</label>
                        <span class="action-content"
                              v-bind:class="{'change': content.after.state !== action.cafe.state }">{{ content.after.state }}</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>邮编</label>
                        <span class="action-content" v-bind:class="{'change': content.after.zip !== action.cafe.zip }">{{ content.after.zip }}</span>
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
                <div class="grid-x" v-if="content.after.tea === 1">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>茶包</label>
                        <div class="drink-option option">
                            <div class="option-container">
                                <img v-bind:src="'/storage/img/icons/tea-bag.svg'" class="option-icon"/> <span
                                    class="option-name">茶包</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-x" v-if="content.after.matcha === 1">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>抹茶</label>
                        <div class="drink-option option">
                            <div class="option-container">
                                <img v-bind:src="'/storage/img/icons/matcha-latte.svg'" class="option-icon"/> <span
                                    class="option-name">抹茶</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="large-12 medium-12 cell">
                <span class="action-information">Cafe Updated by {{ action.by.name }} on {{ action.created_at }}</span>
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
                let contentBrewMethods = JSON.parse(this.content.after.brew_methods);

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
