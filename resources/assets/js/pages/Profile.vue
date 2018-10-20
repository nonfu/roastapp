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
    <div id="profile" class="page">
        <div id="profile-updated-successfully" class="notification success">
            个人信息更新成功!
        </div>

        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <loader v-show="userLoadStatus === 1" :width="100" :height="100"></loader>
            </div>
        </div>

        <div class="grid-container" v-show="userLoadStatus === 2">
            <div class="grid-x grid-padding-x">
                <div class="large-8 medium-10 small-12 cell center">
                    <label>最喜欢的咖啡
                        <textarea v-model="favorite_coffee"></textarea>
                    </label>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="large-8 medium-10 small-12 cell center">
                    <label>口味记录
                        <textarea v-model="flavor_notes"></textarea>
                    </label>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="large-8 medium-10 small-12 cell center">
                    <label>是否公开个人信息
                        <select id="public-visibility" v-model="profile_visibility">
                            <option value="0">仅自己可见</option>
                            <option value="1">所有人可见</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="large-8 medium-10 small-12 cell center">
                    <label>所在城市
                        <input type="text" v-model="city"/>
                    </label>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="large-8 medium-10 small-12 cell center">
                    <label>所在省份
                        <input type="text" v-model="state"/>
                    </label>
                </div>
            </div>
            <div class="grid-x grid-padding-x">
                <div class="large-8 medium-10 small-12 cell center">
                    <a class="button update-profile" v-on:click="updateProfile()">更新个人信息</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
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
                    $("#profile-updated-successfully").show().delay(5000).fadeOut();
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