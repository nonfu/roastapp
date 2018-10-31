<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#admin-users {
        label {
            font-weight: bold;
            color: black;
            font-size: 16px;
            margin-top: 15px;
        }
        img.large-avatar {
            display: block;
            width: 50px;
            height: 50px;
            border-radius: 50px;
        }
        a.update-user {
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
        div.company {
            padding-top: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid black;
            a.remove-company {
                text-decoration: underline;
                color: red;
                float: right;
            }
        }
        input[type="text"].company-owner-input {
            margin-top: 30px;
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
    }
</style>

<template>
    <div id="admin-users">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-12 medium-12 cell">
                    <h3 class="page-header">
                        <router-link :to="{ name: 'admin-users'}">所有用户</router-link>
                        > {{ adminUser.name }}
                    </h3>
                </div>
            </div>

            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>头像</label>
                    <img class="large-avatar" v-bind:src="adminUser.avatar"/>
                </div>
            </div>

            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>姓名</label>
                    {{ adminUser.name }}
                </div>
            </div>

            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>邮箱</label>
                    {{ adminUser.email }}
                </div>
            </div>

            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>权限级别</label>
                    <select v-model="permission">
                        <option value="0">普通用户</option>
                        <option value="1">商家用户</option>
                        <option value="2">管理员</option>
                        <option value="3" v-if="user.permission === 3">超级管理员</option>
                    </select>
                </div>
            </div>

            <div class="grid-x">
                <div class="large-8 medium-12 cell company-selection-container">
                    <label>拥有的公司</label>

                    <span class="no-companies-owned" v-if="companies.length === 0">N/A</span>

                    <div class="company" v-for="(company, key) in companies">
                        <router-link :to="{ name: 'admin-company', params: { id: company.id } }">{{ company.name }}
                        </router-link>
                        <a class="remove-company" v-on:click="removeCompany( key )">移除</a>
                    </div>

                    <input type="text" class="form-input company-owner-input" v-model="companyName"
                           v-on:keyup="searchCompanies()"/>
                    <div class="company-autocomplete-container" v-show="companyName.length > 0 && showAutocomplete">
                        <div class="company-autocomplete" v-for="companyResult in companyResults"
                             v-on:click="selectCompany( companyResult )">
                            <span class="company-name">{{ companyResult.name }}</span>
                            <span class="company-locations">{{ companyResult.cafes_count }} location<span
                                    v-if="companyResult.cafes_count > 1">s</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <a class="update-user" v-on:click="updateProfile()">更新</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
    import {EventBus} from '../../event-bus.js';
    
    import _ from 'lodash';
    
    import {ROAST_CONFIG} from '../../config.js';

    export default {
        data() {
            return {
                companyName: '',
                companyResults: [],
                showAutocomplete: false,
                permission: 0,
                companies: []
            }
        },
        
        created() {
            this.$store.dispatch('loadAdminUser', {id: this.$route.params.id});
        },
        
        computed: {
            
            user() {
                return this.$store.getters.getUser;
            },
            
            adminUser() {
                return this.$store.getters.getAdminUser;
            },
            
            adminUserLoadStatus() {
                return this.$store.getters.getAdminUserLoadStatus;
            },
            
            adminUserUpdateStatus() {
                return this.$store.getters.getAdminUserUpdateStatus;
            }
        },
        
        watch: {
            
            'adminUserLoadStatus': function () {
                if (this.adminUserLoadStatus === 2) {
                    this.syncUserToModel();
                }
            },
            
            'adminUserUpdateStatus': function () {
                if (this.adminUserUpdateStatus === 2) {
                    this.syncUserToModel();
                    EventBus.$emit('show-success', {
                        notification: '更新用户成功!'
                    });
                }
            }
        },
        
        methods: {
            
            syncUserToModel() {
                this.permission = this.adminUser.permission;
                this.companies = this.adminUser.companies_owned
            },
            
            updateProfile() {
                this.$store.dispatch('updateAdminUser', {
                    id: this.adminUser.id,
                    permission: this.permission,
                    companies: this.companies
                });
            },
            
            searchCompanies: _.debounce(function (e) {
                
                if (this.companyName.length > 1) {
                    this.showAutocomplete = true;
                    axios.get(ROAST_CONFIG.API_URL + '/admin/companies', {
                        params: {
                            search: this.companyName
                        }
                    }).then(function (response) {
                        this.companyResults = response.data;
                    }.bind(this));
                }
            }, 300),
            
            selectCompany(company) {
                this.showAutocomplete = false;
                this.companies.push(company);
                this.companyResults = [];
                this.companyName = '';
            },
            
            removeCompany(index) {
                this.companies.splice(index, 1);
            }
        }
    }
</script>