<template>
  <div class>
    <header class="flex bg-indigo-400 h-100">
      <div class="w-1/3"></div>
      <div class="w-1/3 text-center text-white flex items-center justify-center">
        <slot name="titleName"></slot>
      </div>
      <div class="w-1/3 flex justify-end items-center pr-3">
        <slot name="rightSide"></slot>
      </div>
    </header>
    <slot name="createBtn"></slot>
  </div>
</template>

<script>
export default {
  name: "Header"
};
</script>
