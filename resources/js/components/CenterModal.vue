<template>
  <div :class="backColor ? 'bg-color' : ''" class="modal-mask relative">
    <div :class="width ? width : 'w-2/3'" class="bg-white rounded m-0 m-auto shadow modal-view">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "CenterModal",
  props: {
    backColor: {
      type: Boolean
    },
    width: {
      type: String
    }
  }
};
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9999;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: table;
  transition: opacity 0.3s ease;
}

.bg-color {
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-view {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
