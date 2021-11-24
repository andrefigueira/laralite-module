<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <alert-component :alert="alert"></alert-component>
            </div><!-- End col -->
            <div class="col-md-12">
                <b-input-group size="sm" class="m-2 mr-0 pr-3">
                    <b-form-input
                        v-model="filter"
                        type="search"
                        id="filterInput"
                        placeholder="Type to Search"
                        style="padding: 18px 10px"
                        class="mr-2"
                    ></b-form-input>
                    <b-input-group-append>
                        <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                    </b-input-group-append>
                </b-input-group>

                <div class="table-responsive-sm">
                <b-table
                    hover
                    show-empty
                    ref="table"
                    :busy.sync="isBusy"
                    :items="tableDataProvider"
                    :fields="fields"
                    :per-page="perPage"
                    :current-page="currentPage"
                    :filter="filter">
                    <template v-slot:cell(actions)="row">
                        <a v-b-tooltip:hover title="Edit" class="float-right" @click="showSettingsForm(row.item, row.index, $event.target)" style="text-decoration:none">
                            <i class="ri-pencil-fill"></i>
                        </a>
                    </template>
                </b-table>
            </div>

            </div><!-- End col -->
        </div><!-- End row -->

        <div class="float-right mb-3 mt-2">
          <ul class="pagination pagination-rounded mb-0">
            <b-pagination
                class="ml-2"
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage"
            ></b-pagination>
          </ul>
        </div>
        <b-modal id="settings-modal" :title="'Edit Settings: ' + settingsComponent.name" :hide-footer="true" size="xl" @hide="clearSettings()">
            <component :is="settingsComponent.componentName" :component="settingsComponent" v-model="settingsComponent.properties"></component>
        </b-modal>
    </div>
</template>

<script>
    import AlertComponent from "./AlertComponent";
    export default {
        components: {AlertComponent},
        data() {
            return {
                // Alert settings
                alert: {
                    show: false,
                    dismissible: true,
                    message: '',
                    variant: 'success',
                    dismissCountDown: 0,
                    dismissSecs: 3
                },

                // Table settings
                fields: [
                    { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },

                    { key: 'actions', label: '' }
                ],
                totalRows: 1,
                currentPage: 1,
                perPage: 8,
                pageOptions: [5, 10, 15],
                sortBy: '',
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,
                filterOn: [],

                // Component settings
                isBusy: false,
                settingsComponent: {}
            }
        },
        methods: {
            showSettingsForm(item, index, button) {
                this.settingsComponent = item;
                this.settingsComponent.componentName = this.settingsComponent.name + 'SettingsComponent';
                this.$root.$emit('bv::show::modal', 'settings-modal', button);
            },
            clearSettings() {
                this.settingsComponentName = '';
            },
            tableDataProvider(context) {
                this.isBusy = true;

                const promise = axios.get(
                    '/api/component?page=' + context.currentPage + '&perPage=' + context.perPage + '&filter=' + context.filter + '&sortBy=' + context.sortBy + '&sortDesc=' + context.sortDesc,
                    { withCredentials: true }
                );

                return promise.then((data) => {
                    const items = data.data.data;

                    this.totalRows = data.data.total;

                    this.isBusy = false;

                    return items;
                }).catch(error => {
                    this.isBusy = false;

                    return [];
                })
            }
        }
    }
</script>
