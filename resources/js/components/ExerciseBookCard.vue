<template>
  <div class="bg-white mx-3 rounded-sm shadow-sm px-2 flex flex-column">
    <div @click="goProblem(cardData.id)" class="flex flex-column">
      <div class="text-right p-2">カテゴリ</div>
      <div class="px-2 pb-4 pt-2">{{ cardData.exerciseBooksName.name }}</div>
    </div>
    <div class="border-t p-3 flex items-center justify-between text-gray-600">
      <div class="flex items-center w-1/3">
        <router-link
          tag="div"
          :to="{
                        name: 'profile',
                        params: {
                            userId: cardData.user.id
                        }
                    }"
          class="flex w-100"
        >
          <font-awesome-icon
            v-if="!cardData.user.profile_img"
            icon="user-circle"
            class="text-3xl mr-2"
          />
          <img
            v-if="cardData.user.profile_img"
            class="h-2rem w-2rem mr-2 bg-cover img-profile"
            :src="cardData.user.profile_img"
          />
          <span class="flex items-center">{{ cardData.user.name }}</span>
        </router-link>
      </div>
      <div class="flex w-1/3">
        <font-awesome-icon
          @click="isLike(cardData.id)"
          icon="star"
          class="text-2xl mr-2"
          :class="{ 'is-like': isLikedBy }"
        />
        <span>{{ count }}</span>
      </div>
      <div class="w-1/3 text-right">{{ cardData.updated_at }}</div>
    </div>
  </div>
</template>

<script>
import { INTERNAL_SERVER_ERROR } from "../util";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faUserCircle, faStar } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faUserCircle, faStar);

export default {
  name: "ExerciseBookCard",
  props: {
    cardData: {
      type: Object,
      required: true
    }
  },
  components: {
    FontAwesomeIcon
  },
  data() {
    return {
      isLikedBy: false,
      count: ""
    };
  },
  methods: {
    goProblem(exerciseBooksId) {
      this.$router.push({
        name: "problem",
        params: { id: exerciseBooksId }
      });
    },
    async isLike(problemId) {
      // 既にいいねしていた場合、していなかった場合
      if (this.isLikedBy) {
        console.log("true");
        const response = await axios
          .delete("/api/unlike", { data: { id: problemId } })
          .catch(error => error.response);

        if (response.status === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
          return;
        }

        this.isLikedBy = false;
      } else {
        const response = await axios
          .put("/api/like", { id: problemId })
          .catch(error => error.response || error);

        if (response.status === INTERNAL_SERVER_ERROR) {
          this.$router.push("/500");
          return;
        }

        this.isLikedBy = true;
      }
    },
    async isLikedByApi(id) {
      const url = `/api/islikedby/${id}`;
      const response = await axios
        .get(url)
        .catch(error => error.response || error);

      if (response.status === INTERNAL_SERVER_ERROR) {
        this.$router.push("/500");
        return;
      }

      return response.data.isLike;
    },
    async countLikes(id) {
      const countLikesUrl = `/api/countLikes/${id}`;
      const response = await axios
        .get(countLikesUrl)
        .catch(error => error.response);

      if (response.status === INTERNAL_SERVER_ERROR) {
        this.$router.push("/500");
        return;
      }

      return response.data.count;
    }
  },
  async mounted() {
    console.log(this.cardData);
    this.count = this.cardData.favolite_count;
    this.isLikedBy = this.cardData.is_liked_by;
    const isLike = await this.isLikedByApi(this.cardData.id);

    isLike ? (this.isLikedBy = isLike) : (this.isLikedBy = false);
  },
  watch: {
    async isLikedBy(newIsLikedBy, oldIsLikedBy) {
      const countLikes = await this.countLikes(this.cardData.id);
      countLikes ? (this.count = countLikes) : (this.count = 0);
    }
  }
};
</script>

<style scoped>
.is-like {
  color: red;
}

.img-profile {
  border-radius: 50%;
}
</style>