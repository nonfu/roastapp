<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div.user-listing {
        padding-top: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid black;
        img.avatar {
            width: 20px;
            height: 20px;
            border-radius: 20px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
        }
    }
</style>

<template>
    <div class="user user-listing">
        <div class="grid-x">
            <div class="large-3 medium-3 cell">
                <img v-bind:src="user.avatar" class="avatar"/> {{ user.name }}
            </div>
            <div class="large-3 medium-3 cell">
                {{ user.email }}
            </div>
            <div class="large-3 medium-3 cell">
                <span class="general" v-if="user.permission === 0">普通用户</span>
                <span class="owner" v-if="user.permission === 1">商家</span>
                <span class="admin" v-if="user.permission === 2">管理员</span>
                <span class="super-admin" v-if="user.permission === 3">超级管理员</span>
            </div>
            <div class="large-3 medium-3 cell">
                <router-link :to="{ name: 'admin-user', params: { id: user.id } }">更多信息</router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user']
    }
</script>