<style lang="scss">
    div#cafe-map{
        position: absolute;
        top: 50px;
        left: 0px;
        right: 0px;
        bottom: 100px;
    }
</style>

<template>
    <div id="cafes">
        <div class="grid-x">
            <div class="large-9 medium-9 small-12 cell">
                <cafe-map></cafe-map>
            </div>
            <div class="large-3 medium-3 small-12 cell">

            </div>
        </div>
    </div>
</template>

<script>
    import CafeMap from '../components/cafes/CafeMap.vue';
    export default {
        components: {
            CafeMap
        }
    }
</script>