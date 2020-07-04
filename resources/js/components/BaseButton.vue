<template>
  <button
    @click="$emit('click-btn')"
    :class="[
            height ? height : '',
            width ? width : '',
            color ? color : 'bg-blue-400',
            text ? text : 'text-white'
        ]"
    :type="type ? type : 'button'"
    class="p-2 rounded-sm shadow-sm font-bold focus:outline-none outline-none"
  >
    <slot></slot>
  </button>
</template>

<script>
export default {
  name: "BaseButton",
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
    },
    type: {
      type: String
    }
  }
};
</script>

<style scoped>
</style>
