<template>
  <div class="relative h-100">
    <div class="h-90 bg-gray-200">
      <TheHeader class="h-8">
        <template v-slot:titleName>
          <h5>Mypage</h5>
        </template>
      </TheHeader>

      <main class="overflow-y-scroll h-92">
        <div class="flex bg-white shadow-xl py-3 px-4">
          <div class="flex flex-col w-1/2">
            <div class="text-center p-2">
              <span>{{userData.name}}</span>
            </div>
            <div class="flex justify-center">
              <div class="w-50 relative img-circle">
                <font-awesome-icon
                  v-if="!userData.profile_img"
                  icon="user-circle"
                  class="m-0 h-100 w-100 absolute inset-0 img-profile mr-2"
                />

                <img
                  v-if="userData.profile_img"
                  class="m-0 h-100 bg-cover absolute inset-0 img-profile"
                  :src="userData.profile_img"
                  alt="プロフィール画像"
                />
              </div>
            </div>
          </div>
          <div class="w-1/2 flex flex-col text-right">
            <div class="w-full h-30 flex justify-end">
              <BaseButton
                v-if="!isLoginUser"
                @click-btn="isFollow"
                :text="followBtnTextColor"
                :color="followBtnBgColor"
                :height="'h-3rem'"
                :width="'w-2/3'"
                class="h-100"
              >
                <template>{{followBtnText}}</template>
              </BaseButton>
              <BaseButton
                v-if="isLoginUser"
                @click-btn="goEditScreen"
                :text="editBtn.btnTextColor"
                :color="editBtn.btnBgColor"
                :height="'h-3rem'"
                :width="'w-1/3'"
                class="h-100"
              >
                <template>{{editBtn.btnText}}</template>
              </BaseButton>
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
            v-if="isLoginUser"
            @left-click="ownExerciseBooks"
            @right-click="favoriteOrder"
            :isLeftActive="displayTab.isOwnExerciseBooks"
            :isRightActive="displayTab.isFavoriteOrder"
          >
            <template v-slot:leftBtnText>問題</template>
            <template v-slot:rightBtnText>お気に入り</template>
          </ChangeTabBtn>
          <div v-else class="w-1/3">
            <div class="bg-indigo-500 py-2 px-4 text-white text-center">問題</div>
          </div>
        </div>

        <ExerciseBookCard
          v-for="cardData in ExerciseBookCardData"
          :key="cardData.id"
          :cardData="cardData"
          class="mb-4"
        >
          <template v-if="ExerciseBookCardData" />
        </ExerciseBookCard>
      </main>
      <!-- フラッシュメッセージ -->
      <transition name="center-modal">
        <CenterModal v-if="isFlashMsg">
          <div class="p-2 bg-gray-800 text-white rounded-md">{{flashMsg.text}}</div>
        </CenterModal>
      </transition>
    </div>
    <TheFooter />

    <!-- ローディング -->
    <transition name="center-modal">
      <TheLoading if="loading.isLoading" :loading="loading.isLoading" :opacity="loading.opacity" />
    </transition>
  </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import BaseButton from "../components/BaseButton";
import ExerciseBookCard from "../components/ExerciseBookCard";
import TheLoading from "../components/TheLoading";
import ChangeTabBtn from "../components/ChangeTabBtn";
import CenterModal from "../components/CenterModal";
import { INTERNAL_SERVER_ERROR, OK } from "../util";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faUserCircle } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faUserCircle);

export default {
  name: "Profile",

  components: {
    TheHeader,
    TheFooter,
    BaseButton,
    ExerciseBookCard,
    TheLoading,
    ChangeTabBtn,
    CenterModal,
    FontAwesomeIcon
  },

  data() {
    return {
      ExerciseBookCardData: {},
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
      loading: {
        isLoading: false,
        opacity: 1
      },
      isFollowedBy: false,
      followingsCount: "",
      followersCount: "",
      flashMsg: {
        text: "登録が完了しました。",
        speed: 2000
      }
    };
  },

  computed: {
    followBtnBgColor() {
      return this.isFollowedBy ? "bg-blue-400" : "bg-white";
    },
    followBtnTextColor() {
      return this.isFollowedBy ? "text-white" : "text-blue-400";
    },
    followBtnText() {
      return this.isFollowedBy ? "フォロー中" : "フォローする";
    },
    isLoginUser() {
      const paramsUserId = parseInt(this.$route.params.id);
      const loginUserId = parseInt(this.$store.state.auth.user.id);
      return loginUserId === paramsUserId ? true : false;
    },
    isFlashMsg() {
      return this.$store.state.flashMessage.visible;
    }
  },

  watch: {
    async isFollowedBy() {
      const url = `/api/user/${this.$route.params.id}/followers`;
      const response = await axios
        .get(url)
        .catch(error => error.response || error);

      this.followersCount = response.data.follower_count;
    },
    async $route() {
      this.loading.isLoading = true;
      await this.getOwnExercizeBooks();
      await this.getUser();
      this.loading.isLoading = false;
    }
  },

  async created() {
    this.loading.isLoading = true;
    await this.getOwnExercizeBooks();
    await this.getUser();
    this.loading.isLoading = false;

    // フラッシュメッセージがある場合
    if (this.isFlashMsg) {
      this.$store.dispatch("flashMessage/hideFlashMsg", this.flashMsg.speed);
    }
  },

  methods: {
    async getUser() {
      const url = `/api/profile/${this.$route.params.id}`;
      const response = await axios
        .get(url)
        .catch(error => error.response || error);

      this.userData = response.data.user;
      this.followersCount = response.data.user.followers_count;
      this.followingsCount = response.data.user.followings_count;
      this.isFollowedBy = response.data.user.is_followed_by;
    },

    async getOwnExercizeBooks() {
      const url = `/api/exercise-books/${this.$route.params.id}`;

      const response = await axios.get(url).catch(error => error.response);

      if (response.status === INTERNAL_SERVER_ERROR) {
        this.$router.push("/500");
      }

      this.ExerciseBookCardData = response.data.exercise_books;
    },
    async ownExerciseBooks() {
      this.displayTab.isOwnExerciseBooks = true;
      this.displayTab.isFavoriteOrder = false;
      this.loading.opacity = 0;
      this.loading.isLoading = true;
      await this.getOwnExercizeBooks();
      this.loading.isLoading = false;
    },
    async favoriteOrder() {
      this.displayTab.isOwnExerciseBooks = false;
      this.displayTab.isFavoriteOrder = true;

      this.loading.opacity = 0;
      this.loading.isLoading = true;
      const response = await axios
        .get("/api/own-favorite-exercise-books")
        .catch(error => error.resoponse);

      if (response.status === OK) {
        this.ExerciseBookCardData = response.data.exercise_books;
      }
      this.loading.isLoading = false;
    },
    async isFollow() {
      const url = `/api/user/${this.$route.params.id}/follow`;
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
      this.$router.push(`/profile/${this.$route.params.id}/edit`);
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

.center-modal-enter-active,
.center-modal-leave-active {
  transition: opacity 0.2s;
}

.center-modal-enter,
.center-modal-leave-to {
  opacity: 0;
}
</style>
