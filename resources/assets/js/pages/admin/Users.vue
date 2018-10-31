<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div.users-header {
        font-family: "Lato", sans-serif;
        border-bottom: 1px solid black;
        font-weight: bold;
        padding-bottom: 10px;
    }
</style>

<template>
    <div id="admin-users">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-12 medium-12 cell">
                    <h3 class="page-header">所有用户</h3>
                </div>
            </div>
        </div>

        <div class="grid-container">
            <div class="grid-x users-header">
                <div class="large-3 medium-3 cell">
                    姓名
                </div>
                <div class="large-4 medium-4 cell">
                    邮箱
                </div>
                <div class="large-3 medium-3 cell">
                    类型
                </div>
                <div class="large-2 medium-2 cell">

                </div>
            </div>

            <user v-for="(user, key) in users"
                  :key="user.id"
                  :user="user"
            ></user>
        </div>
    </div>
</template>

<script>
    
    import User from '../../components/admin/users/User.vue';

    export default {
        
        components: {
            User
        },
        
        created() {
            
            this.$store.dispatch('loadAdminUsers');
        },
        computed: {
            
            users() {
                return this.$store.getters.getAdminUsers;
            }
        }
    }
</script>