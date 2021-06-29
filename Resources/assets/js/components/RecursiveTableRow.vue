<template>
    <div v-show="data.length > 0">
        <div class="row" v-for="page in data" v-bind:key="page.id">
            <div class="col-md-12 col-sm-12 col-xs-12 table-row">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-3">
                        <span :class="'ml-' + nodeLevel">&rdsh;</span>
                        <a class="text-dark" :href="'/admin/pages/edit/' + page.id">{{ page.name }}</a>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3">{{ page.slug }}</div>
                    <div class="col-md-2 col-sm-2 col-xs-2"><b-badge class="badge-soft-warning" :variant="page.primary === 1 ? 'primary' : 'secondary'">{{ page.primary === 1 ? 'Primary' : 'Standard' }}</b-badge></div>
                    <div class="col-md-2 col-sm-2 col-xs-2"><b-badge variant="primary" class="badge-soft-primary">{{ getTemplateName(page) }}</b-badge></div>
                    <div class="col-md-2 col-sm-2 col-xs-2">
                        <a v-b-tooltip:hover title="Delete" @click="confirmDelete(page)" class="float-right row-button"><i class="ri-delete-bin-6-fill"></i></a>
                        <a v-b-tooltip:hover title="Edit" :href="'/admin/pages/edit/' + page.id" class="float-right mr-3 row-button"><i class="ri-pencil-fill"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12" v-show="page.children.length > 0">
                <recursive-table-row :nodeLevel="nodeLevel + 2" :parentSlug="page.slug" :data="page.children"></recursive-table-row>
            </div><!-- End col -->
        </div><!-- End col -->
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            data: {
                type: Array,
                default: []
            },
            parentSlug: {
                type: String,
                default: ''
            },
            nodeLevel: {
                type: Number,
                default: 0
            }
        },
        methods: {
            getTemplateName(page) {
                if (page.template === null) {
                    return 'ERROR';
                }

                if (page.template.name === undefined) {
                    return 'ERROR';
                }

                return page.template.name;
            },
            confirmDelete(page) {
                if (page.primary === 1) {
                    this.$bvModal.msgBoxOk('Cannot delete primary page').then(value => {
                        return false;
                    }).catch(error => {
                        return false;
                    });

                    return false;
                }

                if (page.children.length > 0) {
                    this.$bvModal.msgBoxOk('Unable to delete page, children exist').then(value => {
                        return false;
                    }).catch(error => {
                        return false;
                    });

                    return false;
                }

                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        let index = this.data.indexOf(page);
                        let self = this;

                        axios.delete('/api/page/' + page.id).then(response => {
                            self.data.splice(index, 1);
                        }).catch(error => {
                            // handle error
                        });
                    }
                }).catch(error => {
                    // An error occurred
                });
            }
        }
    }
</script>

<style lang="scss">
    .row-button {
        margin-top: -4px;
        margin-bottom: 4px;
    }
</style>
