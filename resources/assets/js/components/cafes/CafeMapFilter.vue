<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#cafe-map-filter {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
        padding: 5px;
        z-index: 999999;
        position: absolute;
        right: 45px;
        top: 50px;
        width: 25%;

        div.filter-brew-method {
            display: inline-block;
            height: 30px;
            text-align: center;
            border: 1px solid #ededed;
            border-radius: 5px;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 5px;
            padding-bottom: 5px;
            margin-right: 10px;
            margin-top: 10px;
            cursor: pointer;
            color: #7F5F2A;
            font-family: 'Josefin Sans', sans-serif;
            font-size: 12px;

            &.active {
                border-bottom: 4px solid $primary-color;
            }
        }
    }
</style>

<template>
    <div id="cafe-map-filter">
        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <div class="large-12 medium-12 small-12 cell">
                    <label>搜索</label>
                    <input type="text" v-model="textSearch" placeholder="搜索"/>

                    <div class="is-roaster-container">
                        <input type="checkbox" v-model="isRoaster"/> <label>烘焙店</label>
                    </div>

                    <div class="brew-methods-container">
                        <div class="filter-brew-method" v-on:click="toggleBrewMethodFilter( method.method )"
                             v-bind:class="{'active' : brewMethods.indexOf( method.method ) > -1 }"
                             v-for="method in cafeBrewMethods">
                            {{ method.method }}
                        </div>
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
                textSearch: '',
                tags: '',
                isRoaster: false,
                brewMethods: []
            }
        },
        computed: {
            cafeBrewMethods() {
                return this.$store.getters.getBrewMethods;
            },
        },
        methods: {
            toggleBrewMethodFilter(method) {
                if (this.brewMethods.indexOf(method) > -1) {
                    this.brewMethods.splice(this.brewMethods.indexOf(method), 1);
                } else {
                    this.brewMethods.push(method);
                }
            },
            updateFilterDisplay() {
                EventBus.$emit('filters-updated', {
                    text: this.textSearch,
                    tags: this.tags,
                    roaster: this.isRoaster,
                    brew_methods: this.brewMethods
                });
            }
        },
        watch: {
            textSearch() {
                this.updateFilterDisplay();
            },

            isRoaster() {
                this.updateFilterDisplay();
            },

            brewMethods() {
                this.updateFilterDisplay();
            }
        },
    }
</script>