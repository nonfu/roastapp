<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#profile-page {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: white;
        z-index: 99999;
        overflow: auto;
        img#back {
            float: right;
            margin-top: 20px;
            margin-right: 20px;
        }
        div.centered {
            margin: auto;
        }
        h2.page-title {
            color: #342C0C;
            font-size: 36px;
            font-weight: 900;
            font-family: "Lato", sans-serif;
            margin-top: 60px;
        }
        label.form-label {
            font-family: "Lato", sans-serif;
            text-transform: uppercase;
            font-weight: bold;
            color: black;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        a.update-profile-button {
            display: block;
            text-align: center;
            height: 50px;
            color: white;
            border-radius: 3px;
            font-size: 18px;
            font-family: "Lato", sans-serif;
            background-color: #A7BE4D;
            line-height: 50px;
            margin-bottom: 50px;
        }
    }
</style>

<template>
    <transition name="scale-in-center">
        <div id="profile-page">
            <router-link :to="{ name: 'cafes' }">
                <img src="/storage/img/close-modal.svg" id="back"/>
            </router-link>

            <div class="grid-container">
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <h2 class="page-title">个人信息</h2>
                    </div>
                </div>
            </div>


            <div class="grid-container">
                <div class="grid-x grid-padding-x">
                    <loader v-show="userLoadStatus === 1" :width="100" :height="100"></loader>
                </div>
            </div>

            <div class="grid-container" v-show="userLoadStatus === 2">
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-10 small-12 cell centered">
                        <label class="form-label">最喜欢的咖啡</label>
                        <textarea v-model="favorite_coffee"></textarea>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-10 small-12 cell centered">
                        <label class="form-label">口味记录</label>
                        <textarea v-model="flavor_notes"></textarea>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-10 small-12 cell centered">
                        <label class="form-label">是否公开</label>
                        <select id="public-visibility" v-model="profile_visibility">
                            <option value="0">仅自己可见</option>
                            <option value="1">所有人可见</option>
                        </select>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-10 small-12 cell centered">
                        <label class="form-label">所在城市</label>
                        <input type="text" v-model="city"/>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-10 small-12 cell centered">
                        <label class="form-label">所在省份</label>
                        <select v-model="state">
                            <option value=""></option>
                            <option value="北京">北京</option>
                            <option value="上海">上海</option>
                            <option value="天津">天津</option>
                            <option value="重庆">重庆</option>
                            <option value="江苏">江苏</option>
                            <option value="浙江">浙江</option>
                            <option value="安徽">安徽</option>
                            <option value="广东">广东</option>
                            <option value="山东">山东</option>
                            <option value="四川">四川</option>
                            <option value="湖北">湖北</option>
                            <option value="湖南">湖南</option>
                            <option value="山西">山西</option>
                            <option value="陕西">陕西</option>
                            <option value="辽宁">辽宁</option>
                            <option value="吉林">吉林</option>
                            <option value="黑龙江">黑龙江</option>
                            <option value="内蒙古">内蒙古</option>
                            <option value="河南">河南</option>
                            <option value="河北">河北</option>
                            <option value="广西">广西</option>
                            <option value="贵州">贵州</option>
                            <option value="云南">云南</option>
                            <option value="西藏">西藏</option>
                            <option value="青海">青海</option>
                            <option value="新疆">新疆</option>
                            <option value="甘肃">甘肃</option>
                            <option value="宁夏">宁夏</option>
                            <option value="江西">江西</option>
                            <option value="海南">海南</option>
                            <option value="福建">福建</option>
                            <option value="台湾">台湾</option>
                        </select>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-10 small-12 cell centered">
                        <a class="update-profile-button" v-on:click="updateProfile()">更新</a>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import {EventBus} from '../event-bus.js';

    import Loader from '../components/global/Loader.vue';

    export default {
        components: {
            Loader
        },
        data() {
            return {
                favorite_coffee: '',
                flavor_notes: '',
                profile_visibility: 0,
                city: '',
                state: ''
            }
        },
        watch: {
            'userLoadStatus': function () {
                if (this.userLoadStatus === 2) {
                    this.setFields();
                }
            },

            'userUpdateStatus': function () {
                if (this.userUpdateStatus === 2) {
                    EventBus.$emit('show-success', {
                        notification: '个人信息更新成功!'
                    });
                }
            }
        },
        created() {
            if (this.userLoadStatus === 2) {
                this.setFields();
            }
        },
        computed: {
            user() {
                return this.$store.getters.getUser;
            },

            userLoadStatus() {
                return this.$store.getters.getUserLoadStatus();
            },

            userUpdateStatus() {
                return this.$store.getters.getUserUpdateStatus;
            }
        },
        methods: {
            setFields() {
                this.profile_visibility = this.user.profile_visibility;
                this.favorite_coffee = this.user.favorite_coffee;
                this.flavor_notes = this.user.flavor_notes;
                this.city = this.user.city;
                this.state = this.user.state;
            },
            updateProfile() {
                if (this.validateProfile()) {
                    this.$store.dispatch('editUser', {
                        profile_visibility: this.profile_visibility,
                        favorite_coffee: this.favorite_coffee,
                        flavor_notes: this.flavor_notes,
                        city: this.city,
                        state: this.state
                    });
                }
            },
            validateProfile() {
                return true;
            }
        }
    }
</script>