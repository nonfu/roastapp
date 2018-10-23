<style scoped lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#new-cafe-page {
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
        .centered {
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
        input[type="text"].form-input {
            border: 1px solid #BABABA;
            border-radius: 3px;
            &.invalid {
                border: 1px solid #D0021B;
            }
        }
        div.validation {
            color: #D0021B;
            font-family: "Lato", sans-serif;
            font-size: 14px;
            margin-top: -15px;
            margin-bottom: 15px;
        }
        div.location-type {
            text-align: center;
            font-family: "Lato", sans-serif;
            font-size: 16px;
            width: 25%;
            display: inline-block;
            height: 55px;
            line-height: 55px;
            cursor: pointer;
            margin-bottom: 5px;
            margin-right: 10px;
            background-color: #EEE;
            color: $black;
            &.active {
                color: white;
                background-color: $secondary-color;
            }
            &.roaster {
                border-top-left-radius: 3px;
                border-bottom-left-radius: 3px;
                border-right: 0px;
            }
            &.cafe {
                border-top-right-radius: 3px;
                border-bottom-right-radius: 3px;
            }
        }
        div.company-selection-container {
            position: relative;
            div.company-autocomplete-container {
                border-radius: 3px;
                border: 1px solid #BABABA;
                background-color: white;
                margin-top: -17px;
                width: 80%;
                position: absolute;
                z-index: 9999;
                div.company-autocomplete {
                    cursor: pointer;
                    padding-left: 12px;
                    padding-right: 12px;
                    padding-top: 8px;
                    padding-bottom: 8px;
                    span.company-name {
                        display: block;
                        color: #0D223F;
                        font-size: 16px;
                        font-family: "Lato", sans-serif;
                        font-weight: bold;
                    }
                    span.company-locations {
                        display: block;
                        font-size: 14px;
                        color: #676767;
                        font-family: "Lato", sans-serif;
                    }
                    &:hover {
                        background-color: #F2F2F2;
                    }
                }
                div.new-company {
                    cursor: pointer;
                    padding-left: 12px;
                    padding-right: 12px;
                    padding-top: 8px;
                    padding-bottom: 8px;
                    font-family: "Lato", sans-serif;
                    color: #054E7A;
                    font-style: italic;
                    &:hover {
                        background-color: #F2F2F2;
                    }
                }
            }
        }
        a.add-location-button {
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

    /* Small only */
    @media screen and (max-width: 39.9375em) {
        div#new-cafe-page {
            div.location-type {
                width: 50%;
            }
        }
    }
</style>

<template>
    <transition name="scale-in-center">
        <div id="new-cafe-page">
            <router-link :to="{ name: 'cafes' }">
                <img src="/storage/img/close-modal.svg" id="back"/>
            </router-link>

            <div class="grid-container">
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <h2 class="page-title">新增咖啡店</h2>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered company-selection-container">
                        <label class="form-label">公司名称</label>
                        <input type="text" class="form-input" v-model="companyName" v-on:keyup="searchCompanies()"
                               v-bind:class="{'invalid' : !validations.companyName.is_valid }"/>
                        <div class="validation" v-show="!validations.companyName.is_valid">{{
                            validations.companyName.text }}
                        </div>
                        <input type="hidden" v-model="companyID"/>
                        <div class="company-autocomplete-container" v-show="companyName.length > 0 && showAutocomplete">
                            <div class="company-autocomplete" v-for="companyResult in companyResults"
                                 v-on:click="selectCompany( companyResult )">
                                <span class="company-name">{{ companyResult.name }}</span>
                                <span class="company-locations">{{ companyResult.cafes_count }} location<span
                                        v-if="companyResult.cafes_count > 1">s</span></span>
                            </div>
                            <div class="new-company" v-on:click="addNewCompany()">
                                Add new company called "{{ companyName }}"
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-x grid-padding-x" v-if="newCompany">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <label class="form-label">网站</label>
                        <input type="text" class="form-input" v-model="website"
                               v-bind="{ 'invalid' : !validations.website.is_valid }"/>
                        <div class="validation" v-show="!validations.website.is_valid">{{ validations.website.text }}
                        </div>
                    </div>
                </div>
                <div class="grid-x grid-padding-x" v-if="newCompany">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <label class="form-label">类型</label>
                    </div>
                </div>

                <div class="grid-x grid-padding-x" v-if="newCompany">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <div class="location-type roaster" v-bind:class="{ 'active': companyType === 'roaster' }"
                             v-on:click="setCompanyType('roaster')">
                            烘焙店
                        </div>
                        <div class="location-type cafe" v-bind:class="{ 'active': companyType === 'cafe' }"
                             v-on:click="setCompanyType('cafe')">
                            咖啡店
                        </div>
                    </div>
                </div>

                <div class="grid-x grid-padding-x" v-if="newCompany" v-show="companyType === 'roaster'">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <label class="form-label">是否提供订购服务？</label>
                    </div>
                </div>

                <div class="grid-x grid-padding-x" v-if="newCompany" v-show="companyType === 'roaster'">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <div class="subscription-option option"
                             v-on:click="subscription === 0 ? subscription = 1 : subscription = 0"
                             v-bind:class="{'active': subscription === 1}">
                            <div class="option-container">
                                <img src="/storage/img/coffee-pack.svg" class="option-icon"/> <span class="option-name">咖啡订购</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <label class="form-label">支持的冲泡方法</label>
                    </div>
                </div>

                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <div class="brew-method option" v-on:click="toggleSelectedBrewMethod(method.id)"
                             v-for="method in brewMethods"
                             v-bind:class="{'active': brewMethodsSelected.indexOf(method.id) >= 0 }">
                            <div class="option-container">
                                <img v-bind:src="method.icon" class="option-icon"/> <span class="option-name">{{ method.method }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <label class="form-label">支持的饮料选项</label>
                    </div>
                </div>

                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <div class="drink-option option" v-on:click="matcha === 0 ? matcha = 1 : matcha = 0"
                             v-bind:class="{'active': matcha === 1 }">
                            <div class="option-container">
                                <img v-bind:src="'/storage/img/matcha-latte.svg'" class="option-icon"/> <span
                                    class="option-name">抹茶</span>
                            </div>
                        </div>
                        <div class="drink-option option" v-on:click="tea === 0 ? tea = 1 : tea = 0"
                             v-bind:class="{'active': tea === 1 }">
                            <div class="option-container">
                                <img v-bind:src="'/storage/img/tea-bag.svg'" class="option-icon"/> <span
                                    class="option-name">茶包</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <label class="form-label">位置名称</label>
                        <input type="text" class="form-input" v-model="locationName"/>
                    </div>
                </div>

                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <label class="form-label">街道地址</label>
                        <input type="text" class="form-input" v-model="address"
                               v-bind:class="{'invalid' : !validations.address.is_valid }"/>
                        <div class="validation" v-show="!validations.address.is_valid">{{ validations.address.text }}
                        </div>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <label class="form-label">城市</label>
                        <input type="text" class="form-input" v-model="city"
                               v-bind:class="{'invalid' : !validations.city.is_valid }"/>
                        <div class="validation" v-show="!validations.city.is_valid">{{ validations.city.text }}</div>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <div class="grid-x grid-padding-x">
                            <div class="large-6 medium-6 small-12 cell">
                                <label class="form-label">省份</label>
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
                                <div class="validation" v-show="!validations.state.is_valid">{{ validations.state.text
                                    }}
                                </div>
                            </div>
                            <div class="large-6 medium-6 small-12 cell">
                                <label class="form-label">邮编</label>
                                <input type="text" class="form-input" v-model="zip"
                                       v-bind:class="{'invalid' : !validations.zip.is_valid }"/>
                                <div class="validation" v-show="!validations.zip.is_valid">{{ validations.zip.text }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-8 medium-9 small-12 cell centered">
                        <a class="add-location-button" v-on:click="submitNewCafe()">添加</a>
                    </div>
                </div>
            </div>

        </div>
    </transition>
</template>

<script>

    import {EventBus} from '../event-bus.js';

    import _ from 'lodash';

    import {ROAST_CONFIG} from '../config.js';

    export default {
        data() {
            return {
                companyResults: [],
                showAutocomplete: true,
                companyName: '',
                companyID: '',
                newCompany: false,
                companyType: 'roaster',
                subscription: 0,
                website: '',
                locationName: '',
                address: '',
                city: '',
                state: '',
                zip: '',
                brewMethodsSelected: [],
                matcha: 0,
                tea: 0,

                validations: {
                    companyName: {
                        is_valid: true,
                        text: ''
                    },
                    website: {
                        is_valid: true,
                        text: ''
                    },
                    address: {
                        is_valid: true,
                        text: ''
                    },
                    city: {
                        is_valid: true,
                        text: ''
                    },
                    state: {
                        is_valid: true,
                        text: ''
                    },
                    zip: {
                        is_valid: true,
                        text: ''
                    }
                }
            }
        },
        methods: {
            setCompanyType(type) {
                this.companyType = type;
            },
            toggleSelectedBrewMethod(id) {
                if (this.brewMethodsSelected.indexOf(id) >= 0) {
                    this.brewMethodsSelected.splice(this.brewMethodsSelected.indexOf(id), 1);
                } else {
                    this.brewMethodsSelected.push(id);
                }
            },
            searchCompanies: _.debounce(function (e) {
                if (this.companyName.length > 1) {
                    this.showAutocomplete = true;
                    axios.get(ROAST_CONFIG.API_URL + '/companies/search', {
                        params: {
                            search: this.companyName
                        }
                    }).then(function (response) {
                        this.companyResults = response.data.companies;
                    }.bind(this));
                }
            }, 300),
            submitNewCafe() {
                if (this.validateNewCafe()) {
                    this.$store.dispatch('addCafe', {
                        company_name: this.companyName,
                        company_id: this.companyID,
                        company_type: this.companyType,
                        subscription: this.subscription,
                        website: this.website,
                        location_name: this.locationName,
                        address: this.address,
                        city: this.city,
                        state: this.state,
                        zip: this.zip,
                        brew_methods: this.brewMethodsSelected,
                        matcha: this.matcha,
                        tea: this.tea
                    });
                }
            },
            validateNewCafe() {
                let validNewCafeForm = true;

                // 确保 name 字段不为空
                if (this.companyName.trim() === '') {
                    validNewCafeForm = false;
                    this.validations.companyName.is_valid = false;
                    this.validations.companyName.text = '请输入咖啡店的名字';
                } else {
                    this.validations.companyName.is_valid = true;
                    this.validations.companyName.text = '';
                }

                // 确保网址是有效的 URL
                if (this.website.trim !== '' && !this.website.match(/^((https?):\/\/)?([w|W]{3}\.)?[a-zA-Z0-9\-\.]{3,}\.[a-zA-Z]{2,}(\.[a-zA-Z]{2,})?$/)) {
                    validNewCafeForm = false;
                    this.validations.website.is_valid = false;
                    this.validations.website.text = '请输入有效的咖啡店网址';
                } else {
                    this.validations.website.is_valid = true;
                    this.validations.website.text = '';
                }

                if (this.address.trim() === '') {
                    validNewCafeForm = false;
                    this.validations.address.is_valid = false;
                    this.validations.address.text = '地址字段不能为空';
                } else {
                    this.validations.address.is_valid = true;
                    this.validations.address.text = '';
                }

                if (this.city.trim() === '') {
                    validNewCafeForm = false;
                    this.validations.city.is_valid = false;
                    this.validations.city.text = '城市字段不能为空';
                } else {
                    this.validations.city.is_valid = true;
                    this.validations.city.text = '';
                }

                if (this.state.trim() === '') {
                    validNewCafeForm = false;
                    this.validations.state.is_valid = false;
                    this.validations.state.text = '省份字段不能为空';
                } else {
                    this.validations.state.is_valid = true;
                    this.validations.state.text = '';
                }

                if (this.zip.trim() === '' || !this.zip.match(/(^\d{6}$)/)) {
                    validNewCafeForm = false;
                    this.validations.zip.is_valid = false;
                    this.validations.zip.text = '请输入有效的邮政编码';
                } else {
                    this.validations.zip.is_valid = true;
                    this.validations.zip.text = '';
                }

                return validNewCafeForm;
            },
            addNewCompany() {
                this.showAutocomplete = false;
                this.newCompany = true;
                this.companyResults = [];
            },
            selectCompany(company) {
                this.showAutocomplete = false;
                this.companyName = company.name;
                this.companyID = company.id;
                this.newCompany = false;
                this.companyResults = [];
                this.website = company.website;
            },
            clearForm() {
                this.companyResults = [];
                this.companyName = '';
                this.companyID = '';
                this.newCompany = false;
                this.companyType = 'roaster';
                this.subscription = 0;
                this.website = '';
                this.locationName = '';
                this.address = '';
                this.city = '';
                this.state = '';
                this.zip = '';
                this.brewMethodsSelected = [];
                this.matcha = 0;
                this.tea = 0;
                this.validations = {
                    companyName: {
                        is_valid: true,
                        text: ''
                    },
                    website: {
                        is_valid: true,
                        text: ''
                    },
                    address: {
                        is_valid: true,
                        text: ''
                    },
                    city: {
                        is_valid: true,
                        text: ''
                    },
                    state: {
                        is_valid: true,
                        text: ''
                    },
                    zip: {
                        is_valid: true,
                        text: ''
                    }
                };
            }
        },
        computed: {
            brewMethods() {
                return this.$store.getters.getBrewMethods;
            }
            ,
            addCafeStatus() {
                return this.$store.getters.getCafeAddStatus;
            }
            ,
            addCafeText() {
                return this.$store.getters.getCafeAddText;
            }
        },
        watch: {
            addCafeStatus() {
                if (this.addCafeStatus === 2) {
                    // 显示添加成功通知
                    EventBus.$emit('show-success', {
                        notification: this.addCafeText
                    });
                    this.clearForm();
                    // 返回列表页
                    this.$router.push({name: 'cafes'});
                }
            }
        }
    }
</script>