<template>
    <div>
        <b-alert :show="!loading && !showResults" class="m-3">No pages added yet &middot; <a href="/admin/pages/create">Create one</a></b-alert>

        <div v-show="loading" class="text-center">
            <b-spinner label="Spinning"></b-spinner>
        </div>

        <div class="table table-top-border-0" v-show="showResults">
            <div class="row table-header">
                <div class="col-md-4">Name</div>
                <div class="col-md-2">Slug</div>
                <div class="col-md-2">Type</div>
                <div class="col-md-2">Template</div>
                <div class="col-md-2"></div>
            </div><!-- End row -->

            <recursive-table-row :data="pages"></recursive-table-row>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');

            this.load();
        },
        data() {
            return {
                loading: true,
                showResults: false,
                pages: []
            }
        },
        methods: {
            load() {
                axios.get('/api/page?with=children').then(response => {
                    this.pages = response.data.data;

                    if (this.pages.length > 0) {
                        this.showResults = true;
                    }

                    this.loading = false;
                }).catch(error => {
                    // handle error
                });
            }
        }
    }
</script>
