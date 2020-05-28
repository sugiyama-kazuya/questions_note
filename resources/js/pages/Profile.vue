<template>
  <div class="relative h-100">
    <div class="h-90 bg-gray-200">
      <Header class="h-8">
        <template v-slot:titleName>
          <h5>Mypage</h5>
        </template>
      </Header>

      <main class="overflow-y-scroll h-92">
        <div class="bg-white shadow-xl">
          <div class="text-right py-3 px-3">
            <DefaultBtn :height="'h-3rem'" :width="'w-1/3'">
              <template>フォローする</template>
            </DefaultBtn>
          </div>
          <div class="flex flex-col justify-center items-center">
            <div class>
              <img
                class="rounded-full h-12rem w-48"
                src="http://placeimg.com/350/250/people"
                alt="プロフィール画像"
              />
            </div>
            <div class="p-3">
              <span>kazuya</span>
            </div>
          </div>
          <div class="flex items-center justify-center p-4">
            <div class="mr-4">フォロー 1</div>
            <div>フォロワー 2</div>
          </div>
        </div>

        <div class="flex justify-center items-center h-10 px-3 my-3 round-sm">
          <ChangeTabBtn
            @left-click="ownExerciseBooks"
            @right-click="favoriteOrder"
            :isLeftActive="displayTab.isOwnExerciseBooks"
            :isRightActive="displayTab.isFavoriteOrder"
          >
            <template v-slot:leftBtnText>問題</template>
            <template v-slot:rightBtnText>お気に入り</template>
          </ChangeTabBtn>
        </div>

        <Loading if="isLoading" :loading="isLoading" />
        <ProblemCard
          v-for="cardData in problemCardData"
          :key="cardData.id"
          :cardData="cardData"
          class="mb-4"
        >
          <template v-if="problemCardData" />
        </ProblemCard>
      </main>
    </div>
    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import DefaultBtn from "../components/DefaultBtn";
import ProblemCard from "../components/ProblemCard";
import Loading from "../components/Loading";
import ChangeTabBtn from "../components/ChangeTabBtn";
import { INTERNAL_SERVER_ERROR } from "../util";

export default {
  name: "Profile",
  components: {
    Header,
    Footer,
    DefaultBtn,
    ProblemCard,
    Loading,
    ChangeTabBtn
  },
  data() {
    return {
      problemCardData: {},
      displayTab: {
        isOwnExerciseBooks: true,
        isFavoriteOrder: false
      },
      isLoading: false
    };
  },
  methods: {
    async ownExerciseBooks() {
      this.displayTab.isOwnExerciseBooks = true;
      this.displayTab.isFavoriteOrder = false;

      this.isLoading = true;
      await this.getOwnExercizeBooks();
      this.isLoading = false;
    },
    async favoriteOrder() {
      this.displayTab.isOwnExerciseBooks = false;
      this.displayTab.isFavoriteOrder = true;

      this.isLoading = true;

      const response = await axios
        .get("/api/ownFavoriteExerciseBooks")
        .catch(error => error.resoponse);

      this.problemCardData = response.data.exercise_books;
      this.isLoading = false;
    },
    async getOwnExercizeBooks() {
      const url = `/api/ownExercizeBooks/${this.$route.params.userId}`;

      const response = await axios.get(url).catch(error => error.response);

      if (response.status === INTERNAL_SERVER_ERROR) {
        this.$router.push("/500");
      }

      this.problemCardData = response.data.exercise_books;
    }
  },
  async mounted() {
    this.isLoading = true;
    const exerciseBooks = await this.getOwnExercizeBooks();
    this.isLoading = false;
  }
};
</script>

<style></style>
