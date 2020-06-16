<template>
  <input
    @input="$emit('input', $event.target.value)"
    :value="value"
    :class="[
        bgColor ? bgColor : '',
        placeholder ? placeholder : '',
        height ? height : 'h-3rem'
        ]"
    type="text"
    class="mb-3 p-2 shadow-sm appearance-none border rounded w-full focus:outline-none focus:shadow-outline"
  />
</template>

<script>
export default {
  name: "FormText",
  props: {
    value: {
      type: String,
      required: true
    },
    height: {
      type: String
    },
    bgColor: {
      type: String
    },
    placeholder: {
      type: String
    }
  }
};
</script>

<style>
</style>
