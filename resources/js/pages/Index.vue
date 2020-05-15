<template>
  <div class="relative h-100">
    <div class="h-90 bg-gray-200">
      <Header class="h-8">
        <template v-slot:titleName>
          <h5>HOME</h5>
        </template>
      </Header>

      <div class="flex justify-center items-center h-10 px-3 round-sm">
        <div class="inline-flex w-4/5 st-border h-60">
          <button class="w-1/2 bg-indigo-500 text-white py-2 px-4">新着</button>
          <button class="w-1/2 bg-white py-2 px-4 text-indigo-500">人気順</button>
        </div>
      </div>

      <main class="overflow-y-scroll h-82 py-2">
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

export default {
  name: "Index",
  components: {
    Header,
    Footer,
    ProblemCard
  },
  data() {
    return {
      problemCardData: []
    };
  },
  async created() {
    await this.$store.dispatch("listProblem/getProblmeCard");
    this.problemCardData = await this.$store.state.listProblem.problemCardData;
    console.log(this.problemCardData);
  }
};
</script>

<style scoped>
.st-border {
  border: 1px solid #667eea;
}
</style>
