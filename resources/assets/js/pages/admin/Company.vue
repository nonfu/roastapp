<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#admin-company {
        label {
            font-weight: bold;
            color: black;
            font-size: 16px;
            margin-top: 15px;
        }
        div.cafes-header {
            font-family: "Lato", sans-serif;
            border-bottom: 1px solid black;
            font-weight: bold;
            padding-bottom: 10px;
        }
        div.user-selection-container {
            position: relative;
            margin-top: 20px;
            div.user-autocomplete-container {
                border-radius: 3px;
                border: 1px solid #BABABA;
                background-color: white;
                margin-top: -17px;
                width: 80%;
                position: absolute;
                z-index: 9999;
                div.user-autocomplete {
                    cursor: pointer;
                    padding-left: 12px;
                    padding-right: 12px;
                    padding-top: 8px;
                    padding-bottom: 8px;
                    span.user-name {
                        display: block;
                        color: #0D223F;
                        font-size: 16px;
                        font-family: "Lato", sans-serif;
                        font-weight: bold;
                    }
                    &:hover {
                        background-color: #F2F2F2;
                    }
                }
            }
        }
        div.location-type {
            display: inline-block;
            margin-right: 10px;
            cursor: pointer;
            background-color: #CCC;
            &.active {
                color: white;
                background-color: $secondary-color;
            }
        }
        div.owner {
            padding-top: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid black;
            a.remove-owner {
                text-decoration: underline;
                color: red;
                float: right;
            }
        }
        a.save-edits {
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
</style>

<template>
    <div id="admin-company">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-12 medium-12 cell">
                    <h3 class="page-header">
                        <router-link :to="{ name: 'admin-companies'}">所有公司</router-link>
                        > {{ company.name }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="grid-container">
            <div class="grid-x admin-tabs">
                <div class="tab" v-on:click="tab = 'information'" v-bind:class="{'active-tab': tab === 'information'}">
                    信息
                </div>
                <div class="tab" v-on:click="tab = 'cafes'" v-bind:class="{'active-tab': tab === 'cafes'}">
                    咖啡店
                </div>
                <div class="tab" v-on:click="tab = 'history'" v-bind:class="{'active-tab': tab === 'history'}">
                    历史
                </div>
            </div>
        </div>

        <div class="grid-container" v-show="tab === 'information'">
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>公司名称</label>
                    <input type="text" v-model="name"/>
                    <span class="validation" v-show="!validations.name">请输入公司名称</span>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>类型</label>
                    <div class="location-type roaster" v-bind:class="{ 'active': type === 'roaster' }"
                         v-on:click="setCompanyType('roaster')">
                        烘焙店
                    </div>
                    <div class="location-type cafe" v-bind:class="{ 'active': type === 'cafe' }"
                         v-on:click="setCompanyType('cafe')">
                        专卖店
                    </div>
                </div>
            </div>
            <div class="grid-x grid-padding-x" v-show="type === 'roaster'">
                <div class="large-8 medium-9 small-12 cell centered">
                    <label>烘焙店提供订购服务吗?</label>
                </div>
            </div>

            <div class="grid-x grid-padding-x" v-show="type === 'roaster'">
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
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>网站</label>
                    <input type="text" v-model="website"/>
                    <span class="validation" v-show="!validations.website">请输入有效的网站地址</span>
                </div>
            </div>

            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>所有人</label>
                    <div class="no-owners" v-show="owners.length === 0">N/A</div>
                    <div class="owner" v-for="(owner, key) in owners">
                        <router-link v-if="user.permission > 1" :to="{name: 'admin-user', params: { 'id': owner.id } }">
                            {{ owner.name }}
                        </router-link>
                        <span v-if="user.permission === 1">{{ owner.name }}</span>

                        <a class="remove-owner" v-if="user.permission > 1" v-on:click="removeOwner( key )">移除</a>
                    </div>

                    <div class="user-selection-container" v-if="user.permission > 1">
                        <input type="text" class="new-owner" v-model="newOwner" v-on:keyup="searchUsers()"
                               placeholder="添加所有人"/>

                        <div class="user-autocomplete-container" v-show="newOwner.length > 0 && showAutocomplete">
                            <div class="user-autocomplete" v-for="user in newOwnerResults"
                                 v-on:click="selectUser( user )">
                                <span class="user-name">{{ user.name }}</span>
                            </div>
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
                    <a class="save-edits" v-on:click="saveEdits()">更新</a>
                </div>
            </div>
        </div>
        <div class="grid-container" v-show="tab === 'cafes'">
            <div class="grid-x cafes-header">
                <div class="large-3 medium-3 cell">
                    位置名称
                </div>
                <div class="large-6 medium-6 cell">
                    地址
                </div>
                <div class="large-3 medium-3 cell">

                </div>
            </div>
            <cafe v-for="cafe in company.cafes"
                  :cafe="cafe"
                  :key="cafe.id"
            ></cafe>
        </div>
    </div>
</template>

<script>
    
    import Cafe from '../../components/admin/companies/Cafe.vue';
    
    import {EventBus} from '../../event-bus.js';
    
    import _ from 'lodash';
    
    import {ROAST_CONFIG} from '../../config.js';

    export default {
        
        data() {
            return {
                tab: 'information',
                newOwner: '',
                newOwnerResults: [],
                showAutocomplete: true,
                name: '',
                type: '',
                subscription: 0,
                website: '',
                instagram_url: '',
                facebook_url: '',
                twitter_url: '',
                owners: [],
                deleted: 0,
                validations: {
                    name: true,
                    type: true,
                    website: true,
                    owners: true
                }
            }
        },
        
        components: {
            Cafe
        },
        
        created() {
            this.$store.dispatch('loadAdminCompany', {id: this.$route.params.id});
        },
        
        computed: {
            
            user() {
                return this.$store.getters.getUser;
            },
            
            company() {
                return this.$store.getters.getCompany;
            },
            
            companyLoadStatus() {
                return this.$store.getters.getCompanyLoadStatus;
            },
            
            companyEditStatus() {
                return this.$store.getters.getCompanyEditStatus;
            }
        },
        
        watch: {
            
            'companyLoadStatus': function () {
                if (this.companyLoadStatus === 2) {
                    this.syncCompanyToModel();
                }
            },
            
            'companyEditStatus': function () {
                if (this.companyEditStatus === 2) {
                    this.syncCompanyToModel();
                    EventBus.$emit('show-success', {
                        notification: '更新公司信息成功!'
                    });
                }
            }
        },
        
        methods: {
            
            setCompanyType(type) {
                this.type = type;
            },
            
            removeOwner(index) {
                this.owners.splice(index, 1);
            },
            
            saveEdits() {
                if (this.validateEditCompany()) {
                    this.$store.dispatch('updateAdminCompany', {
                        id: this.company.id,
                        name: this.name,
                        type: this.type,
                        website: this.website,
                        instagram_url: this.instagram_url,
                        facebook_url: this.facebook_url,
                        twitter_url: this.twitter_url,
                        subscription: this.subscription,
                        owners: this.owners,
                        deleted: this.deleted
                    });
                }
            },
            
            validateEditCompany() {
                let validCompanyForm = true;
                
                if (this.name.trim() === '') {
                    validCompanyForm = false;
                    this.validations.name = false;
                } else {
                    this.validations.name = true;
                }
                
                if (this.website.trim !== '' && !this.website.match(/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/)) {
                    validNewCafeForm = false;
                    this.validations.website = false;
                } else {
                    this.validations.website = true;
                }
                return validCompanyForm;
            },
            
            syncCompanyToModel() {
                this.name = this.company.name;
                this.type = this.company.roaster === 1 ? 'roaster' : 'cafe';
                this.subscription = this.company.subscription;
                this.website = this.company.website;
                this.instagram_url = this.company.instagram_url;
                this.facebook_url = this.company.facebook_url;
                this.twitter_url = this.company.twitter_url;
                this.owners = this.company.owned_by;
                this.deleted = this.company.deleted;
            },
            
            searchUsers: _.debounce(function (e) {
                
                if (this.newOwner.length > 1) {
                    this.showAutocomplete = true;
                    
                    axios.get(ROAST_CONFIG.API_URL + '/admin/users', {
                        params: {
                            search: this.newOwner
                        }
                    }).then(function (response) {
                        this.newOwnerResults = response.data;
                    }.bind(this));
                }
            }, 300),
            
            selectUser(user) {
                this.owners.push(user);
                this.newOwner = '';
                this.showAutocomplete = false;
            }
        }
    }
</script>