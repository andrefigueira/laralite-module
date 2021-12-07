<template>
<!--  <popup-modal-component ref="popup">-->
    <transition name="fade">
      <div class="popup-modal" v-if="isVisible">
        <div class="window">
    <h5 style="margin-top: 0">{{ title }}</h5>
    <p style="margin-top: 20px">{{ message }}</p>
    <div class="btns">
      <b-button variant="danger" class="cancel-btn" @click="_cancel" style="float: right">{{ cancelButton }}</b-button>
      <b-button variant="warning" class="ok-btn mr-2" @click="_confirm">{{ okButton }}</b-button>
    </div>
        </div>
      </div>
    </transition>
<!--  </popup-modal-component>-->
</template>

<script>
export default {
  name: "ConfirmDialogueComponent",

  data: () => ({
    // Parameters that change depending on the type of dialogue
    isVisible: false,
    title: undefined,
    message: undefined, // Main text content
    okButton: undefined, // Text for confirm button; leave it empty because we don't know what we're using it for
    cancelButton: 'Exit', // text for cancel button

    // Private variables
    resolvePromise: undefined,
    rejectPromise: undefined,
  }),

  methods: {
    open() {
      this.isVisible = true
    },
    close() {
      this.isVisible = false
    },
    show(opts = {}) {
      this.title = opts.title
      this.message = opts.message
      this.okButton = opts.okButton
      if (opts.cancelButton) {
        this.cancelButton = opts.cancelButton
      }
      // Once we set our config, we tell the popup modal to open
      this.open()
      // Return promise so the caller can get results
      return new Promise((resolve, reject) => {
        this.resolvePromise = resolve
        this.rejectPromise = reject
      })
    },

    _confirm() {
      this.close()
      this.resolvePromise(true)
    },

    _cancel() {
      this.close()
      this.resolvePromise(false)
      // Or you can throw an error
      // this.rejectPromise(new Error('User cancelled the dialogue'))
    },
  },
}
</script>

<style scoped>
/* css class for the transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.popup-modal {
  background-color: rgba(0, 0, 0, 0.5);
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  z-index: 1;
}

.window {
  background: #fff;
  border-radius: 5px;
  box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
  max-width: 480px;
  margin-left: auto;
  margin-right: auto;
  padding: 1rem;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
.btns {
  /*display: flex;*/
  flex-direction: row;
  text-align-last: right;
  /*justify-content: space-between;*/
}

.ok-btn, .cancel-btn {
  color: red;
  text-decoration: none;
  line-height: 2.5rem;
  cursor: pointer;
}

</style>
