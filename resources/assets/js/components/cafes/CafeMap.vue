<style lang="scss">
    div#cafe-map {
        width: 100%;
        height: 400px;
    }
</style>

<template>
    <div id="cafe-map">

    </div>
</template>

<script>
    import {ROAST_CONFIG} from '../../config.js';

    export default {
        props: {
            'latitude': {
                type: Number,
                default: function () {
                    return 120.21
                }
            },
            'longitude': {
                type: Number,
                default: function () {
                    return 30.29
                }
            },
            'zoom': {
                type: Number,
                default: function () {
                    return 4
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
            this.map = new AMap.Map('cafe-map', {
                center: [this.latitude, this.longitude],
                zoom: this.zoom
            });
            this.clearMarkers();
            this.buildMarkers();
        },
        computed: {
            cafes() {
                return this.$store.getters.getCafes;
            }
        },
        methods: {
            // 为所有咖啡店创建点标记
            buildMarkers() {
                // 初始化点标记数组
                this.markers = [];

                // 自定义点标记
                var image = ROAST_CONFIG.APP_URL + '/storage/img/coffee-marker.png';
                var icon = new AMap.Icon({
                    image: image,  // Icon的图像
                    imageSize: new AMap.Size(19, 33)
                });

                // 遍历所有咖啡店创建点标记
                for (var i = 0; i < this.cafes.length; i++) {

                    // 为每个咖啡店创建点标记并设置经纬度
                    var marker = new AMap.Marker({
                        position: AMap.LngLat(parseFloat(this.cafes[i].latitude), parseFloat(this.cafes[i].longitude)),
                        title: this.cafes[i].name,
                        icon: icon,
                        map: this.map
                    });

                    // 自定义信息窗体
                    var infoWindow = new AMap.InfoWindow({
                        content: this.cafes[i].name
                    });
                    this.infoWindows.push(infoWindow);

                    // 绑定点击事件到点标记对象，点击打开上面创建的信息窗体
                    marker.on('click', function () {
                        infoWindow.open(this.getMap(), this.getPosition());
                    });

                    // 将点标记放到数组中
                    this.markers.push(marker);
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
        },
        watch: {
            // 一旦 cafes 有更新立即重构地图点标记
            cafes() {
                this.clearMarkers();
                this.buildMarkers();
            }
        }
    }
</script>