<template>
  <!-- modal -->
  <div class="modal fade" data-backdrop="static" :id="`modal-${idModal}`">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ title }}</h4>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            :disabled="loadstate"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <slot />
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal" :disabled="loadstate">
            Close
          </button>
          <button @click="$emit('event')" type="button" class="btn btn-primary" :disabled="loadstate">
            <div v-if="!loadstate">
              {{ buttonText }}
            </div>
            <partial-load v-if="loadstate" />
          </button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</template>

<script>
import { mapState } from 'vuex'
export default {
  props: {
    title: {
      type: String,
      default: ''
    },
    idModal: {
      type: String,
      default: ''
    },
    buttonText: {
      type: String,
      default: ''
    }
  },
  computed: {
    ...mapState('LoadState',[
      'loadstate'
    ])
  }
}
</script>
