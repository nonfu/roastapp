<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div.cafe-page {
        h2 {
            text-align: center;
            color: $primary-color;
            font-family: 'Josefin Sans', sans-serif;
        }

        h3 {
            text-align: center;
            color: $secondary-color;
            font-family: 'Josefin Sans', sans-serif;
        }

        span.address {
            text-align: center;
            display: block;
            font-family: 'Lato', sans-serif;
            color: #A0A0A0;
            font-size: 20px;
            line-height: 30px;
            margin-top: 50px;
        }

        a.website {
            text-align: center;
            color: $dull-color;
            font-size: 30px;
            font-weight: bold;
            margin-top: 50px;
            display: block;
            font-family: 'Josefin Sans', sans-serif;
        }

        div.brew-methods-container {
            max-width: 700px;
            margin: auto;

            div.cell {
                text-align: center;
            }
        }

        div.tags-container {
            max-width: 700px;
            margin: auto;
            text-align: center;
            margin-top: 30px;

            span.tag {
                color: $dark-color;
                font-family: 'Josefin Sans', sans-serif;
                margin-right: 20px;
                display: inline-block;
                line-height: 20px;
            }
        }
    }
</style>

<template>
    <div id="cafe" class="page">

        <div class="grid-container">
            <div class="grid-x grid-padding-x">

                <div class="large-12 medium-12 small-12 cell">
                    <loader v-show="cafeLoadStatus === 1" :width="100" :height="100">
                    </loader>

                    <div class="cafe-page" v-show="cafeLoadStatus === 2">
                        <h2>{{ cafe.name }}</h2>
                        <h3 v-if="cafe.location_name !== ''">{{ cafe.location_name }}</h3>

                        <div class="like-container">
                            <div class="grid-x">
                                <div class="large-12 medium-12 small-12 cell">
                                    <toggle-like v-if="user != '' && userLoadStatus == 2"></toggle-like>
                                    <a class="prompt-log-in" v-if="user == '' && userLoadStatus == 2"
                                       v-on:click="login()">登录后喜欢该咖啡店</a>
                                </div>
                            </div>
                        </div>

                        <div class="tags-container">
                            <div class="grid-x grid-padding-x">
                                <div class="large-12 medium-12 small-12 cell">
                                    <span class="tag" v-for="tag in cafe.tags">#{{ tag.tag }}</span>
                                </div>
                            </div>
                        </div>

                        <span class="address">
                            {{ cafe.address }}<br>
                            {{ cafe.city }}, {{ cafe.state }}<br>
                            {{ cafe.zip }}
                        </span>

                        <a class="website" v-bind:href="cafe.website" target="_blank">{{ cafe.website }}</a>

                        <div class="brew-methods-container">
                            <div class="grid-x grid-padding-x">
                                <div class="large-3 medium-4 small-12 cell" v-for="brewMethod in cafe.brew_methods">
                                    {{ brewMethod.method }}
                                </div>
                            </div>
                        </div>

                        <br>

                        <individual-cafe-map></individual-cafe-map>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<script>

    import Loader from '../components/global/Loader.vue';
    import IndividualCafeMap from '../components/cafes/IndividualCafeMap.vue';
    import ToggleLike from '../components/cafes/ToggleLike.vue';

    import {EventBus} from '../event-bus.js';

    export default {
        // 定义页面使用的组件
        components: {
            Loader,
            IndividualCafeMap,
            ToggleLike
        },

        // 页面创建时通过路由中的参数ID加载咖啡店数据
        created() {
            this.$store.dispatch('loadCafe', {
                id: this.$route.params.id
            });
        },

        // 定义计算属性
        computed: {
            cafeLoadStatus() {
                return this.$store.getters.getCafeLoadStatus;
            },

            cafe() {
                return this.$store.getters.getCafe;
            },

            // 从 Vuex 中获取用户加载状态
            userLoadStatus() {
                return this.$store.getters.getUserLoadStatus();
            },

            // 从 Vuex 中获取用户信息
            user() {
                return this.$store.getters.getUser;
            },
        },

        methods: {
            login() {
                EventBus.$emit('prompt-login');
            },
        }
    }
</script>