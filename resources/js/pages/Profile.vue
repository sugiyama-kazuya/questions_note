<template>
  <div class="relative h-100">
    <div class="h-90 bg-gray-200">
      <Header class="h-8">
        <template v-slot:titleName>
          <h5>Mypage</h5>
        </template>
      </Header>

      <main class="overflow-y-scroll h-92">
        <div class="flex bg-white shadow-xl py-3 px-4">
          <div class="flex flex-col w-1/2">
            <div class="text-center p-2">
              <span>{{userData.name}}</span>
            </div>
            <div class="flex justify-center">
              <div class="w-50 relative img-circle">
                <img
                  class="m-0 h-100 bg-cover absolute inset-0 img-profile"
                  src="http://placeimg.com/350/250/people"
                  alt="プロフィール画像"
                />
              </div>
            </div>
          </div>
          <div class="w-1/2 flex flex-col text-right">
            <div class="w-full h-30 flex justify-end">
              <DefaultBtn
                v-if="!isLoginUser"
                @click-btn="isFollow"
                :text="followBtnTextColor"
                :color="followbBtnBgColor"
                :height="'h-3rem'"
                :width="'w-2/3'"
                class="h-100"
              >
                <template>{{followBtnText}}</template>
              </DefaultBtn>
              <DefaultBtn
                v-if="isLoginUser"
                @click-btn="goEditScreen"
                :text="editBtn.btnTextColor"
                :color="editBtn.btnBgColor"
                :height="'h-3rem'"
                :width="'w-1/3'"
                class="h-100"
              >
                <template>{{editBtn.btnText}}</template>
              </DefaultBtn>
            </div>
            <div class="h-70 flex flex-col items-center justify-end">
              <div class="flex flex-col">
                <span class="mb-2 text-left">フォロー {{followingsCount}}</span>
                <span class="text-left">フォロワー {{followersCount}}</span>
              </div>
            </div>
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
      userData: {},
      displayTab: {
        isOwnExerciseBooks: true,
        isFavoriteOrder: false
      },
      editBtn: {
        btnText: "編集",
        btnTextColor: "text-blue-400",
        btnBgColor: "bg-white"
      },
      isLoading: false,
      isFollowedBy: false,
      followingsCount: "",
      followersCount: ""
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

      console.log(response.data);

      this.problemCardData = response.data.exercise_books;
    },
    async isFollow() {
      const url = `/api/user/${this.$route.params.userId}/follow`;
      if (this.isFollowedBy) {
        this.isFollowedBy = false;
        const response = await axios
          .delete(url)
          .catch(error => error.response || error);

        if (response.status === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
          return;
        }
      } else {
        this.isFollowedBy = true;
        const response = await axios
          .put(url)
          .catch(error => error.response || error);

        if (response.status === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
          return;
        }
      }
    },
    goEditScreen() {
      this.$router.push(`/profile/${this.$route.params.userId}/edit`);
    }
  },
  async mounted() {
    this.isLoading = true;
    const exerciseBooks = await this.getOwnExercizeBooks();
    const userUrl = `/api/profile/${this.$route.params.userId}`;
    const response = await axios
      .get(userUrl)
      .catch(error => error.response || error);

    this.userData = response.data.user;
    this.followersCount = response.data.user.followers_count;
    this.followingsCount = response.data.user.followings_count;
    this.isFollowedBy = response.data.user.is_followed_by;
    this.isLoading = false;
  },
  computed: {
    followBtnBgColor() {
      return this.isFollowedBy ? "bg-blue-400" : "white";
    },
    followBtnTextColor() {
      return this.isFollowedBy ? "text-white" : "text-blue-400";
    },
    followBtnText() {
      return this.isFollowedBy ? "フォロー中" : "フォローする";
    },
    isLoginUser() {
      const paramsUserId = parseInt(this.$route.params.userId);
      const loginUserId = parseInt(this.$store.state.auth.user.id);
      return loginUserId === paramsUserId ? true : false;
    }
  },
  watch: {
    async isFollowedBy() {
      const url = `/api/user/${this.$route.params.userId}/followers`;
      const response = await axios
        .get(url)
        .catch(error => error.response || error);

      this.followersCount = response.data.follower_count;
    }
  }
};
</script>

<style scoped lang="scss">
.img-circle:before {
  display: block;
  content: "";
  padding-bottom: 100%;
}

.img-profile {
  border-radius: 50%;
}
</style>
