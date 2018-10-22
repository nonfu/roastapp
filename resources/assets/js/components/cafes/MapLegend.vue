<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#map-legend {
        position: absolute;
        z-index: 9;
        left: 15px;
        bottom: 90px;
        -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
        border-radius: 5px;
        background-color: white;
        padding: 10px;
        max-width: 200px;
        font-family: "Lato", sans-serif;
        span.legend-title {
            display: block;
            text-align: center;
            font-family: "Lato", sans-serif;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        div.legend-row {
            margin-bottom: 5px;
            img {
                margin-right: 10px;
            }
        }
    }
</style>

<template>
    <div id="map-legend" v-show="( !showFilters && cafesView === 'map' )">
        <div class="grid-x">
            <div class="large-12 medium-12 small-12 cell">
                <span class="legend-title">图例</span>
            </div>
            <div class="large-12 medium-12 small-12 cell legend-row">
                <img src="/storage/img/roaster-marker.svg"/> 烘焙店
            </div>
            <div class="large-12 medium-12 small-12 cell legend-row">
                <img src="/storage/img/cafe-marker.svg"/> 咖啡店
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {}
        },

        computed: {
            showFilters() {
                return this.$store.getters.getShowFilters;
            },

            cafesView() {
                return this.$store.getters.getCafesView;
            }
        }
    }
</script>