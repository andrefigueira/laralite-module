<template>
    <div>
        <b-alert :show="items.length < 1">No pages added yet</b-alert>
        <b-table striped hover :items="pages">
            <template v-slot:cell(actions)="page">
                <b-button @click="confirmDelete(page.item.id, page.index)" variant="danger" size="sm" class="float-right">Delete</b-button>
                <a :href="'/admin/pages/edit/' + page.item.id" class="btn btn-sm btn-primary float-right mr-1">Edit</a>
            </template>
        </b-table>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            items: {
                type: Array,
                default: []
            }
        },
        computed: {
            pages: function () {
                return this.items.map(function (page) {
                    return {
                        id: page.id,
                        name: page.name,
                        slug: '/' + page.slug,
                        actions: ''
                    };
                });
            }
        },
        data() {
            return {

            }
        },
        methods: {
            confirmDelete(pageId, index) {
                this.$bvModal.msgBoxConfirm('Are you sure?').then(value => {
                    if (value) {
                        this.deletePage(pageId, index);
                    }
                }).catch(error => {
                    // An error occurred
                });
            },
            deletePage(pageId, index) {
                let self = this;
                self.items.splice(index, 1);

                this.$set(this, 'items', self.items);


                // axios.delete('/api/page/' + pageId).then(response => {
                //     self.items.splice(index, 1);
                // }).catch(error => {
                //
                // });
            }
        }
    }
</script>
