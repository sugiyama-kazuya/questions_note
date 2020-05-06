<template>
  <div class="absolute inset-x-0 top-0">
    <header class="flex mb-2 h-30 p-3 bg-indigo-400">
      <div class="w-1/3"></div>
      <div class="w-1/3 text-center text-white flex items-center justify-center">
        <slot name="titleName"></slot>
      </div>
      <div class="w-1/3"></div>
    </header>
    <slot name="createBtn"></slot>
  </div>
</template>

<script>
export default {
  name: "Header"
};
</script>
