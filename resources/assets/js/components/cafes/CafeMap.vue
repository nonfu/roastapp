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
                markers: []
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

                // 遍历所有咖啡店创建点标记
                for (var i = 0; i < this.cafes.length; i++) {

                    // 为每个咖啡店创建点标记并设置经纬度
                    var marker = new AMap.Marker({
                        position: AMap.LngLat(parseFloat(this.cafes[i].latitude), parseFloat(this.cafes[i].longitude)),
                        title: this.cafes[i].name
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