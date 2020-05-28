<template>
  <div class="relative h-100">
    <div class="h-90 bg-gray-200">
      <Header class="h-8">
        <template v-slot:titleName>
          <h5>HOME</h5>
        </template>
      </Header>

      <div class="flex justify-center items-center h-10 px-3 round-sm">
        <ChangeTabBtn
          @left-click="newArrivalsOrder"
          @right-click="popularOrder"
          :isLeftActive="displayTab.isNewActive"
          :isRightActive="displayTab.isPopularActive"
        >
          <template v-slot:leftBtnText>新着</template>
          <template v-slot:rightBtnText>人気順</template>
        </ChangeTabBtn>
      </div>

      <main class="overflow-y-scroll h-82 py-2">
        <Loading if="isLoading" :loading="isLoading" />
        <ProblemCard
          v-for="cardData in problemCardData"
          :key="cardData.id"
          :cardData="cardData"
          class="mb-4"
        ></ProblemCard>
      </main>
    </div>
    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import ProblemCard from "../components/ProblemCard";
import Loading from "../components/Loading";
import ChangeTabBtn from "../components/ChangeTabBtn";

export default {
  name: "Index",
  components: {
    Header,
    Footer,
    ProblemCard,
    Loading,
    ChangeTabBtn
  },
  data() {
    return {
      problemCardData: {},
      isLoading: false,
      displayTab: {
        isNewActive: false,
        isPopularActive: false
      }
    };
  },
  async mounted() {
    this.isLoading = true;
    await this.$store.dispatch("listProblem/getProblmeCard");
    this.problemCardData = this.$store.state.listProblem.problemCardData.exercise_books;

    this.displayTab.isNewActive = true;

    this.$nextTick(function() {
      this.isLoading = false;
    });
  },
  methods: {
    async newArrivalsOrder() {
      this.displayTab.isPopularActive = false;
      this.displayTab.isNewActive = true;

      this.isLoading = true;
      await this.$store.dispatch("listProblem/getProblmeCard");

      this.problemCardData = await this.$store.state.listProblem.problemCardData
        .exercise_books;
      this.isLoading = false;
    },
    async popularOrder() {
      this.displayTab.isNewActive = false;
      this.displayTab.isPopularActive = true;

      this.isLoading = true;
      const response = await axios
        .get("/api/orderFavorite")
        .catch(error => error.response || error);

      console.log(response.data);

      this.problemCardData = response.data.exercise_books;
      this.isLoading = false;
    }
  }
};
</script>

<style scoped>
.st-border {
  border: 1px solid #667eea;
}
</style>
