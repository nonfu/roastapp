<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#admin-companies {
        div.companies-header {
            font-family: "Lato", sans-serif;
            border-bottom: 1px solid black;
            font-weight: bold;
            padding-bottom: 10px;
            img.sort-icon {
                display: inline-block;
                margin-left: 10px;
            }
            div.sortable-header {
                cursor: pointer;
            }
        }
        div.no-companies-available {
            text-align: center;
            font-family: "Lato", sans-serif;
            font-size: 20px;
            padding-top: 20px;
            padding-bottom: 20px;
        }
    }
</style>

<template>
    <div id="admin-companies">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-12 medium-12 cell">
                    <h3 class="page-header">所有公司</h3>
                </div>
            </div>
        </div>

        <div class="grid-container">
            <div class="grid-x">
                <input type="text" v-model="search" placeholder="通过公司名称搜索"/>
            </div>
        </div>

        <div class="grid-container">
            <div class="grid-x companies-header">
                <div class="large-3 medium-3 cell sortable-header" v-on:click="resortCafes('name')">
                    公司名称
                    <img class="sort-icon" src="/storage/img/sort-asc.svg"
                         v-show="sortBy === 'name' && sortDirection === 'ASC'"/>
                    <img class="sort-icon" src="/storage/img/sort-desc.svg"
                         v-show="sortBy === 'name' && sortDirection === 'DESC'"/>
                </div>
                <div class="large-5 medium-5 cell">
                    网站
                </div>
                <div class="large-2 medium-2 cell sortable-header" v-on:click="resortCafes('cafes')">
                    咖啡店
                    <img class="sort-icon" src="/storage/img/sort-asc.svg"
                         v-show="sortBy === 'cafes' && sortDirection === 'ASC'"/>
                    <img class="sort-icon" src="/storage/img/sort-desc.svg"
                         v-show="sortBy === 'cafes' && sortDirection === 'DESC'"/>
                </div>
                <div class="large-2 medium-2 cell sortable-header" v-on:click="resortCafes('pending-actions')">
                    待审核动作
                    <img class="sort-icon" src="/storage/img/sort-asc.svg"
                         v-show="sortBy === 'pending-actions' && sortDirection === 'ASC'"/>
                    <img class="sort-icon" src="/storage/img/sort-desc.svg"
                         v-show="sortBy === 'pending-actions' && sortDirection === 'DESC'"/>
                </div>
            </div>

            <company v-for="company in companies"
                     :key="company.id"
                     :company="company"
                     :search="search">
            </company>

            <div class="large-12 medium-12 cell no-companies-available" v-show="companies.length === 0">
                No companies available
            </div>
        </div>
    </div>
</template>

<script>
    import Company from '../../components/admin/companies/Company.vue';

    export default {
        data() {
            return {
                sortBy: 'name',
                sortDirection: 'ASC',
                search: ''
            }
        },

        components: {
            Company
        },

        created() {
            this.$store.dispatch('loadAdminCompanies');
        },

        computed: {
            companies() {
                return this.$store.getters.getCompanies;
            },

            companiesLoadStatus() {
                return this.$store.getters.getCompaniesLoadStatus;
            }
        },

        watch: {
            'companiesLoadStatus': function () {
                this.resortCafes('name');
            }
        },

        methods: {
            resortCafes(by) {
                if (by === this.sortBy) {
                    if (this.sortDirection === 'ASC') {
                        this.sortDirection = 'DESC';
                    } else {
                        this.sortDirection = 'ASC';
                    }
                }

                if (by !== this.sortBy) {
                    this.sortDirection = 'ASC';
                    this.sortBy = by;
                }

                switch (this.sortBy) {
                    case 'name':
                        this.sortCompaniesByName();
                        break;
                    case 'cafes':
                        this.sortCompaniesByCafes();
                        break;
                    case 'pending-actions':
                        this.sortCompaniesByPendingActions();
                        break;
                }
            },

            sortCompaniesByName() {
                this.companies.sort(function (a, b) {
                    if (this.sortDirection === 'ASC') {
                        return ((a.name === b.name) ? 0 : ((a.name > b.name) ? 1 : -1));
                    }
                    if (this.sortDirection === 'DESC') {
                        return ((a.name === b.name) ? 0 : ((a.name < b.name) ? 1 : -1));
                    }
                }.bind(this));
            },

            sortCompaniesByCafes() {
                this.companies.sort(function (a, b) {
                    if (this.sortDirection === 'ASC') {
                        return parseInt(a.cafes_count) < parseInt(b.cafes_count) ? 1 : -1;
                    }
                    if (this.sortDirection === 'DESC') {
                        return parseInt(a.cafes_count) > parseInt(b.cafes_count) ? 1 : -1;
                    }
                }.bind(this));
            },

            sortCompaniesByPendingActions() {
                this.companies.sort(function (a, b) {
                    if (this.sortDirection === 'ASC') {
                        return parseInt(a.actions_count) < parseInt(b.actions_count) ? 1 : -1;
                    }
                    if (this.sortDirection === 'DESC') {
                        return parseInt(a.actions_count) > parseInt(b.actions_count) ? 1 : -1;
                    }
                }.bind(this));
            }
        }
    }
</script>