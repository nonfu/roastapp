<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div.company {
        font-family: "Lato", sans-serif;
        border-bottom: 1px solid black;
        padding-bottom: 15px;
        padding-top: 15px;
        color: black;
        &:hover {
            background-color: #EEEEEE;
            cursor: pointer;
        }
    }
</style>

<template>
    <router-link :to="{ name: 'admin-company', params: { id: company.id } }" v-show="searchValidated">
        <div class="company">
            <div class="grid-x">
                <div class="large-3 medium-3 cell">
                    {{ company.name }}
                </div>
                <div class="large-5 medium-5 cell">
                    {{ company.website }}
                </div>
                <div class="large-2 medium-2 cell">
                    {{ company.cafes_count }}
                </div>
                <div class="large-2 medium-2 cell">
                    {{ company.actions_count }}
                </div>
            </div>
        </div>
    </router-link>
</template>

<script>
    export default {
        props: ['company', 'search'],
        
        computed: {
            
            searchValidated() {
                
                if (this.search === '') {
                    return true;
                }
                
                if (this.search !== '' && this.company.name.search(this.search) > -1) {
                    return true;
                }
                
                return false;
            }
        }
    }
</script>
