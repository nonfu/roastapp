<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#cafe-map-container {
        position: absolute;
        top: 75px;
        left: 0px;
        right: 0px;
        bottom: 0px;

        div#cafe-map {
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 0px;
        }

        div.cafe-info-window {

            div.cafe-name {
                display: block;
                text-align: center;
                color: $dark-color;
                font-family: 'Josefin Sans', sans-serif;
            }

            div.cafe-address {
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
                    font-size: 12px;
                }
                span.state {
                    font-size: 12px;
                }
                span.zip {
                    font-size: 12px;
                    display: block;
                }
                a {
                    color: $secondary-color;
                    font-weight: bold;
                }
            }
        }
    }
</style>

<template>
    <div id="cafe-map-container">
        <div id="cafe-map">

        </div>
    </div>
</template>

<script>
    import {ROAST_CONFIG} from '../../config.js';
    import {EventBus} from '../../event-bus.js';

    import {CafeTypeFilter} from '../../mixins/filters/CafeTypeFilter.js';
    import {CafeBrewMethodsFilter} from '../../mixins/filters/CafeBrewMethodsFilter.js';
    import {CafeTagsFilter} from '../../mixins/filters/CafeTagsFilter.js';
    import {CafeTextFilter} from '../../mixins/filters/CafeTextFilter.js';
    import {CafeUserLikeFilter} from '../../mixins/filters/CafeUserLikeFilter.js';
    import {CafeHasMatchaFilter} from '../../mixins/filters/CafeHasMatchaFilter.js';
    import {CafeHasTeaFilter} from '../../mixins/filters/CafeHasTeaFilter.js';
    import {CafeSubscriptionFilter} from '../../mixins/filters/CafeSubscriptionFilter.js';
    import {CafeInCityFilter} from '../../mixins/filters/CafeInCityFilter.js';
    import cafe from "../../api/cafe";

    export default {
        mixins: [
            CafeTypeFilter,
            CafeBrewMethodsFilter,
            CafeTagsFilter,
            CafeTextFilter,
            CafeUserLikeFilter,
            CafeHasMatchaFilter,
            CafeHasTeaFilter,
            CafeSubscriptionFilter,
            CafeInCityFilter
        ],
        props: {
            'latitude': {
                type: Number,
                default: function () {
                    return 30.29
                }
            },
            'longitude': {
                type: Number,
                default: function () {
                    return 120.21
                }
            },
            'zoom': {
                type: Number,
                default: function () {
                    return 5
                }
            }
        },
        data() {
            return {
                markers: [],
                infoWindows: []
            }
        },
        mounted() {
            this.markers = [];
            this.map = new AMap.Map('cafe-map', {
                center: [this.longitude, this.latitude],
                zoom: this.zoom
            });
            this.clearMarkers();
            this.buildMarkers();

            // 监听位置选择事件
            EventBus.$on('location-selected', function (cafe) {
                var latLng = new AMap.LngLat(cafe.lng, cafe.lat);
                this.map.setZoom(17);
                this.map.panTo(latLng);
            }.bind(this));

            // 监听城市选择事件
            EventBus.$on('city-selected', function (city) {
                var latLng = new AMap.LngLat(city.lng, city.lat);
                this.map.setZoom(11);
                this.map.panTo(latLng);
            }.bind(this));
        },
        computed: {
            cafes() {
                return this.$store.getters.getCafes;
            },

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
            },

            previousLat() {
                return this.$store.getters.getLat;
            },

            previousLng() {
                return this.$store.getters.getLng;
            },

            previousZoom() {
                return this.$store.getters.getZoomLevel;
            }
        },
        methods: {
            // 为所有咖啡店创建点标记
            buildMarkers() {
                // 初始化点标记数组
                this.markers = [];

                // 自定义点标记
                /*var image = ROAST_CONFIG.APP_URL + '/storage/img/coffee-marker.png';
                var icon = new AMap.Icon({
                    image: image,  // Icon的图像
                    imageSize: new AMap.Size(19, 33)
                });*/

                // 遍历所有咖啡店创建点标记
                // var infoWindow = new AMap.InfoWindow();
                for (var i = 0; i < this.cafes.length; i++) {

                    if (this.cafes[i].company.roaster === 1) {
                        var image = ROAST_CONFIG.APP_URL + '/storage/img/roaster-marker.svg';
                    } else {
                        var image = ROAST_CONFIG.APP_URL + '/storage/img/cafe-marker.svg';
                    }
                    var icon = new AMap.Icon({
                        image: image,  // Icon的图像
                        imageSize: new AMap.Size(19, 33)
                    });

                    // 为每个咖啡店创建点标记并设置经纬度
                    var marker = new AMap.Marker({
                        position: new AMap.LngLat(parseFloat(this.cafes[i].longitude), parseFloat(this.cafes[i].latitude)),
                        title: this.cafes[i].location_name,
                        icon: icon,
                        extData: {
                            'cafe': this.cafes[i]
                        }
                    });

                    // 自定义信息窗体
                    /*var contentString = '<div class="cafe-info-window">' +
                        '<div class="cafe-name">' + this.cafes[i].name + this.cafes[i].location_name + '</div>' +
                        '<div class="cafe-address">' +
                        '<span class="street">' + this.cafes[i].address + '</span>' +
                        '<span class="city">' + this.cafes[i].city + '</span> ' +
                        '<span class="state">' + this.cafes[i].state + '</span>' +
                        '<span class="zip">' + this.cafes[i].zip + '</span>' +
                        '<a href="/#/cafes/' + this.cafes[i].id + '">Visit</a>' +
                        '</div>' +
                        '</div>';
                    marker.content = contentString;*/
                    marker.cafeId = this.cafes[i].id;

                    // 绑定点击事件到点标记对象，点击跳转到咖啡店详情页
                    marker.on('click', mapClick);

                    // 将点标记放到数组中
                    this.markers.push(marker);
                }

                let store = this.$store;
                let router = this.$router;

                function mapClick(mapEvent) {
                    // infoWindow.setContent(mapEvent.target.content);
                    // infoWindow.open(this.getMap(), this.getPosition());
                    let center = mapEvent.target.getMap().getCenter();
                    store.dispatch('applyZoomLevel', mapEvent.target.getMap().getZoom());
                    store.dispatch('applyLat', center.getLat());
                    store.dispatch('applyLng', center.getLng());
                    router.push({name: 'cafe', params: {id: mapEvent.target.cafeId}});
                }

                // 将所有点标记显示到地图上
                this.map.add(this.markers);
            },
            // 从地图上清理点标记
            clearMarkers() {
                // 遍历所有点标记并将其设置为 null 从而从地图上将其清除
                for (var i = 0; i < this.markers.length; i++) {
                    this.markers[i].setMap(null);
                }
            },
            processFilters(filters) {
                for (var i = 0; i < this.markers.length; i++) {
                    if (this.textSearch === ''
                        && this.activeLocationFilter === 'all'
                        && this.brewMethodsFilter.length === 0
                        && !this.onlyLiked
                        && !this.hasMatcha
                        && !this.hasTea
                        && !this.hasSubscription
                        && this.cityFilter === '') {
                        this.markers[i].setMap(this.map);
                    } else {
                        // 初始化过滤器标识
                        var textPassed = false;
                        var brewMethodsPassed = false;
                        var typePassed = false;
                        var likedPassed = false;
                        var matchaPassed = false;
                        var teaPassed = false;
                        var subscriptionPassed = false;
                        var cityPassed = false;

                        if (this.processCafeTypeFilter(this.markers[i].getExtData().cafe, this.activeLocationFilter)) {
                            typePassed = true;
                        }

                        if (this.textSearch !== '' && this.processCafeTextFilter(this.markers[i].getExtData().cafe, this.textSearch)) {
                            textPassed = true;
                        } else if (this.textSearch === '') {
                            textPassed = true;
                        }

                        if (this.brewMethodsFilter.length !== 0 && this.processCafeBrewMethodsFilter(this.markers[i].getExtData().cafe, this.brewMethodsFilter)) {
                            brewMethodsPassed = true;
                        } else if (this.brewMethodsFilter.length === 0) {
                            brewMethodsPassed = true;
                        }

                        if (this.onlyLiked && this.processCafeUserLikeFilter(this.markers[i].getExtData().cafe)) {
                            likedPassed = true;
                        } else if (!this.onlyLiked) {
                            likedPassed = true;
                        }

                        if (this.hasMatcha && this.processCafeHasMatchaFilter(this.markers[i].getExtData().cafe)) {
                            matchaPassed = true;
                        } else if (!this.hasMatcha) {
                            matchaPassed = true;
                        }

                        if (this.hasTea && this.processCafeHasTeaFilter(this.markers[i].getExtData().cafe)) {
                            teaPassed = true;
                        } else if (!this.hasTea) {
                            teaPassed = true;
                        }

                        if (this.hasSubscription && this.processCafeSubscriptionFilter(this.markers[i].getExtData().cafe)) {
                            subscriptionPassed = true;
                        } else if (!this.hasSubscription) {
                            subscriptionPassed = true;
                        }

                        if (this.cityFilter !== '' && this.processCafeInCityFilter(this.markers[i].getExtData().cafe, this.cityFilter)) {
                            cityPassed = true;
                        } else if (this.cityFilter === '') {
                            cityPassed = true;
                        }

                        if (typePassed && textPassed && brewMethodsPassed && likedPassed && matchaPassed && teaPassed && subscriptionPassed && cityPassed) {
                            this.markers[i].setMap(this.map);
                        } else {
                            this.markers[i].setMap(null);
                        }
                    }
                }
            },
        },
        watch: {
            // 一旦 cafes 有更新立即重构地图点标记
            cafes() {
                this.clearMarkers();
                this.buildMarkers();
                this.processFilters();
            },
            // 如果路由从咖啡店详情页切换到咖啡店列表，检查之前的经纬度是否设置，
            // 如果设置的话将其作为新绘制地图的定位点
            '$route'(to, from) {
                if (to.name === 'cafes' && from.name === 'cafe') {
                    if (this.previousLat !== 0.0 && this.previousLng !== 0.0 && this.previousZoom !== '') {
                        var latLng = new AMap.LngLat(this.previousLng, this.previousLat);
                        this.map.setZoom(this.previousZoom);
                        this.map.panTo(latLng);
                    }
                }
            },

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
        }
    }
</script>