<template>
  <div>
    <div class="row">
      <div class="col-lg-12">
        <div class="admin-title-section pt-2">
          <a @click="goBack" class="back-btn p-0">
            <b-icon icon="arrow-left" font-scale="1"></b-icon>
          </a>
          <span class="admin-title pl-2">
                      {{
              type === 'create' ? 'Create new product' + (form.name !== '' ? ' &rarr; ' + form.name : '') : 'Edit product &rarr; ' + product.name
            }}
                  </span>
        </div><!-- End admin title section -->

        <b-alert :show="alertShow" :variant="alertType" v-html="alertMessage" dismissible></b-alert>
      </div><!-- End col -->
    </div><!-- End row -->

    <div class="row">
      <div class="col-lg-12">
        <div class="page-section">
          <form-wizard shape="tab" color="#333" error-color="#ff4949" finish-button-text="Save & Publish">
            <template slot="title">
              <div></div>
            </template>
            <tab-content title="Product Information" icon="fas fa-file"
                         :before-change="()=>validateProductInformation()">
              <div class="row">
                <div class="col-md-3">
                  <p class="alert alert-danger" v-if="$v.form.images.$error"><i class="fas fa-exclamation-circle"></i>
                    Must upload an image</p>
                  <image-upload-component @image-removed="removeUploadedImage"
                                          @image-uploaded="setUploadedImage"></image-upload-component>

                  <label class="mt-3" for="primary-option">Product category</label>
                  <v-select class="mb-3" id="primary-option" label="name" v-model="category" :options="categoryOptions"
                            :clearable="false"></v-select>
                </div><!-- End col -->
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-md-6 col-sm-12">
                      <b-form-group id="product-name-group" label="Product name" label-for="product-name">
                        <b-form-input
                            id="product-name-input"
                            required
                            v-model="form.name"
                            :state="validateState('name')"
                            placeholder="Enter product name"
                        ></b-form-input>
                        <b-form-invalid-feedback>Enter a valid name with more than 3 characters
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </div><!-- End col -->
                    <div class="col-md-6 col-sm-12">
                      <b-form-group id="product-url-group" label="Product URL" label-for="product-url">
                        <b-form-input
                            id="product-url-input"
                            required
                            v-model="form.slug"
                            :state="validateState('slug')"
                            placeholder="Enter product url"
                        ></b-form-input>
                        <b-form-invalid-feedback>Enter a valid url with more than 3 characters</b-form-invalid-feedback>
                      </b-form-group>
                    </div><!-- End col -->
                  </div><!-- End row -->

                  <div class="row">
                    <div class="col-sm-12">
                      <b-form-group id="product-description-group" label="Product description"
                                    label-for="product-description">
                        <ckeditor
                            :editor="editor"
                            v-model="form.description"
                            :config="editorConfig"
                        ></ckeditor>
                        <b-form-invalid-feedback>Enter a valid description</b-form-invalid-feedback>
                      </b-form-group>
                      <b-form-group id="product-active-group" label="Activate" label-for="product-active"
                                    class="pt-md-3">
                        <b-form-checkbox v-model="form.active" name="check-button" switch>
                        </b-form-checkbox>
                      </b-form-group>
                      <b-form-group id="product-credit_purchasable-group" label="Credit Purchasable" label-for="product-credit_purchasable"
                                    class="pt-md-3">
                        <b-form-checkbox v-model="form.credit_purchasable" name="check-button" switch>
                        </b-form-checkbox>
                      </b-form-group>
                    </div>
                  </div>
                </div><!-- End col -->
              </div><!-- End row -->
            </tab-content>
            <tab-content title="Pricing & Variants" icon="fas fa-dollar-sign">
              <p>Define the options of your item, create variants, and track stock levels.
                Each row in the table below represents a variant on this product. You
                can add additional options to those variants by adding columns.</p>

              <b-modal id="image-select" ref="image-select" title="Select Variant Image" :hide-footer="true">
                <div class="variant-image-selector">
                  <div class="row">
                    <image-upload-component :show-preview="false" class="col-6 mb-1"
                                            @image-removed="removeUploadedVariantImage"
                                            @image-uploaded="setUploadedVariantImage"></image-upload-component>

                    <div class="col-6 mb-1" v-for="(uploadedImage, imageIndex) in form.images">
                      <div :style="{'background-image': 'url(' + uploadedImage + ')'}" class="uploaded-image-preview"
                           :class="{'active': selectedVariantImage.index === imageIndex && selectedVariantImage.path !== ''}"
                           @click="selectVariantImage(uploadedImage, imageIndex)"></div>
                      <!-- End uploaded image preview -->
                    </div>
                  </div><!-- End row -->
                </div>
                <button class="btn btn-theme w-100 mt-1" @click="applyVariantImage()">Apply</button>
              </b-modal>

              <table class="table table-responsive-sm border-bottom">
                <tr>
                  <th width="10%">Image</th>
                  <th width="15%">SKU</th>
                  <th width="15%">Pricing</th>
                  <th width="10%">Credits</th>
                  <th width="10%">Stock</th>
                  <th width="10%">Weight <i v-b-tooltip.hover
                                            title="Dimensions in pounds, used for calculating shipping cost"
                                            class="fas fa-info-circle"></i></th>
                  <th width="15%">Dimensions <i v-b-tooltip.hover
                                                title="Dimensions in inches, used for calculating shipping cost"
                                                class="fas fa-info-circle"></i></th>
                  <th width="15%">Groupable</th>
                  <th></th>
                </tr>
                <tr v-for="(variant, index) in form.variants">
                  <td class="align-middle">
                    <a href="#" :style="{'background-image': 'url(' + variant.image + ')'}"
                       class="variant-image-placeholder dark-link" @click="showImageSelectModal(index)">
                      <i v-if="variant.image === ''" class="far fa-image"></i>
                    </a>
                  </td>
                  <td class="align-middle">
                    <a href="#" class="dark-link" v-b-tooltip.hover title="Click to edit" v-if="editingSku !== index"
                       @click="editingSku=index">{{ variant.sku }}</a>

                    <b-form-group class="position-relative mb-0" v-if="editingSku === index">
                      <b-form-input minlength="1" maxlength="15" style="max-width: 100%;" class="pr-2"
                                    v-model="variant.sku"></b-form-input>
                      <a href="#" class="inline-edit-tick" @click="stopEditingSku(variant.sku)"><i
                          class="fas fa-check"></i></a>
                    </b-form-group>
                  </td>
                  <td class="align-middle">
                    <a href="#" :id="'pricing-edit-' + index" class="dark-link">
                      <span :class="{'strikethrough': variant.pricing.on_sale }">{{ variant.pricing.price }}</span>
                      <span>{{ variant.pricing.on_sale ? '$' + variant.pricing.sale_price : '' }}</span>
                    </a>

                    <b-popover :target="'pricing-edit-' + index" triggers="hover" placement="bottom">
                      <b-form-group label="Price">
                        <b-form-input min="0" type="number" v-model="variant.pricing.price"></b-form-input>
                      </b-form-group>
                      <b-form-group label="Sale Price">
                        <b-form-input min="0" type="number" v-model="variant.pricing.sale_price"></b-form-input>
                      </b-form-group>
                      <b-form-group>
                        <b-form-checkbox
                            v-model="variant.pricing.on_sale"
                            :value="true"
                            :unchecked-value="false">
                          On Sale
                        </b-form-checkbox>
                      </b-form-group>
                    </b-popover>
                  </td>
                  <td class="align-middle">
                    <a href="#" :id="'credit-edit-' + index" class="dark-link">
                      <span>{{ variant.pricing.credits ? variant.pricing.credits : '0' }} <b-badge v-if="!form.credit_purchasable" variant="warning">disabled</b-badge></span>
                    </a>

                    <b-popover :target="'credit-edit-' + index" triggers="hover" :disabled="!form.credit_purchasable" placement="bottom">
                      <b-form-group label="Credits">
                        <b-form-input min="0" type="number" v-model="variant.pricing.credits"></b-form-input>
                      </b-form-group>
                    </b-popover>
                  </td>
                  <td class="align-middle">
                    <a href="#" :id="'stock-edit-' + index" class="dark-link">
                      <span>{{ variant.stock < 0 ? '&infin;' : variant.stock }} {{
                          variant.stock === '' ? 0 : ''
                        }}</span>
                    </a>

                    <b-popover :target="'stock-edit-' + index" triggers="hover" placement="bottom">
                      <b-form-group label="Stock">
                        <b-form-input min="0" type="number" pattern="[0-9\.\-]*" v-model="variant.stock"
                                      required></b-form-input>
                      </b-form-group>
                      <b-form-group>
                        <b-form-checkbox
                            v-model="variant.stock"
                            :value="-1"
                            :unchecked-value="1">
                          Unlimited
                        </b-form-checkbox>
                      </b-form-group>
                    </b-popover>
                  </td>
                  <td class="align-middle">
                    <a href="#" class="dark-link" v-b-tooltip.hover title="Click to edit" v-if="editingWeight !== index"
                       @click="editingWeight=index">{{ variant.weight }}lb</a>

                    <b-form-group class="position-relative mb-0" v-if="editingWeight === index">
                      <b-form-input minlength="1" maxlength="15" style="max-width: 100%;" class="pr-2"
                                    v-model="variant.weight" pattern="[0-9\.\-]*"></b-form-input>
                      <a href="#" class="inline-edit-tick" @click="stopEditingWeight(variant.weight)"><i
                          class="fas fa-check"></i></a>
                    </b-form-group>
                  </td>
                  <td class="align-middle">
                    <a href="#" :id="'dimensions-edit-' + index" class="dark-link">
                      <span>{{
                          variant.dimensions.length === 0 && variant.dimensions.height === 0 && variant.dimensions.width === 0 ? '-' : variant.dimensions.length + '&times;' + variant.dimensions.width + '&times;' + variant.dimensions.height
                        }}</span>
                    </a>

                    <b-popover :target="'dimensions-edit-' + index" triggers="hover" placement="bottom">
                      <b-form-group label="Length">
                        <b-form-input min="0" type="number" v-model="variant.dimensions.length"
                                      pattern="[0-9\.\-]*"></b-form-input>
                      </b-form-group>
                      <b-form-group label="Width">
                        <b-form-input min="0" type="number" v-model="variant.dimensions.width"
                                      pattern="[0-9\.\-]*"></b-form-input>
                      </b-form-group>
                      <b-form-group label="Height">
                        <b-form-input min="0" type="number" v-model="variant.dimensions.height"
                                      pattern="[0-9\.\-]*"></b-form-input>
                      </b-form-group>
                    </b-popover>
                  </td>
                  <td class="align-middle">
                    <a href="#" class="dark-link" v-b-tooltip.hover title="Click to edit"
                       v-if="editingGroupable !== index"
                       @click="editingGroupable=index">{{ variant.groupable === false ? 'No' : 'Yes' }}</a>

                    <b-form-group class="position-relative mb-0" v-if="editingGroupable === index">
                      <b-form-select v-model="variant.groupable" :options="groupableOptions"></b-form-select>
                      <a href="#" class="inline-edit-tick" style="right: 25px;"
                         @click="stopEditingGroupable(variant.groupable)"><i class="fas fa-check"></i></a>
                    </b-form-group>
                  </td>
                  <td class="align-middle">
                    <b-button v-if="form.variants.length > 1" @click="removeVariant(variant)" variant="default"
                              size="sm" class="float-right"><i class="far fa-trash-alt"></i></b-button>
                  </td>
                </tr>
              </table>

              <b-button v-b-tooltip.hover title="Click to add a new product variant" @click="addNewVariant()"
                        variant="default" size="sm"><i class="fas fa-plus"></i></b-button>
            </tab-content>
            <tab-content title="SEO" icon="fas fa-globe-europe">
              <div class="row">
                <div class="col-lg-12">
                  <p>Search engine optimization (SEO) allows you to improve your ranking in search results. Use these
                    features to make it easier for users to find this item when they search for it.</p>
                  <div class="hr mb-3"></div>
                </div>
                <div class="col-md-6 col-sm-12">
                  <b-form-group id="product-seo-title-group" label="SEO Title" label-for="product-seo-title">
                    <b-form-input
                        id="product-seo-title-input"
                        required
                        v-model="form.meta.title"
                        placeholder="Enter SEO title"
                    ></b-form-input>
                  </b-form-group>

                  <b-form-group id="product-seo-keywords-group" label="SEO Keywords" label-for="product-seo-keywords">
                    <b-form-input
                        id="product-seo-keywords-input"
                        required
                        v-model="form.meta.keywords"
                        placeholder="Enter SEO keywords"
                    ></b-form-input>
                  </b-form-group>

                  <b-form-group id="product-seo-description-group" label="SEO Description"
                                label-for="product-seo-description">
                    <b-form-textarea
                        id="product-seo-description-input"
                        required
                        v-model="form.meta.description"
                        placeholder="Enter SEO description"
                    ></b-form-textarea>
                  </b-form-group>
                </div><!-- End col -->
                <div class="col-md-6 col-sm-12">
                  <h6>Search Result Preview</h6>

                  <div class="search-result-preview mb-3">
                    <div class="title">{{ form.meta.title ? form.meta.title : 'Example Page Title - Website' }}</div>
                    <div class="url">https://website.com/{{ form.url }}</div>
                    <div class="description">
                      {{ form.meta.description ? form.meta.description : 'Lorem ipsum dolor sit amet...' }}
                    </div>
                  </div><!-- End search result preview -->

                  <p>Search results typically show your SEO title and description. Your title is also the browser window
                    title, and matches your title formats. Depending on the search engine, descriptions displayed can be
                    50 to 300 characters long. If you don’t add a title or description, search engines will use this
                    item's title and content.</p>
                </div><!-- End col -->
              </div><!-- End row -->
            </tab-content>
            <tab-content title="Summary" icon="fas fa-clipboard-check">
              Finished adding all product detail, you can go back and check or press the save & publish below to finish.
            </tab-content>
            <template slot="footer" slot-scope="props">
              <div class="wizard-footer-left">
                <wizard-button v-if="props.activeTabIndex > 0 && !props.isLastStep" @click.native="props.prevTab()"
                               class="btn btn-primary" :style="props.fillButtonStyle"><i class="fas fa-arrow-left"></i>
                  Previous
                </wizard-button>
              </div>
              <div class="wizard-footer-right">
                <wizard-button v-if="!props.isLastStep" @click.native="props.nextTab()"
                               class="btn btn-primary wizard-footer-right" :style="props.fillButtonStyle">Next <i
                    class="fas fa-arrow-right"></i></wizard-button>

                <wizard-button v-else @click.native="save()" class="btn btn-success wizard-footer-right finish-button"
                               :style="props.fillButtonStyle">{{ props.isLastStep ? 'Save & Publish' : 'Next' }}
                </wizard-button>
              </div>
            </template>
          </form-wizard>
        </div><!-- End page section -->
      </div><!-- End col -->
    </div><!-- End row -->
  </div>
</template>

<script>
import {FormWizard, TabContent} from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
import {bus} from '../admin'
import helpers from "../helpers";
import {validationMixin} from 'vuelidate'
import {required, minLength} from 'vuelidate/lib/validators'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
  mixins: [validationMixin],
  components: {
    FormWizard,
    TabContent
  },
  mounted() {
    console.log('Component mounted.');
    this.load();
  },
  props: {
    type: {
      type: String,
      default: 'create'
    },
    product: {
      type: Object,
      default: function () {
        return {};
      }
    },
  },
  data() {
    return {
      saving: false,
      alertShow: false,
      alertType: 'primary',
      alertMessage: '',
      category: {},
      categoryOptions: [],
      form: {
        id: '',
        name: '',
        description: this.product.description,
        active: false,
        credit_purchasable: false,
        category_id: '',
        meta: {
          title: '',
          keywords: '',
          description: ''
        },
        variants: [
          {
            sku: 'WZ12345',
            image: '',
            pricing: {
              price: '0.00',
              sale_price: '0.00',
              on_sale: false,
              credits: '0'
            },
            stock: 0,
            weight: 0,
            dimensions: {
              length: 0,
              width: 0,
              height: 0
            },
            groupable: false,
          }
        ],
        images: []
      },
      editor: ClassicEditor,
      editorConfig: {
        // The configuration of the rich-text editor.
      },
      editingSku: false,
      editingWeight: false,
      editingDimensions: false,
      editingGroupable: false,
      selectedVariantImage: {
        index: 0,
        path: ''
      },
      selectedVariantIndex: 0,
      groupableOptions: [
        {value: true, text: 'Yes'},
        {value: false, text: 'No'},
      ],
    }
  },
  validations: {
    form: {
      name: {
        required,
        minLength: minLength(3)
      },
      slug: {
        required,
        minLength: minLength(3)
      },
      description: {
        required,
        minLength: minLength(3)
      },
      images: {
        required,
      }
    }
  },
  computed: {
    noProductDefined() {
      return this.product.id !== undefined;
    },
    button() {
      if (this.type === 'create') {
        return 'Create';
      }

      return 'Save changes'
    },
    formEndpoint() {
      let endpoint = '/api/product';

      if (this.type === 'edit') {
        endpoint = '/api/product/' + this.product.id;
      }

      return endpoint;
    },
    formMethod() {
      let method = 'post';

      if (this.type === 'edit') {
        method = 'patch';
      }

      return method;
    },
    isActive() {
      return this.currentState;
    },

    checkedValue: {
      get() {
        return this.defaultState
      },
      set(newValue) {
        this.currentState = newValue;
      }
    }
  },
  methods: {
    goBack() {
      window.history.back();
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
    loadProductCategoryOptions(defaultOption) {
      axios.get('/api/product-category-list', {withCredentials: true}).then(response => {
        this.categoryOptions = response.data;
        // console.log(this.categoryOptions);

        if (this.product.category_id === undefined) {
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
    load() {
      if (this.product.id !== undefined) {
        this.form.id = this.product.id;
        this.form.credit_purchasable = this.product.credit_purchasable;
        this.category_id = this.category.id;
        this.form.name = this.product.name;
        this.form.slug = this.product.slug;
        this.form.description = this.product.description;
        this.form.active = this.product.active === 1;
        this.form.meta = this.product.meta;
        this.form.images = this.product.images;
        this.form.variants.pop();
        for (let i=0; i<this.product.variants.length; i++) {
          let variant = this.product.variants[i];
          variant.pricing.price = helpers.priceFormat(variant.pricing.price);
          variant.pricing.sale_price = helpers.priceFormat(variant.pricing.sale_price);
          this.form.variants.push(variant);
        }
      }

      let defaultProductCategoryValue = {
        id: null,
        name: 'No category'
      };

      this.loadProductCategoryOptions(defaultProductCategoryValue);
    },
    save() {
      this.$v.form.$touch();

      this.saving = true;

      this.form.category_id = this.category.id;

      axios({
        method: this.formMethod,
        url: this.formEndpoint,
        data: this.form
      }).then(response => {
        this.saving = false;

        bus.$emit('product-created', response.data.data);

        if (this.type === 'create') {
          window.location.replace('/admin/product');
        }

        this.alertShow = true;
        this.alertMessage = 'Saved changes to product';
        this.alertType = 'success';
      }).catch(error => {
        this.saving = false;

        // 409 status code: conflict, i.e. already exists in system
        if (error.response.status === 409) {
          console.log('Product already exists in system');

          this.alertShow = true;
          this.alertType = 'danger';
          this.alertMessage = 'Product already exists!';

          return;
        }

        if (error.response.status === 422) {
          console.log('Validation failed');

          this.alertShow = true;
          this.alertType = 'danger';
          this.alertMessage = helpers.createErrorsList(error.response.data.errors);

          return;
        }

        console.log(error);

        this.alertShow = true;
        this.alertType = 'danger';
        this.alertMessage = 'Failed to create product try again later';
      });
    },
    generateSlug() {
      this.sectionSlug = this.sectionName.toLowerCase()
          .replace(/[^\w ]+/g, '')
          .replace(/ +/g, '-');
    },
    setUploadedImage(path) {
      this.form.images[0] = path;
      this.$v.form['images'].$touch();
    },
    removeUploadedImage() {
      this.form.images = [];
      this.$v.form['images'].$touch();
    },
    validateProductInformation() {
      return new Promise((resolve, reject) => {
        this.$v.form.$touch();

        switch (true) {
          case this.form.name === '':
            reject('Name must not be empty');
            break;
          case this.form.url === '':
            reject('URL must not be empty');
            break;
          case this.form.description === '':
            reject('Name must not be empty');
            break;
          case this.form.images[0] === undefined:
            reject('Must upload an image');
            break;
          default:
            console.log('validation passed');
            resolve(true);
        }
      })
    },
    stopEditingSku(sku) {
      if (sku.length < 1) {
        return false;
      }

      this.editingSku = false;
    },
    stopEditingWeight(weight) {
      this.editingWeight = false;
    },
    stopEditingGroupable(groupable) {
      this.editingGroupable = false;
    },
    addNewVariant() {
      this.form.variants.push({
        sku: 'WZ12345',
        image: '',
        pricing: {
          price: '0.00',
          sale_price: '0.00',
          on_sale: false,
          credits: '0',
        },
        stock: 0,
        weight: 0,
        dimensions: {
          length: 0,
          width: 0,
          height: 0
        },
        groupable: false,
      });
    },
    removeVariant(variant) {
      let index = this.form.variants.indexOf(variant);

      this.form.variants.splice(index, 1);
    },
    setUploadedVariantImage(path) {
      this.form.images.push(path);
      this.form.variants.image = path;
      this.$v.form['images'].$touch();
    },
    removeUploadedVariantImage() {
      // this.form.images = [];
      this.$v.form['images'].$touch();
    },
    selectVariantImage(path, index) {
      this.selectedVariantImage = {
        index: index,
        path: path
      }
    },
    applyVariantImage() {
      this.form.variants[this.selectedVariantIndex].image = this.selectedVariantImage.path;
      this.$refs['image-select'].hide();
    },
    showImageSelectModal(index) {
      this.selectedVariantIndex = index;
      this.$refs['image-select'].show();
    }
  }
}
</script>

<style lang="scss">
.wizard-header {
  display: none;
}

.wizard-navigation {
  .wizard-progress-with-circle {
    display: none;
  }

  .wizard-nav {
    border-bottom: 1px solid #F1F1F1;
    padding-bottom: 0.4rem;

    li {
      display: block;
      margin-top: -0.2rem;

      a {
        display: block;

        .tab_shape {
          outline: none !important;
        }

        .wizard-icon {
          font-style: normal;
          font-size: 1rem;
        }

        .stepTitle {
          display: block;
          padding: 0.6rem 0;
          font-size: 0.8rem;
        }
      }

      &.active {
        a {
          .wizard-icon {
            font-size: 1rem !important;
          }
        }
      }
    }
  }
}

.inline-edit-tick {
  position: absolute;
  right: 8px;
  top: 7px;
  color: #333;
  z-index: 999;
}

.variant-image-placeholder {
  display: block;
  padding: 0.9rem 0;
  border: 1px solid #D9D9D9;
  width: 50px;
  height: 50px;
  text-align: center;
  vertical-align: middle;
  border-radius: 1px;
  background-size: cover;
  background-position: center;

  i {
    vertical-align: middle;
    font-size: 1.3rem;
  }
}

.search-result-preview {
  border: 1px solid #CCC;
  padding: 0.6rem;

  .title {
    color: #1A0DAB;
  }

  .url {
    color: #006621;
  }

  .description {

  }
}

.popover {
  max-width: 400px;

  .form-control {
    font-size: 0.8rem;
  }
}

.variant-image-selector {
  max-height: 460px;
  overflow-y: scroll;
  overflow-x: hidden;
  padding-right: 0.5rem;

  .image-upload-wrapper {
    padding: 4.5rem 0;
  }
}

.uploaded-image-preview {
  width: 100%;
  padding-bottom: 100%;
  background-size: cover;
  background-position: center;
  cursor: pointer;
  border: 3px solid #333;

  img {
    width: 100%;
    height: auto;
  }

  &.active {
    border: 3px solid #3853d8;
  }
}

/* .wizard-nav-pills {
   text-align: -webkit-center !important;
 }
 .wizard-icon-circle {
   border-color: rgb(86, 100, 210) !important;
 }

 .active .wizard-progress-bar {
   background-color: rgb(86, 100, 210) !important;
   color: rgb(86, 100, 210) !important;
   width: 16.6667% !important;
 }*/

</style>
