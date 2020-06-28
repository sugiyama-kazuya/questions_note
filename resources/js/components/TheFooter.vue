<template>
  <footer class="flex py-3 bg-indigo-400 absolute inset-x-0 bottom-0 h-10 text-white">
    <div class="w-1/4 flex flex-col items-center justify-center">
      <router-link
        to="/exercise-books"
        tag="div"
        class="flex flex-col items-center justify-center"
        active-class="text-yellow-300"
        exact
      >
        <font-awesome-icon icon="book-open" class="text-3xl" />Index
      </router-link>
    </div>
    <div class="w-1/4 flex flex-col items-center justify-center">
      <router-link
        to="/problems/create"
        tag="div"
        class="flex flex-col items-center justify-center"
        active-class="text-yellow-300"
        exact
      >
        <font-awesome-icon icon="plus" class="text-3xl" />Create
      </router-link>
    </div>
    <div class="w-1/4 flex flex-col items-center justify-center">
      <router-link
        :to="{ name: 'profile', params: { id: loginUserId } }"
        tag="div"
        class="flex flex-col items-center justify-center"
        active-class="text-yellow-300"
        exact
      >
        <font-awesome-icon icon="home" class="text-3xl" />Home
      </router-link>
    </div>
    <div class="w-1/4 flex flex-col items-center justify-center">
      <router-link
        to="/search"
        tag="div"
        class="flex flex-col items-center justify-center"
        active-class="text-yellow-300"
        exact
      >
        <font-awesome-icon icon="search" class="text-3xl" />Search
      </router-link>
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
  name: "TheFooter",
  components: {
    FontAwesomeIcon
  },
  computed: {
    loginUserId() {
      return this.$store.state.auth.user.id;
    }
  }
};
</script>
