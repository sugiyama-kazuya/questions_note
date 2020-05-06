<template>
  <div class="relative h-full">
    <header class="flex mb-4 h-20 absolute inset-x-0 top-0">
      <div class="w-1/3 bg-blue-300"></div>
      <div class="w-1/3 bg-blue-300 text-center text-white flex items-center justify-center">
        <h5 class="m-0">Index</h5>
      </div>
      <div class="w-1/3 bg-blue-300"></div>
    </header>

    <footer class="flex h-16 bg-blue-300 absolute inset-x-0 bottom-0 text-white">
      <div class="w-1/4 flex flex-col items-center justify-center">
        <font-awesome-icon icon="home" class="text-3xl" />Home
      </div>
      <div class="w-1/4 flex flex-col items-center justify-center">
        <font-awesome-icon icon="home" class="text-3xl" />Create
      </div>
      <div class="w-1/4 flex flex-col items-center justify-center">
        <font-awesome-icon icon="home" class="text-3xl" />Status
      </div>
      <div class="w-1/4 flex flex-col items-center justify-center">
        <font-awesome-icon icon="home" class="text-3xl" />Search
      </div>
    </footer>
  </div>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faHome } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faHome);

export default {
  name: "Index",
  components: {
    FontAwesomeIcon
  }
};
</script>
