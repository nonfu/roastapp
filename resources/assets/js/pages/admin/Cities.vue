<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#admin-cities {
        a.add-city {
            display: block;
            width: 150px;
            color: white;
            background-color: #CCC;
            text-align: center;
            border-radius: 5px;
            float: right;
            height: 45px;
            line-height: 45px;
        }
        div.city-listing {
            padding-top: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid black;
        }
        div.new-city-modal {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, .6);
            z-index: 99999;
            div.modal-box {
                width: 100%;
                max-width: 530px;
                min-width: 320px;
                padding: 20px;
                background-color: #fff;
                border: 1px solid #ddd;
                -webkit-box-shadow: 0 1px 3px rgba(50, 50, 50, 0.08);
                box-shadow: 0 1px 3px rgba(50, 50, 50, 0.08);
                -webkit-border-radius: 4px;
                border-radius: 4px;
                font-size: 16px;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                max-height: 500px;
                overflow-y: auto;
                label {
                    font-weight: bold;
                }
                a.add-city-button {
                    display: block;
                    width: 150px;
                    color: white;
                    background-color: #CCC;
                    text-align: center;
                    border-radius: 5px;
                    margin-top: 20px;
                    height: 45px;
                    line-height: 45px;
                }
            }
        }
    }
</style>

<template>
    <div id="admin-cities">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-8 medium-8 cell">
                    <h3 class="page-header">所有城市</h3>
                </div>
                <div class="large-4 medium-4 cell">
                    <a class="add-city" v-on:click="showNewCityModal = true">添加城市</a>
                </div>
            </div>
        </div>

        <div class="grid-container">
            <div class="grid-x list-header">
                <div class="large-3 medium-3 cell">
                    名称
                </div>
                <div class="large-3 medium-3 cell">
                    省份
                </div>
                <div class="large-3 medium-3 cell">
                    国家
                </div>
                <div class="large-3 medium-3 cell">

                </div>
            </div>
            <div class="grid-x listing" v-for="city in cities">
                <div class="large-3 medium-3 cell">
                    {{ city.name }}
                </div>
                <div class="large-3 medium-3 cell">
                    {{ city.state }}
                </div>
                <div class="large-3 medium-3 cell">
                    {{ city.country }}
                </div>
                <div class="large-3 medium-3 cell">
                    <router-link :to="{ name: 'admin-city', params: { 'id': city.id} }">编辑</router-link>
                </div>
            </div>
        </div>

        <div class="new-city-modal" v-show="showNewCityModal" v-on:click="hideNewCityModal()">
            <div class="modal-box" v-on:click.stop="">
                <div class="grid-x">
                    <div class="large-12 medium-12 cell">
                        <label>名称</label>
                        <input type="text" id="city-name" v-model="name"/>
                        <span class="validation" v-show="!validations.name">请输入城市名称!</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 cell">
                        <label>省份</label>
                        <select v-model="state" v-bind:class="{'invalid' : !validations.state.is_valid }">
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
                    <div class="large-12 medium-12 cell">
                        <label>国家</label>
                        <input type="text" id="country" v-model="country"/>
                        <span class="validation" v-show="!validations.country">请输入城市所在国家!</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 cell">
                        <label>纬度</label>
                        <input type="text" id="latitude" v-model="latitude"/>
                        <span class="validation" v-show="!validations.latitude">请输入该城市纬度!</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 cell">
                        <label>经度</label>
                        <input type="text" id="longitude" v-model="longitude"/>
                        <span class="validation" v-show="!validations.longitude">请输入该城市经度!</span>
                    </div>
                </div>
                <div class="grid-x">
                    <div class="large-12 medium-12 cell">
                        <a class="add-city-button" v-on:click="addCity()">提交</a>
                    </div>
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
                showNewCityModal: false,
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
            this.$store.dispatch('loadAdminCities');
        },

        computed: {
            cities() {
                return this.$store.getters.getAdminCities;
            },

            cityAddStatus() {
                return this.$store.getters.getAdminCityAddStatus;
            }
        },

        watch: {
            'cityAddStatus': function () {
                if (this.cityAddStatus === 2) {
                    this.clearForm();
                    EventBus.$emit('show-success', {
                        notification: '添加城市成功!'
                    });
                    this.showNewCityModal = false;
                }
            }
        },

        methods: {
            addCity() {
                if (this.validateNewCity()) {
                    this.$store.dispatch('addAdminCity', {
                        name: this.name,
                        state: this.state,
                        country: this.country,
                        latitude: this.latitude,
                        longitude: this.longitude
                    });
                }
            },

            validateNewCity() {
                let validNewCityForm = true;
                if (this.name.trim() === '') {
                    validNewCityForm = false;
                    this.validations.name = false;
                } else {
                    this.validations.name = true;
                }
                if (this.state === '') {
                    validNewCityForm = false;
                    this.validations.state = false;
                } else {
                    this.validations.state = true;
                }
                if (this.country === '') {
                    validNewCityForm = false;
                    this.validations.country = false;
                } else {
                    this.validations.country = true;
                }
                if (this.latitude === '') {
                    validNewCityForm = false;
                    this.validations.latitude = false;
                } else {
                    this.validations.latitude = true;
                }
                if (this.longitude === '') {
                    validNewCityForm = false;
                    this.validations.longitude = false;
                } else {
                    this.validations.longitude = true;
                }
                return validNewCityForm;
            },

            hideNewCityModal() {
                this.showNewCityModal = false;
                this.clearForm();
            },

            clearForm() {
                this.name = '';
                this.state = '';
                this.country = '';
                this.radius = '';
                this.latitude = '';
                this.longitude = '';
                this.validations.name = true;
                this.validations.state = true;
                this.validations.country = true;
                this.validations.latitude = true;
                this.validations.longitude = true;
            }
        }
    }
</script>