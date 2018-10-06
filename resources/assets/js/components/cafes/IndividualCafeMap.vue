<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#individual-cafe-map {
        width: 700px;
        height: 500px;
        margin: auto;
        margin-bottom: 200px;
    }
</style>

<template>
    <div id="individual-cafe-map">

    </div>
</template>

<script>
    import {ROAST_CONFIG} from '../../config.js';

    export default {
        computed: {
            cafeLoadStatus() {
                return this.$store.getters.getCafeLoadStatus;
            },

            cafe() {
                return this.$store.getters.getCafe;
            }
        },

        watch: {
            cafeLoadStatus() {
                if (this.cafeLoadStatus === 2) {
                    this.displayIndividualCafeMap();
                }
            }
        },

        methods: {

            displayIndividualCafeMap() {

                this.map = new AMap.Map('individual-cafe-map', {
                    center: [parseFloat(this.cafe.latitude), parseFloat(this.cafe.longitude)],
                    zoom: 13
                });

                var image = ROAST_CONFIG.APP_URL + '/storage/img/coffee-marker.png';
                var icon = new AMap.Icon({
                    image: image,  // Icon的图像
                    imageSize: new AMap.Size(19, 33)
                });

                var marker = new AMap.Marker({
                    position: new AMap.LngLat(parseFloat(this.cafe.latitude), parseFloat(this.cafe.longitude)),
                    icon: icon
                });

                this.map.add(marker);
            }
        }
    }
</script>