<style>

</style>

<template>
    <div id="app-layout">
        <navigation></navigation>
        <router-view></router-view>
    </div>
</template>

<script>
    import Navigation from '../components/global/Navigation.vue';

    export default {
        components: {
            Navigation
        },
        created(){
            this.$store.dispatch( 'loadCafes' );
            this.$store.dispatch( 'loadUser' );
        }
    }
</script>