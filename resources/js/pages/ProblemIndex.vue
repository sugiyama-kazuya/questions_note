<template>
  <div class="relative h-100">
    <div class="h-90 bg-gray-200">
      <TheHeader class="h-8">
        <template v-slot:titleName>
          <h5>HOME</h5>
        </template>
      </TheHeader>

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
        <ExerciseBookCard
          v-for="cardData in ExerciseBookCardData"
          :key="cardData.id"
          :cardData="cardData"
          class="mb-4"
        ></ExerciseBookCard>
      </main>
    </div>
    <TheFooter />
    <TheLoading if="isLoading" :loading="isLoading" />
  </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import ExerciseBookCard from "../components/ExerciseBookCard";
import TheLoading from "../components/TheLoading";
import ChangeTabBtn from "../components/ChangeTabBtn";

import { OK, INTERNAL_SERVER_ERROR } from "../util";

export default {
  name: "Index",
  components: {
    TheHeader,
    TheFooter,
    ExerciseBookCard,
    TheLoading,
    ChangeTabBtn
  },
  data() {
    return {
      ExerciseBookCardData: {},
      isLoading: false,
      displayTab: {
        isNewActive: false,
        isPopularActive: false
      }
    };
  },
  async mounted() {
    this.isLoading = true;
    const response = await axios
      .get("api/problems")
      .catch(error => error.response || error);

    console.log(this.axios);

    if (response.status === INTERNAL_SERVER_ERROR) {
      this.$router.push("/500");
      return;
    }

    if (response.status === OK) {
      this.ExerciseBookCardData = response.data.exercise_books;
      this.displayTab.isNewActive = true;
    }

    this.$nextTick(function() {
      console.log(this.isLoading);
      this.isLoading = false;
    });
  },
  methods: {
    async newArrivalsOrder() {
      this.displayTab.isPopularActive = false;
      this.displayTab.isNewActive = true;

      this.isLoading = true;
      await this.$store.dispatch("listProblem/getProblmeCard");

      this.ExerciseBookCardData = await this.$store.state.listProblem
        .problemCardData.exercise_books;
      this.isLoading = false;
    },
    async popularOrder() {
      this.displayTab.isNewActive = false;
      this.displayTab.isPopularActive = true;

      this.isLoading = true;
      const response = await axios
        .get("/api/order-favorite-exercise-books")
        .catch(error => error.response || error);

      console.log(response.data);

      this.ExerciseBookCardData = response.data.exercise_books;
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
