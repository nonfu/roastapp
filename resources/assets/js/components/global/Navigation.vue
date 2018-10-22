<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    nav.top-navigation {
        background-color: #FFFFFF;
        height: 75px;
        box-shadow: 0 2px 4px 0 rgba(3, 27, 78, 0.1);
        z-index: 9999;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        a.filters {
            cursor: pointer;
            color: $secondary-color;
            width: 140px;
            height: 45px;
            border: 2px solid $secondary-color;
            border-radius: 3px;
            text-transform: uppercase;
            display: block;
            float: left;
            text-align: center;
            line-height: 41px;
            margin-top: 15px;
            margin-left: 20px;
            font-family: "Lato", sans-serif;
            font-weight: bold;
            font-size: 16px;
            img {
                display: inline-block;
                vertical-align: middle;
                margin-right: 10px;
                height: 13px;
                &.list {
                    transform: rotate(-90deg);
                }
            }
            img.chevron-active {
                display: none;
            }
            &.active {
                background-color: $secondary-color;
                color: white;
                img.chevron {
                    display: none;
                }
                img.chevron-active {
                    display: inline-block;
                    &.list {
                        transform: rotate(-90deg);
                    }
                }
            }
            span.filter-count-active {
                display: inline-block;
                margin-left: 5px;
            }
        }
        span.clear-filters {
            font-size: 16px;
            color: $text-secondary-color;
            font-family: "Lato", sans-serif;
            cursor: pointer;
            margin-left: 15px;
            display: block;
            float: left;
            margin-top: 25px;
            img {
                margin-right: 10px;
                float: left;
                margin-top: 6px;
            }
        }
        img.logo {
            margin: auto;
            margin-top: 22.5px;
            margin-bottom: 22.5px;
            display: block;
        }
        img.hamburger {
            float: right;
            margin-right: 18px;
            margin-top: 30px;
            cursor: pointer;
        }
        img.avatar {
            float: right;
            margin-right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 20px;
            margin-top: 18px;
        }
        &:after {
            content: "";
            display: table;
            clear: both;
        }
        span.login {
            font-family: "Lato", sans-serif;
            font-size: 16px;
            text-transform: uppercase;
            color: black;
            font-weight: bold;
            float: right;
            margin-top: 27px;
            margin-right: 15px;
            cursor: pointer;
        }
    }

    /* Small only */
    @media screen and (max-width: 39.9375em) {
        nav.top-navigation {
            a.filters {
                line-height: 31px;
                margin-top: 20px;
                width: 75px;
                height: 35px;
                img {
                    display: none;
                }
                &.active {
                    img.chevron-active {
                        display: none;
                    }
                }
            }
            span.clear-filters {
                display: none;
            }
            span.login {
                display: none;
            }
            img.hamburger {
                margin-top: 26px;
            }
        }
    }

    /* Medium only */
    @media screen and (min-width: 40em) and (max-width: 63.9375em) {
    }

    /* Large only */
    @media screen and (min-width: 64em) and (max-width: 74.9375em) {
    }
</style>

<template>
    <nav class="top-navigation">
        <div class="grid-x">
            <div class="large-4 medium-4 small-4 cell">
                <a class="filters" v-bind:class="{'active': showFilters}" v-on:click="toggleShowFilters()">
                    <img class="chevron" v-bind:class="{'list' : cafesView === 'list'}"
                         src="/storage/img/chevron-right.svg"/>
                    <img class="chevron-active" v-bind:class="{'list' : cafesView === 'list'}"
                         src="/storage/img/chevron-right-active.svg"/> 过滤器
                    <span class="filter-count-active" v-show="activeFilterCount > 0">({{ activeFilterCount }})</span>
                </a>

                <span class="clear-filters" v-show="showFilters" v-on:click="clearFilters()">
                    <img src="/storage/img/clear-filters-icon.svg"/> 清除过滤器
                </span>

            </div>
            <div class="large-4 medium-4 small-4 cell">
                <router-link :to="{ name: 'cafes'}">
                    <img src="/storage/img/logo.svg" class="logo"/>
                </router-link>
            </div>
            <div class="large-4 medium-4 small-4 cell">
                <img class="hamburger" src="/storage/img/hamburger.svg" v-on:click="setShowPopOut()"/>
                <img class="avatar" v-if="user != '' && userLoadStatus === 2" :src="user.avatar"
                     v-show="userLoadStatus === 2"/>
                <span class="login" v-if="user == ''" v-on:click="login()">登录</span>
            </div>
        </div>
    </nav>
</template>

<script>
    import {EventBus} from '../../event-bus.js';

    export default {
        // 定义组件的计算属性
        computed: {
            // 从 Vuex 中获取用户加载状态
            userLoadStatus() {
                return this.$store.getters.getUserLoadStatus();
            },

            // 从 Vuex 中获取用户信息
            user() {
                return this.$store.getters.getUser;
            },

            showFilters() {
                return this.$store.getters.getShowFilters;
            },

            cafesView() {
                return this.$store.getters.getCafesView;
            },

            cityFilter() {
                return this.$store.getters.getCityFilter;
            },

            textSearch() {
                return this.$store.getters.getTextSearch;
            },

            activeLocationFilter() {
                return this.$store.getters.getActiveLocationFilter;
            },

            onlyLiked() {
                return this.$store.getters.getOnlyLiked;
            },

            brewMethods() {
                return this.$store.getters.getBrewMethodsFilter;
            },

            hasMatcha() {
                return this.$store.getters.getHasMatcha;
            },

            hasTea() {
                return this.$store.getters.getHasTea;
            },

            hasSubscription() {
                return this.$store.getters.getHasSubscription;
            },

            activeFilterCount() {
                let activeCount = 0;
                if (this.textSearch !== '') {
                    activeCount++;
                }
                if (this.activeLocationFilter !== 'all') {
                    activeCount++;
                }
                if (this.onlyLiked) {
                    activeCount++;
                }
                if (this.brewMethods.length !== 0) {
                    activeCount++;
                }
                if (this.hasMatcha) {
                    activeCount++;
                }
                if (this.hasTea) {
                    activeCount++;
                }
                if (this.hasSubscription) {
                    activeCount++;
                }
                if (this.cityFilter !== '') {
                    activeCount++;
                }
                return activeCount;
            }
        },

        methods: {
            login() {
                EventBus.$emit('prompt-login');
            },

            logout() {
                this.$store.dispatch('logoutUser');
                window.location = '/logout';
            },

            toggleShowFilters() {
                this.$store.dispatch('toggleShowFilters', {showFilters: !this.showFilters});
            },

            setShowPopOut() {
                this.$store.dispatch('toggleShowPopOut', {showPopOut: true});
            },
            
            clearFilters() {
                EventBus.$emit('clear-filters');
            }
        }
    }
</script>