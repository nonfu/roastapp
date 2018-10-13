<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#home{
        a.add-cafe-button{
            float: right;
            display: block;
            margin-top: 10px;
            margin-bottom: 10px;
            background-color: $dark-color;
            color: white;
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 10px;
            padding-right: 10px;
        }
    }
</style>

<template>
    <div id="home">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-12 medium-12 small-12 columns">
                    <router-link :to="{ name: 'newcafe' }" class="add-cafe-button">+ 新增咖啡店</router-link>
                </div>
            </div>
        </div>

        <cafe-filter></cafe-filter>

        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <loader v-show="cafesLoadStatus == 1" :width="100" :height="100"></loader>
                <cafe-card v-for="cafe in cafes" :key="cafe.id" :cafe="cafe"></cafe-card>
            </div>
        </div>
    </div>
</template>

<script>
    import CafeCard from '../components/cafes/CafeCard.vue';
    import Loader from '../components/global/Loader.vue';
    import CafeFilter from '../components/cafes/CafeFilter.vue';

    export default {
        created() {
            this.$store.dispatch( 'loadCafes' );
        },
        /**
         * 定义组件的计算属性
         */
        computed: {
            // 获取 cafes 加载状态
            cafesLoadStatus(){
                return this.$store.getters.getCafesLoadStatus;
            },

            // 获取 cafes
            cafes(){
                return this.$store.getters.getCafes;
            }
        },

        components: {
            CafeCard,
            Loader,
            CafeFilter
        }
    }
</script>