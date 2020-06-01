<template>
  <button
    class="rounded-sm shadow-sm font-bold focus:outline-none btn-border"
    :class="[
            height ? height : 'h-70',
            width ? width : 'w-full',
            color ? color : 'bg-blue-400',
            text ? text : 'text-white'
        ]"
    @click="$emit('click-btn')"
  >
    <slot></slot>
  </button>
</template>

<script>
export default {
  name: "DefaultBtn",
  props: {
    height: {
      type: String
    },
    width: {
      type: String
    },
    color: {
      type: String
    },
    text: {
      type: String
    }
  }
};
</script>

<style scoped>
.btn-border {
  border: 1px solid #63b3ed;
}
</style>
