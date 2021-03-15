<template>
  <span
    v-if="$options.filters.newBadge(anchor)"
    class="right badge badge-info"
    >Updated</span
  >
</template>

<script>
export default {
  props: {
    anchor: {
      type: String,
      default: ''
    }
  }
}
</script>
