/*
 |-------------------------------------------------------------------------------
 | routes.js
 |-------------------------------------------------------------------------------
 | Contains all of the routes for the application
 */

/**
 * Imports Vue and VueRouter to extend with the routes.
 */
import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store.js';

/**
 * Extends Vue to use Vue Router
 */
Vue.use(VueRouter);

// 对需要认证才能访问的路由调用该方法实现 Vue Router 导航守卫
function requireAuth(to, from, next) {
    function proceed() {
        // 如果用户信息已经加载并且不为空则说明该用户已登录，可以继续访问路由，否则跳转到首页
        // 这个功能类似 Laravel 中的 auth 中间件
        if (store.getters.getUserLoadStatus() === 2) {
            if (store.getters.getUser !== '') {
                switch (to.meta.permission) {
                    // 如果权限级别是普通用户则继续
                    case 'user':
                        next();
                        break;

                    // 如果权限级别是商家则需要判断用户角色是否满足
                    case 'owner':
                        if (store.getters.getUser.permission >= 1) {
                            next();
                        } else {
                            next('/cafes');
                        }
                        break;

                    // 如果权限级别是管理员则需要判断用户角色是否满足
                    case 'admin':
                        if (store.getters.getUser.permission >= 2) {
                            next();
                        } else {
                            next('/cafes');
                        }
                        break;

                    // 如果权限级别是超级管理员则需要判断用户角色是否满足
                    case 'super-admin':
                        if (store.getters.getUser.permission === 3) {
                            next();
                        } else {
                            next('/cafes');
                        }
                        break;
                }
            } else {
                next('/');
            }
        }
    }

    if (store.getters.getUserLoadStatus() !== 2) {
        // 如果用户信息未加载完毕则先加载
        store.dispatch('loadUser');

        // 监听用户信息加载状态，加载完成后调用 proceed 方法继续后续操作
        store.watch(store.getters.getUserLoadStatus, function () {
            if (store.getters.getUserLoadStatus() === 2) {
                proceed();
            }
        });
    } else {
        // 如果用户信息加载完毕直接调用 proceed 方法
        proceed()
    }
}

/**
 * Makes a new VueRouter that we will use to run all of the routes for the app.
 */
export default new VueRouter({
    routes: [
        {
            path: '/',
            redirect: {name: 'cafes'},
            name: 'layout',
            component: Vue.component('Home', require('./layouts/Layout.vue')),
            children: [
                {
                    path: 'cafes',
                    name: 'cafes',
                    component: Vue.component('Home', require('./pages/Home.vue')),
                    children: [
                        {
                            path: 'new',
                            name: 'newcafe',
                            component: Vue.component('NewCafe', require('./pages/NewCafe.vue')),
                            beforeEnter: requireAuth,
                            meta: {
                                permission: 'user'
                            }
                        },
                        {
                            path: ':id',
                            name: 'cafe',
                            component: Vue.component('Cafe', require('./pages/Cafe.vue'))
                        },
                        {
                            path: 'cities/:id',
                            name: 'city',
                            component: Vue.component('City', require('./pages/City.vue'))
                        }
                    ]
                },
                {
                    path: 'cafes/:id/edit',
                    name: 'editcafe',
                    component: Vue.component('EditCafe', require('./pages/EditCafe.vue')),
                    beforeEnter: requireAuth,
                    meta: {
                        permission: 'user'
                    }
                },
                {
                    path: 'profile',
                    name: 'profile',
                    component: Vue.component('Profile', require('./pages/Profile.vue')),
                    beforeEnter: requireAuth,
                    meta: {
                        permission: 'user'
                    }
                },
                {
                    path: '_=_',
                    redirect: '/'
                }
            ]
        },
        {
            path: '/admin',
            name: 'admin',
            component: Vue.component('Admin', require('./layouts/Admin.vue')),
            beforeEnter: requireAuth,
            meta: {
                permission: 'owner'
            },
            children: [
                {
                    path: 'actions',
                    name: 'admin-actions',
                    component: Vue.component('AdminActions', require('./pages/admin/Actions.vue')),
                    meta: {
                        permission: 'owner'
                    }
                },
                {
                    path: 'companies',
                    name: 'admin-companies',
                    component: Vue.component('AdminCompanies', require('./pages/admin/Companies.vue')),
                    meta: {
                        permission: 'owner'
                    }
                },
                {
                    path: 'companies/:id',
                    name: 'admin-company',
                    component: Vue.component('AdminCompany', require('./pages/admin/Company.vue')),
                    meta: {
                        permission: 'owner'
                    }
                },
                {
                    path: 'companies/:id/cafe/:cafeID',
                    name: 'admin-cafe',
                    component: Vue.component('AdminCafe', require('./pages/admin/Cafe.vue')),
                    meta: {
                        permission: 'owner'
                    }
                },
                {
                    path: 'users',
                    name: 'admin-users',
                    component: Vue.component('AdminUsers', require('./pages/admin/Users.vue')),
                    meta: {
                        permission: 'admin'
                    }
                },
                {
                    path: 'users/:id',
                    name: 'admin-user',
                    component: Vue.component('AdminUser', require('./pages/admin/User.vue')),
                    meta: {
                        permission: 'admin'
                    }
                },
                {
                    path: 'brew-methods',
                    name: 'admin-brew-methods',
                    component: Vue.component('AdminBrewMethods', require('./pages/admin/BrewMethods.vue')),
                    meta: {
                        permission: 'super-admin'
                    }
                },
                {
                    path: 'brew-methods/:id',
                    name: 'admin-brew-method',
                    component: Vue.component('AdminBrewMethod', require('./pages/admin/BrewMethod.vue')),
                    meta: {
                        permission: 'super-admin'
                    }
                },
                {
                    path: 'cities',
                    name: 'admin-cities',
                    component: Vue.component('AdminCities', require('./pages/admin/Cities.vue')),
                    meta: {
                        permission: 'super-admin'
                    }
                },
                {
                    path: 'cities/:id',
                    name: 'admin-city',
                    component: Vue.component('AdminCity', require('./pages/admin/City.vue')),
                    meta: {
                        permission: 'super-admin'
                    }
                },
                {
                    path: '_=_',
                    redirect: '/'
                }
            ]
        }
    ]
});