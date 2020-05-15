<template>
  <div class="relative h-100">
    <div class="h-90 bg-gray-200">
      <Header class="h-8">
        <template v-slot:titleName>
          <h5 class="text-2xl m-0">1/6</h5>
        </template>
        <template v-slot:rightSide>
          <font-awesome-icon icon="times" class="text-4xl text-white"></font-awesome-icon>
        </template>
      </Header>
      <main class="h-92">
        <ProblemPlayCard></ProblemPlayCard>
      </main>
    </div>
    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import ProblemPlayCard from "../components/ProblemPlayCard";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faTimes, faEllipsisV } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faTimes, faEllipsisV);

export default {
  name: "Problem",
  components: {
    Header,
    Footer,
    FontAwesomeIcon,
    ProblemPlayCard
  }
};
</script>

<style scoped>
</style>
