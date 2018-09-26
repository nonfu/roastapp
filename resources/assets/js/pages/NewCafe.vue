<style>

</style>

<template>
    <div class="page">
        <form>
            <div class="grid-container">
                <div class="grid-x grid-padding-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>名称
                            <input type="text" placeholder="咖啡店名" v-model="name">
                        </label>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>地址
                            <input type="text" placeholder="地址" v-model="address">
                        </label>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>城市
                            <input type="text" placeholder="城市" v-model="city">
                        </label>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>省份
                            <input type="text" placeholder="省份" v-model="state">
                        </label>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>邮编
                            <input type="text" placeholder="邮编" v-model="zip">
                        </label>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <a class="button" v-on:click="submitNewCafe()">提交</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: '',
                address: '',
                city: '',
                state: '',
                zip: ''
            }
        },
        methods: {
            submitNewCafe: function () {
                this.$store.dispatch('addCafe', {
                    name: this.name,
                    address: this.address,
                    city: this.city,
                    state: this.state,
                    zip: this.zip
                });
            }
        }
    }
</script>