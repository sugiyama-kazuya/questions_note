<template>
  <div class="px-3 py-1 bg-blue-400 text-sm text-white rounded-lg">{{name}}</div>
</template>

<script>
export default {
  name: "CategoryTag",
  props: {
    name: {
      type: String,
      required: true
    }
  }
};
</script>

<style>
</style>
