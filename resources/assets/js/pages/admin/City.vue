<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    a.save-edits {
        display: inline-block;
        width: 150px;
        color: white;
        background-color: #CCC;
        text-align: center;
        border-radius: 5px;
        margin-top: 20px;
        height: 45px;
        line-height: 45px;
        margin-right: 10px;
        margin-bottom: 20px;
    }

    a.delete-city {
        display: inline-block;
        width: 150px;
        color: white;
        background-color: #E8635F;
        text-align: center;
        border-radius: 5px;
        margin-top: 20px;
        height: 45px;
        line-height: 45px;
        margin-right: 10px;
        margin-bottom: 20px;
    }
</style>

<template>
    <div id="admin-city">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <h3 class="page-header">
                        <router-link :to="{ name: 'admin-cities'}">所有城市</router-link>
                        >
                        {{ city.name }}
                    </h3>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>名称</label>
                    <input type="text" v-model="name"/>
                    <span class="validation" v-show="!validations.name">请输入城市名称!</span>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>省份</label>
                    <select v-model="state" v-bind:class="{'invalid' : !validations.state }">
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
                    <div class="validation" v-show="!validations.name">请选择城市所在省份!</div>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>国家</label>
                    <input type="text" v-model="country"/>
                    <span class="validation" v-show="!validations.country">请输入城市所在国家!</span>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>纬度</label>
                    <input type="text" v-model="latitude"/>
                    <span class="validation" v-show="!validations.latitude">请输入该城市纬度!</span>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>经度</label>
                    <input type="text" v-model="longitude"/>
                    <span class="validation" v-show="!validations.longitude">请输入该城市经度!</span>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-12 medium-12 cell">
                    <a class="save-edits" v-on:click="saveEdits()">更新城市</a>
                    <a class="delete-city" v-on:click="deleteCity()">删除城市</a>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <h3 class="page-header">该城市中的所有咖啡店</h3>
                </div>
            </div>
            <div class="grid-x list-header">
                <div class="large-3 medium-3 cell">
                    公司
                </div>
                <div class="large-3 medium-3 cell">
                    咖啡店
                </div>
                <div class="large-3 medium-3 cell">
                    地址
                </div>
                <div class="large-3 medium-3 cell">

                </div>
            </div>
            <div class="grid-x listing" v-for="cafe in city.cafes">
                <div class="large-3 medium-3 cell">
                    {{ cafe.company.name }}
                </div>
                <div class="large-3 medium-3 cell">
                    {{ cafe.location_name }}
                </div>
                <div class="large-3 medium-3 cell">
                    {{ cafe.address }}
                </div>
                <div class="large-3 medium-3 cell">
                    <router-link :to="{ name: 'admin-cafe', params: { id: cafe.company.id, cafeID: cafe.id } }">编辑
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {EventBus} from '../../event-bus.js';

    export default {

        data() {
            return {
                name: '',
                state: '',
                country: '',
                latitude: '',
                longitude: '',
                validations: {
                    name: true,
                    state: true,
                    country: true,
                    latitude: true,
                    longitude: true
                }
            }
        },

        created() {
            this.$store.dispatch('loadAdminCity', {id: this.$route.params.id});
        },

        computed: {
            city() {
                return this.$store.getters.getAdminCity;
            },

            cityLoadStatus() {
                return this.$store.getters.getAdminCityLoadStatus;
            },

            cityEditStatus() {
                return this.$store.getters.getAdminCityEditStatus;
            },

            cityDeleteStatus() {
                return this.$store.getters.getAdminCityDeleteStatus;
            }
        },

        watch: {
            'cityLoadStatus': function () {
                this.syncCityToModel();
            },

            'cityDeleteStatus': function () {
                if (this.cityDeleteStatus === 2) {
                    EventBus.$emit('show-success', {
                        notification: this.name + ' deleted successfully!'
                    });
                    this.$router.push({name: 'admin-cities'});
                }
            },

            'cityEditStatus': function () {
                if (this.cityEditStatus === 2) {
                    EventBus.$emit('show-success', {
                        notification: this.name + ' updated successfully!'
                    });
                }
            }
        },

        methods: {
            syncCityToModel() {
                this.name = this.city.name;
                this.state = this.city.state;
                this.country = this.city.country;
                this.latitude = this.city.latitude;
                this.longitude = this.city.longitude;
            },

            saveEdits() {
                if (this.validateEditCity()) {
                    this.$store.dispatch('updateAdminCity', {
                        id: this.city.id,
                        name: this.name,
                        state: this.state,
                        country: this.country,
                        latitude: this.latitude,
                        longitude: this.longitude
                    });
                }
            },

            deleteCity() {
                this.$store.dispatch('deleteAdminCity', {
                    id: this.city.id
                });
            },

            validateEditCity() {
                let validEditCityForm = true;
                if (this.name.trim() === '') {
                    validEditCityForm = false;
                    this.validations.name = false;
                } else {
                    this.validations.name = true;
                }
                if (this.state === '') {
                    validEditCityForm = false;
                    this.validations.state = false;
                } else {
                    this.validations.state = true;
                }
                if (this.country === '') {
                    validEditCityForm = false;
                    this.validations.country = false;
                } else {
                    this.validations.country = true;
                }
                if (this.latitude === '') {
                    validEditCityForm = false;
                    this.validations.latitude = false;
                } else {
                    this.validations.latitude = true;
                }
                if (this.longitude === '') {
                    validEditCityForm = false;
                    this.validations.longitude = false;
                } else {
                    this.validations.longitude = true;
                }
                return validEditCityForm;
            }
        }
    }
</script>