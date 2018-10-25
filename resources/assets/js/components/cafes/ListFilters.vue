<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div.list-filters-container {
        border-bottom: 1px solid #BABABA;
        margin-bottom: 30px;
        padding-bottom: 20px;
        padding-top: 20px;
        span.clear-filters {
            font-size: 16px;
            color: $text-secondary-color;
            font-family: "Lato", sans-serif;
            cursor: pointer;
            display: block;
            float: left;
            margin-bottom: 20px;
            display: none;
            img {
                margin-right: 10px;
                float: left;
                margin-top: 6px;
            }
        }
        span.filters-header {
            display: block;
            font-family: "Lato", sans-serif;
            font-weight: bold;
            margin-bottom: 10px;
        }
        input[type="text"].search {
            box-shadow: none;
            border-radius: 3px;
            color: #BABABA;
            font-size: 16px;
            font-family: "Lato", sans-serif;
            background-image: url('/storage/img/search-icon.svg');
            background-repeat: no-repeat;
            background-position: 6px;
            padding-left: 35px;
            padding-top: 5px;
            padding-bottom: 5px;
        }
        label.filter-label {
            font-family: "Lato", sans-serif;
            text-transform: uppercase;
            font-weight: bold;
            color: black;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        div.location-filter {
            text-align: center;
            font-family: "Lato", sans-serif;
            font-size: 16px;
            color: $secondary-color;
            border-bottom: 1px solid $secondary-color;
            border-top: 1px solid $secondary-color;
            border-left: 1px solid $secondary-color;
            border-right: 1px solid $secondary-color;
            width: 33%;
            display: inline-block;
            height: 55px;
            line-height: 55px;
            cursor: pointer;
            margin-bottom: 5px;
            &.active {
                color: white;
                background-color: $secondary-color;
            }
            &.all-locations {
                border-top-left-radius: 3px;
                border-bottom-left-radius: 3px;
            }
            &.roasters {
                border-left: none;
                border-right: none;
            }
            &.cafes {
                border-top-right-radius: 3px;
                border-bottom-right-radius: 3px;
            }
        }
        span.order-direction {
            cursor: pointer;
            display: inline-block;
            padding: 5px 20px;
            background-color: white;
            font-family: "Lato", sans-serif;
            text-align: center;
            border: 1px solid $secondary-color;
            color: $secondary-color;
            &.asc {
                border-top-left-radius: 5px;
                border-bottom-left-radius: 5px;
                &.active {
                    color: white;
                    background-color: $secondary-color;
                }
            }
            &.desc {
                border-top-right-radius: 5px;
                border-bottom-right-radius: 5px;
                &.active {
                    color: white;
                    background-color: $secondary-color;
                }
            }
        }
    }

    /* Small only */
    @media screen and (max-width: 39.9375em) {
        div.list-filters-container {
            span.clear-filters {
                display: block;
            }
            div.close-filters {
                display: none;
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
    <transition name="slide-in-top">
        <div class="large-12 medium-12 small-12 cell list-filters-container"
             v-show="showFilters && cafesView === 'list'">
            <div class="grid-x cafe-grid-container">
                <div class="large-12 medium-12 small-12 cell">
                    <div class="grid-x grid-padding-x">
                        <div class="large-12 medium-12 small-12 cell">
                            <label class="filter-label">城市</label>
                            <select v-model="cityFilter">
                                <option value=""></option>
                                <option v-for="city in cities" v-bind:value="city.id">{{ city.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid-x grid-padding-x">
                        <div class="large-6 medium-6 small-12 cell">
              <span class="clear-filters" v-show="showFilters" v-on:click="clearFilters()">
                <img src="/storage/img/clear-filters-icon.svg"/> 清除过滤器
              </span>
                            <div class="grid-x grid-padding-x">
                                <div class="large-12 medium-12 small-12 cell">
                                    <label class="filter-label">通过名称或位置搜索</label>
                                </div>
                            </div>
                            <input type="text" class="search" v-model="textSearch"
                                   placeholder="通过名称或位置搜索"/>
                        </div>
                        <div class="large-6 medium-6 small-12 cell">
                            <div id="location-type-container">
                                <div class="grid-x grid-padding-x">
                                    <div class="large-12 medium-12 small-12 cell">
                                        <label class="filter-label">咖啡店类型</label>
                                    </div>
                                </div>

                                <div class="grid-x grid-padding-x">
                                    <div class="large-12 medium-12 small-12 cell">
                                        <div class="location-filter all-locations"
                                             v-bind:class="{ 'active': activeLocationFilter === 'all' }"
                                             v-on:click="setActiveLocationFilter('all')">
                                            所有类型
                                        </div>
                                        <div class="location-filter roasters"
                                             v-bind:class="{ 'active': activeLocationFilter === 'roasters' }"
                                             v-on:click="setActiveLocationFilter('roasters')">
                                            烘焙店
                                        </div>
                                        <div class="location-filter cafes"
                                             v-bind:class="{ 'active': activeLocationFilter === 'cafes' }"
                                             v-on:click="setActiveLocationFilter('cafes')">
                                            咖啡店
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid-x grid-padding-x" id="only-liked-container"
                         v-show="user != '' && userLoadStatus === 2">
                        <div class="large-12 medium-12 small-12 cell">
                            <input type="checkbox" v-model="onlyLiked"/> <span
                                class="liked-location-label">只显示我喜欢过的</span>
                        </div>
                    </div>

                    <div class="grid-x grid-padding-x">
                        <div class="large-6 medium-6 small-12 cell">
                            <label class="filter-label">排序</label>
                            <select v-model="orderBy">
                                <option value="name">名称</option>
                                <option value="most-liked">最多喜欢</option>
                            </select>
                        </div>
                        <div class="large-6 medium-6 small-12 cell">
                            <label class="filter-label">顺序</label>
                            <span class="asc order-direction" v-bind:class="{ 'active': orderDirection === 'asc' }"
                                  v-on:click="setOrderDirection('asc')">升序</span>
                            <span class="desc order-direction" v-bind:class="{ 'active': orderDirection === 'desc' }"
                                  v-on:click="setOrderDirection('desc')">降序</span>
                        </div>
                    </div>

                    <div id="brew-methods-container">
                        <div class="grid-x grid-padding-x">
                            <div class="large-12 medium-12 small-12 cell">
                                <label class="filter-label">冲泡方法</label>
                            </div>
                        </div>

                        <div class="grid-x grid-padding-x">
                            <div class="large-12 medium-12 small-12 cell">
                                <div class="brew-method option" v-on:click="toggleBrewMethodFilter( method.id )"
                                     v-for="method in brewMethods" v-if="method.cafes_count > 0"
                                     v-bind:class="{'active': brewMethodsFilter.indexOf( method.id ) >= 0 }">
                                    <div class="option-container">
                                        <img v-bind:src="method.icon" class="option-icon"/> <span
                                            class="option-name">{{ method.method }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="drink-options-container">
                        <div class="grid-x grid-padding-x">
                            <div class="large-6 medium-6 small-12 cell">
                                <label class="filter-label">饮料选项</label>

                                <div class="drink-option option" v-on:click="toggleMatchaFilter()"
                                     v-bind:class="{'active':hasMatcha}">
                                    <div class="option-container">
                                        <img src="/storage/img/matcha-latte.svg" class="option-icon"/> <span
                                            class="option-name">抹茶</span>
                                    </div>
                                </div>

                                <div class="drink-option option" v-on:click="toggleTeaFilter()"
                                     v-bind:class="{'active':hasTea}">
                                    <div class="option-container">
                                        <img src="/storage/img/tea-bag.svg" class="option-icon"/> <span
                                            class="option-name">茶包</span>
                                    </div>
                                </div>
                            </div>
                            <div class="large-6 medium-6 small-12 cell">
                                <div class="grid-x grid-padding-x"
                                     v-show="activeLocationFilter === 'roasters' || activeLocationFilter === 'all'">
                                    <div class="large-12 medium-12 small-12 cell">
                                        <label class="filter-label">是否提供订购服务</label>
                                    </div>
                                </div>

                                <div class="grid-x grid-padding-x"
                                     v-show="activeLocationFilter === 'roasters' || activeLocationFilter === 'all'">
                                    <div class="large-12 medium-12 small-12 cell">
                                        <div class="subscription option" v-on:click="toggleSubscriptionFilter()"
                                             v-bind:class="{'active': hasSubscription }">
                                            <div class="option-container">
                                                <img src="/storage/img/coffee-pack.svg" class="option-icon"/> <span
                                                    class="option-name">咖啡订购</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import {EventBus} from '../../event-bus.js';

    export default {

        mounted() {

            EventBus.$on('show-filters', function () {
                this.show = true;
            }.bind(this));

            EventBus.$on('clear-filters', function () {
                this.clearFilters();
            }.bind(this));
        },

        watch: {

            'cityFilter': function () {

                if (this.cityFilter !== '') {
                    let id = '';

                    for (let i = 0; i < this.cities.length; i++) {
                        if (this.cities[i].id === this.cityFilter) {
                            id = this.cities[i].id;
                        }
                    }
                    if (id === '') {
                        this.$router.push({name: 'cafes'});
                    } else {
                        this.$router.push({name: 'city', params: {id: id}});
                    }
                } else {
                    this.$router.push({name: 'cafes'});
                }
            },

            'citiesLoadStatus': function () {

                if (this.citiesLoadStatus === 2 && this.$route.name === 'city') {
                    let id = '';
                    for (let i = 0; i < this.cities.length; i++) {
                        if (this.cities[i].id === this.$route.params.id) {
                            this.cityFilter = this.cities[i].id;
                        }
                    }
                }
            }
        },

        computed: {

            showFilters() {
                return this.$store.getters.getShowFilters;
            },
            cities() {
                return this.$store.getters.getCities;
            },
            citiesLoadStatus() {
                return this.$store.getters.getCitiesLoadStatus;
            },
            cityFilter: {
                set(cityFilter) {
                    this.$store.commit('setCityFilter', cityFilter);
                },
                get() {
                    return this.$store.getters.getCityFilter;
                }
            },

            brewMethods() {
                return this.$store.getters.getBrewMethods;
            },

            user() {
                return this.$store.getters.getUser;
            },

            userLoadStatus() {
                return this.$store.getters.getUserLoadStatus();
            },

            cafesView() {
                return this.$store.getters.getCafesView;
            },

            textSearch: {
                set(textSearch) {
                    this.$store.commit('setTextSearch', textSearch)
                },
                get() {
                    return this.$store.getters.getTextSearch;
                }
            },

            activeLocationFilter() {
                return this.$store.getters.getActiveLocationFilter;
            },

            onlyLiked: {
                set(onlyLiked) {
                    this.$store.commit('setOnlyLiked', onlyLiked);
                },
                get() {
                    return this.$store.getters.getOnlyLiked;
                }
            },

            brewMethodsFilter() {
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

            orderBy: {

                set(orderBy) {
                    this.$store.dispatch('updateOrderBy', orderBy);
                },

                get() {
                    return this.$store.getters.getOrderBy;
                }
            },

            orderDirection() {
                return this.$store.getters.getOrderDirection;
            }
        },

        methods: {

            setActiveLocationFilter(filter) {
                this.$store.dispatch('updateActiveLocationFilter', filter);
            },

            toggleBrewMethodFilter(id) {
                let localBrewMethodsFilter = this.brewMethodsFilter;
                if (localBrewMethodsFilter.indexOf(id) >= 0) {
                    localBrewMethodsFilter.splice(localBrewMethodsFilter.indexOf(id), 1);
                } else {
                    localBrewMethodsFilter.push(id);
                }
                this.$store.dispatch('updateBrewMethodsFilter', localBrewMethodsFilter);
            },

            toggleShowFilters() {
                this.$store.dispatch('toggleShowFilters', {showFilters: !this.showFilters});
            },

            toggleMatchaFilter() {
                this.$store.dispatch('updateHasMatcha', !this.hasMatcha);
            },

            toggleTeaFilter() {
                this.$store.dispatch('updateHasTea', !this.hasTea);
            },

            toggleSubscriptionFilter() {
                this.$store.dispatch('updateHasSubscription', !this.hasSubscription);
            },

            clearFilters() {
                this.$store.dispatch('resetFilters');
            },

            setOrderDirection(direction) {
                this.$store.dispatch('updateOrderDirection', direction);
            }
        }
    }
</script>