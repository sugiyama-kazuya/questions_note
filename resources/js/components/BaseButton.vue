<template>
  <button
    class="p-2 m-2 rounded-sm shadow-sm font-bold focus:outline-none outline-none"
    :class="[
            height ? height : 'h-70',
            width ? width : 'w-full',
            color ? color : 'bg-blue-400',
            text ? text : 'text-white'
        ]"
    @click="$emit('click-btn')"
    :type="type ? type : 'button'"
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
.btn-border {
  border: 1px solid #63b3ed;
}
</style>
