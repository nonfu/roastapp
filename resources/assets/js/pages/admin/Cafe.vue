<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#admin-cafe {
        label {
            font-weight: bold;
            color: black;
            font-size: 16px;
            margin-top: 15px;
        }
        a.update-cafe {
            display: block;
            width: 150px;
            color: white;
            background-color: #CCC;
            text-align: center;
            border-radius: 5px;
            margin-top: 20px;
            height: 45px;
            line-height: 45px;
            margin-bottom: 100px;
        }
    }
</style>

<template>
    <div id="admin-cafe">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <h3 class="page-header">
                        <router-link :to="{ name: 'admin-companies'}">所有公司</router-link> >
                        <router-link :to="{ name: 'admin-company', params: { id: this.$route.params.id } }">{{ company.name }}</router-link> >
                        {{ cafe.location_name !== '' ? cafe.location_name : company.name+' at '+cafe.address+' '+cafe.city+' '+cafe.state }}
                    </h3>
                </div>
            </div>

            <div class="grid-container">
                <div class="grid-x admin-tabs">
                    <div class="tab" v-on:click="tab = 'information'" v-bind:class="{'active': tab === 'information'}">
                        信息
                    </div>
                    <div class="tab" v-on:click="tab = 'activity'" v-bind:class="{'active': tab === 'activity'}">
                        活动
                    </div>
                    <div class="tab" v-on:click="tab = 'history'" v-bind:class="{'active': tab === 'history'}">
                        历史
                    </div>
                </div>
            </div>

            <div class="grid-container" v-show="tab === 'activity'">
                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>喜欢</label>
                        {{ cafe.likes_count }} likes
                    </div>
                </div>
            </div>

            <div class="grid-container" v-show="tab === 'information'">
                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>位置名称</label>
                        <input type="text" v-model="location_name"/>
                    </div>
                </div>

                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>街道地址</label>
                        <input type="text" v-model="address"/>
                        <span class="validation" v-show="!validations.address">请输入有效的街道地址</span>
                    </div>
                </div>

                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>城市</label>
                        <input type="text" v-model="city"/>
                        <span class="validation" v-show="!validations.city">请输入有效的所在城市</span>
                    </div>
                </div>

                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>绑定到的城市</label>
                        <select id="bound-to-city" v-model="boundToCity">
                            <option value=""></option>
                            <option v-for="city in cities" v-bind:value="city.id">{{ city.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>省份</label>
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

                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>邮编</label>
                        <input type="text" v-model="zip"/>
                        <span class="validation" v-show="!validations.zip">请输入有效的邮编</span>
                    </div>
                </div>

                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>抹茶</label>
                        <div class="drink-option option" v-on:click="matcha === 0 ? matcha = 1 : matcha = 0"
                             v-bind:class="{'active': matcha === 1 }">
                            <div class="option-container">
                                <img v-bind:src="'/storage/img/matcha-latte.svg'" class="option-icon"/> <span
                                    class="option-name">抹茶</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>茶包</label>
                        <div class="drink-option option" v-on:click="tea === 0 ? tea = 1 : tea = 0"
                             v-bind:class="{'active': tea === 1 }">
                            <div class="option-container">
                                <img v-bind:src="'/storage/img/tea-bag.svg'" class="option-icon"/> <span
                                    class="option-name">茶包</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-x">
                    <div class="large-8 medium-12 small-12 cell">
                        <label>冲泡方法</label>
                        <div class="brew-method option" v-on:click="toggleSelectedBrewMethod( method.id )"
                             v-for="method in brewMethods"
                             v-bind:class="{'active': brewMethodsSelected.indexOf( method.id ) >= 0 }">
                            <div class="option-container">
                                <img v-bind:src="method.icon" class="option-icon"/> <span class="option-name">{{ method.method }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid-x">
                    <div class="large-8 medium-12 cell">
                        <label>状态</label>
                        <select v-model="deleted">
                            <option value="0">有效</option>
                            <option value="1">删除</option>
                        </select>
                    </div>
                </div>

                <div class="grid-x">
                    <div class="large-12 medium-12 cell">
                        <a class="update-cafe" v-on:click="updateCafe()">更新</a>
                    </div>
                </div>
            </div>

            <div class="grid-container" v-show="tab === 'history'">

            </div>
        </div>
    </div>
</template>

<script>
    
    import {EventBus} from '../../event-bus.js';

    export default {
        
        data() {
            return {
                tab: 'information',
                location_name: '',
                address: '',
                city: '',
                state: '',
                zip: '',
                tea: '',
                matcha: '',
                brewMethodsSelected: [],
                boundToCity: '',
                deleted: 0,
                validations: {
                    location_name: true,
                    address: true,
                    city: true,
                    state: true,
                    zip: true
                }
            }
        },
        
        created() {
            this.$store.dispatch('loadAdminCompany', {id: this.$route.params.id});
            this.$store.dispatch('loadAdminCafe', {
                company_id: this.$route.params.id,
                cafe_id: this.$route.params.cafeID
            });
            this.$store.dispatch('loadAdminCities');
        },
        
        computed: {
            
            brewMethods() {
                return this.$store.getters.getBrewMethods;
            },
            
            company() {
                return this.$store.getters.getCompany;
            },
            
            cafe() {
                return this.$store.getters.getAdminCafe;
            },
            
            cafeLoadStatus() {
                return this.$store.getters.getAdminCafeLoadStatus;
            },
            
            cafeEditStatus() {
                return this.$store.getters.getAdminCafeEditStatus;
            },
            
            cities() {
                return this.$store.getters.getAdminCities;
            },
            
            citiesLoadStatus() {
                return this.$store.getters.getAdminCitiesLoadStatus;
            }
        },
        
        watch: {
            
            'cafeLoadStatus': function () {
                if (this.cafeLoadStatus === 2) {
                    this.syncCafeToModel();
                }
            },
            
            'cafeEditStatus': function () {
                if (this.cafeEditStatus === 2) {
                    this.syncCafeToModel();
                    EventBus.$emit('show-success', {
                        notification: '更新咖啡店成功!'
                    });
                }
            }
        },
        
        methods: {
            syncCafeToModel() {
                this.location_name = this.cafe.location_name;
                this.address = this.cafe.address;
                this.city = this.cafe.city;
                this.state = this.cafe.state;
                this.zip = this.cafe.zip;
                this.tea = this.cafe.tea;
                this.matcha = this.cafe.matcha;
                this.boundToCity = this.cafe.city_id;
                for (let i = 0; i < this.cafe.brew_methods.length; i++) {
                    this.brewMethodsSelected.push(this.cafe.brew_methods[i].id);
                }
                this.deleted = this.cafe.deleted;
            },
            
            toggleSelectedBrewMethod(id) {
                if (this.brewMethodsSelected.indexOf(id) >= 0) {
                    this.brewMethodsSelected.splice(this.brewMethodsSelected.indexOf(id), 1);
                } else {
                    this.brewMethodsSelected.push(id);
                }
            },
            
            updateCafe() {
                if (this.validateEditCafe()) {
                    this.$store.dispatch('updateAdminCafe', {
                        id: this.cafe.id,
                        company_id: this.company.id,
                        city_id: this.boundToCity,
                        location_name: this.location_name,
                        address: this.address,
                        city: this.city,
                        state: this.state,
                        zip: this.zip,
                        brew_methods: this.brewMethodsSelected,
                        matcha: this.matcha,
                        tea: this.tea,
                        deleted: this.deleted
                    });
                }
            },
            
            validateEditCafe() {
                let validEditCafeForm = true;
                
                if (this.address.trim() === '') {
                    validEditCafeForm = false;
                    this.validations.address = false;
                } else {
                    this.validations.address = true;
                }
                
                if (this.city.trim() === '') {
                    validEditCafeForm = false;
                    this.validations.city = false;
                } else {
                    this.validations.city = true;
                }
                
                if (this.state.trim() === '') {
                    validEditCafeForm = false;
                    this.validations.state = false;
                } else {
                    this.validations.state = true;
                }
                
                if (this.zip.trim() === '' || !this.zip.match(/(^\d{6}$)/)) {
                    validEditCafeForm = false;
                    this.validations.zip = false;
                } else {
                    this.validations.zip = true;
                }
                return validEditCafeForm;
            }
        }
    }
</script>