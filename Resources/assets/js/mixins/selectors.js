export default {
    productTypes() {
        axios.get('/api/product/types', {withCredentials: true}).then(response => {
            this.options = response.data.data;
            console.log(this.categoryOptions);

            if (this.product.category_id === undefined) {
                this.selected = this.categoryOptions[0];
            } else {
                this.category = this.categoryOptions.filter((productCategory) => {
                    return productCategory.id === this.product.category_id;
                })[0];
            }
        }).catch(error => {
            // handle error
        });
    },
    productCategory() {
        axios.get('/api/product-category-list', {withCredentials: true}).then(response => {
            this.options = response.data;
            // console.log(this.categoryOptions);

            if (this.selected === undefined) {
                this.category = this.categoryOptions[0];
            } else {
                this.category = this.categoryOptions.filter((productCategory) => {
                    return productCategory.id === this.product.category_id;
                })[0];
            }
        }).catch(error => {
            // handle error
        });
    },
}

