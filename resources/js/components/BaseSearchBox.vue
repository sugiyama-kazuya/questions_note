<template>
  <div class="flex justify-center w-screen mb-4 px-4">
    <form class="flex w-4/5 rounded-full">
      <input
        type="text"
        class="w-5/6 py-2 px-3 bg-gray-200 rounded-l-full focus:outline-none"
        :placeholder="placeholder ? placeholder : ''"
      />
      <button class="w-1/6 bg-blue-300 rounded-r-full">
        <FontAwesomeIcon icon="search" class="text-1xl" />
      </button>
    </form>
  </div>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faSearch } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faSearch);

export default {
  name: "BaseSearchBox",
  components: {
    FontAwesomeIcon
  },
  props: {
    placeholder: {
      type: String,
      required: true
    }
  },
  data() {
    return {};
  }
};
</script>

<style>
</style>
