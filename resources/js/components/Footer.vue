<template>
  <footer class="flex py-3 bg-indigo-400 absolute inset-x-0 bottom-0 text-white">
    <div class="w-1/4 flex flex-col items-center justify-center">
      <font-awesome-icon icon="book-open" class="text-3xl" />Index
    </div>
    <div class="w-1/4 flex flex-col items-center justify-center">
      <font-awesome-icon icon="plus" class="text-3xl" />Create
    </div>
    <div class="w-1/4 flex flex-col items-center justify-center">
      <font-awesome-icon icon="home" class="text-3xl" />Home
    </div>
    <div class="w-1/4 flex flex-col items-center justify-center">
      <font-awesome-icon icon="search" class="text-3xl" />Search
    </div>
  </footer>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faHome,
  faPlus,
  faBookOpen,
  faSearch
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faHome, faPlus, faBookOpen, faSearch);

export default {
  name: "Footer",
  components: {
    FontAwesomeIcon
  }
};
</script>
