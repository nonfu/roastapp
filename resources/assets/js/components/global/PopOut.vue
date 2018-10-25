<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div.pop-out {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        top: 0;
        background-color: rgba(55, 44, 12, .29);
        z-index: 9999;
        div.pop-out-side-bar {
            position: fixed;
            right: 0;
            bottom: 0;
            top: 0;
            width: 250px;
            background-color: white;
            box-shadow: -2px 0 4px 0 rgba(3, 27, 78, 0.10);
            padding: 30px;
            div.side-bar-link {
                border-bottom: 1px solid #BABABA;
                font-size: 16px;
                font-weight: bold;
                font-family: "Lato", sans-serif;
                text-transform: uppercase;
                padding-top: 25px;
                padding-bottom: 25px;
                a {
                    color: black;
                }
            }
            img.close-menu-icon {
                float: right;
                cursor: pointer;
            }
            div.ssu-container {
                position: absolute;
                bottom: 30px;
                span.ssu-built-on {
                    color: black;
                    font-size: 14px;
                    font-family: "Lato", sans-serif;
                    display: block;
                    margin-bottom: 10px;
                }
                img {
                    margin: auto;
                    max-width: 190px;
                }
            }
        }
    }
</style>

<template>
    <div class="pop-out" v-show="showPopOut" v-on:click="hideNav()">
        <transition name="slide-in-right">
            <div class="pop-out-side-bar" v-show="showRightNav" v-on:click.stop>
                <img src="/storage/img/close-menu.svg" class="close-menu-icon" v-on:click="hideNav()"/>
                <div class="side-bar-link">
                    <router-link :to="{ name: 'cafes' }" v-on:click.native="hideNav()">
                        咖啡店
                    </router-link>
                </div>
                <div class="side-bar-link" v-if="user != '' && userLoadStatus === 2">
                    <router-link :to="{ name: 'newcafe' }" v-on:click.native="hideNav()">
                        新增咖啡店
                    </router-link>
                </div>
                <div class="side-bar-link" v-if="user != '' && userLoadStatus === 2">
                    <router-link :to="{ name: 'profile' }" v-on:click.native="hideNav()">
                        个人信息
                    </router-link>
                </div>
                <div class="side-bar-link" v-if="user != '' && userLoadStatus === 2 && user.permission >= 1">
                    <router-link :to="{ name: 'admin'}" v-on:click.native="hideNav()">
                        管理后台
                    </router-link>
                </div>
                <div class="side-bar-link">
                    <a v-if="user != '' && userLoadStatus === 2" v-show="userLoadStatus === 2"
                       v-on:click="logout()">退出</a>
                    <a v-if="user == ''" v-on:click="login()">登录</a>
                </div>
                <div class="side-bar-link">
                    <a href="https://github.com/nonfu/roastapp/issues/new/choose" target="_blank">
                        提交bug
                    </a>
                </div>
                <div class="side-bar-link">
                    <a href="https://laravelacademy.org/api-driven-development-laravel-vue" target="_blank">
                        项目文档
                    </a>
                </div>
                <div class="side-bar-link">
                    <a href="https://github.com/nonfu/roastapp" target="_blank">
                        在Github上查看
                    </a>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>

    import {EventBus} from '../../event-bus.js';

    export default {

        computed: {
            showPopOut() {
                return this.$store.getters.getShowPopOut;
            },

            showRightNav() {
                return this.showPopOut;
            },

            user() {
                return this.$store.getters.getUser;
            },

            userLoadStatus() {
                return this.$store.getters.getUserLoadStatus();
            }
        },

        methods: {
            hideNav() {
                this.$store.dispatch('toggleShowPopOut', {showPopOut: false});
            },

            login() {
                this.$store.dispatch('toggleShowPopOut', {showPopOut: false});
                EventBus.$emit('prompt-login');
            },

            logout() {
                this.$store.dispatch('logoutUser');
                window.location = '/logout';
            }
        }
    }
</script>