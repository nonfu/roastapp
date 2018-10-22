<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div.cafe-card {
        border-radius: 5px;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
        padding: 15px 5px 5px 5px;
        margin-top: 20px;
        cursor: pointer;
        -webkit-transform: scaleX(1) scaleY(1);
        transform: scaleX(1) scaleY(1);
        transition: .2s;
        span.title {
            display: block;
            text-align: center;
            color: black;
            font-size: 18px;
            font-weight: bold;
            font-family: 'Lato', sans-serif;
        }
        span.address {
            display: block;
            text-align: center;
            margin-top: 5px;
            color: $grey;
            font-family: 'Lato', sans-serif;
            span.street {
                font-size: 14px;
                display: block;
            }
            span.city {
                font-size: 14px;
            }
            span.state {
                font-size: 14px;
            }
            span.zip {
                font-size: 14px;
                display: block;
            }
        }
        span.liked-meta {
            color: $grey;
            font-size: 10px;
            margin-left: 5px;
            margin-right: 3px;
            img {
                width: 10px;
            }
        }
        &:hover {
            -webkit-transform: scaleX(1.041) scaleY(1.041);
            transform: scaleX(1.041) scaleY(1.041);
            transition: .2s;
        }
    }
</style>

<template>
    <div class="large-3 medium-4 small-6 cell cafe-card-container" v-show="show">
        <router-link :to="{ name: 'cafe', params: { id: cafe.id} }">
            <div class="cafe-card">
                <span class="title">{{ cafe.name }}</span>
                <span class="address">
                    <span class="street">{{ cafe.address }}</span>
                    <span class="city">{{ cafe.city }}</span> <span class="state">{{ cafe.state }}</span>
                    <span class="zip">{{ cafe.zip }}</span>
                </span>
            </div>
            <div class="meta-data">
                <span class="liked-meta">
                    <img v-bind:src="cafe.user_like_count > 0 ? '/storage/img/liked.svg' : '/storage/img/unliked.svg'"/>
                    {{ cafe.likes_count }}
                </span>
            </div>
        </router-link>
    </div>
</template>

<script>

    import {CafeTypeFilter} from '../../mixins/filters/CafeTypeFilter.js';
    import {CafeBrewMethodsFilter} from '../../mixins/filters/CafeBrewMethodsFilter.js';
    import {CafeTextFilter} from '../../mixins/filters/CafeTextFilter.js';
    import {CafeUserLikeFilter} from '../../mixins/filters/CafeUserLikeFilter.js';
    import {CafeHasMatchaFilter} from '../../mixins/filters/CafeHasMatchaFilter.js';
    import {CafeHasTeaFilter} from '../../mixins/filters/CafeHasTeaFilter.js';
    import {CafeSubscriptionFilter} from '../../mixins/filters/CafeSubscriptionFilter.js';
    import {CafeInCityFilter} from '../../mixins/filters/CafeInCityFilter.js';

    import {EventBus} from '../../event-bus.js';

    export default {
        mixins: [
            CafeTypeFilter,
            CafeBrewMethodsFilter,
            CafeTextFilter,
            CafeUserLikeFilter,
            CafeHasMatchaFilter,
            CafeHasTeaFilter,
            CafeSubscriptionFilter,
            CafeInCityFilter
        ],
        props: [
            'cafe'
        ],
        data() {
            return {
                show: true
            }
        },
        mounted() {
            EventBus.$on('filters-updated', function (filters) {
                this.processFilters(filters);
            }.bind(this));
            this.processFilters();
        },
        computed: {
            city() {
                return this.$store.getters.getCity;
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
            }
        },
        watch: {

            cityFilter() {
                this.processFilters();
            },

            textSearch() {
                this.processFilters();
            },

            activeLocationFilter() {
                this.processFilters();
            },

            onlyLiked() {
                this.processFilters();
            },

            brewMethodsFilter() {
                this.processFilters();
            },

            hasMatcha() {
                this.processFilters();
            },

            hasTea() {
                this.processFilters();
            },

            hasSubscription() {
                this.processFilters();
            }
        },
        methods: {
            processFilters() {
                // 如果过滤器为空，则显示所有咖啡店
                if (this.textSearch === ''
                    && this.activeLocationFilter === 'all'
                    && this.brewMethodsFilter.length === 0
                    && !this.onlyLiked
                    && !this.hasMatcha
                    && !this.hasTea
                    && !this.hasSubscription
                    && this.cityFilter === '') {
                    this.show = true;
                } else {
                    // 初始化过滤器条件
                    var textPassed = false;
                    var brewMethodsPassed = false;
                    var typePassed = false;
                    var likedPassed = false;
                    var matchaPassed = false;
                    var teaPassed = false;
                    var subscriptionPassed = false;
                    var cityPassed = false;

                    if (this.processCafeTypeFilter(this.cafe, this.activeLocationFilter)) {
                        typePassed = true;
                    }

                    if (this.textSearch !== '' && this.processCafeTextFilter(this.cafe, this.textSearch)) {
                        textPassed = true;
                    } else if (this.textSearch === '') {
                        textPassed = true;
                    }

                    if (this.brewMethodsFilter.length !== 0 && this.processCafeBrewMethodsFilter(this.cafe, this.brewMethodsFilter)) {
                        brewMethodsPassed = true;
                    } else if (this.brewMethodsFilter.length === 0) {
                        brewMethodsPassed = true;
                    }

                    if (this.onlyLiked && this.processCafeUserLikeFilter(this.cafe)) {
                        likedPassed = true;
                    } else if (!this.onlyLiked) {
                        likedPassed = true;
                    }

                    if (this.hasMatcha && this.processCafeHasMatchaFilter(this.cafe)) {
                        matchaPassed = true;
                    } else if (!this.hasMatcha) {
                        matchaPassed = true;
                    }

                    if (this.hasTea && this.processCafeHasTeaFilter(this.cafe)) {
                        teaPassed = true;
                    } else if (!this.hasTea) {
                        teaPassed = true;
                    }

                    if (this.hasSubscription && this.processCafeSubscriptionFilter(this.cafe)) {
                        subscriptionPassed = true;
                    } else if (!this.hasSubscription) {
                        subscriptionPassed = true;
                    }

                    if (this.cityFilter !== '' && this.processCafeInCityFilter(this.cafe, this.cityFilter)) {
                        cityPassed = true;
                    } else if (this.cityFilter === '') {
                        cityPassed = true;
                    }

                    if (typePassed && textPassed && brewMethodsPassed && likedPassed && matchaPassed && teaPassed && subscriptionPassed && cityPassed) {
                        this.show = true;
                    } else {
                        this.show = false;
                    }
                }
            },

            panToLocation(cafe) {
                EventBus.$emit('location-selected', {lat: parseFloat(cafe.latitude), lng: parseFloat(cafe.longitude)});
            }
        }
    };
</script>