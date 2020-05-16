<template>
  <div
    @click="goProblem(cardData.id)"
    class="bg-white mx-3 rounded-sm shadow-sm px-2 flex flex-column"
  >
    <div class="flex flex-column">
      <div class="text-right p-2">カテゴリ</div>
      <div class="px-2 pb-4 pt-2">{{cardData.exerciseBooksName.name}}</div>
    </div>
    <div class="border-t p-1 flex items-center justify-between text-gray-600">
      <div class="flex items-center">
        <font-awesome-icon icon="user-circle" class="text-2xl mr-2"></font-awesome-icon>
        {{cardData.user.name}}
      </div>
      <div class="flex">
        <font-awesome-icon icon="star" class="text-2xl mr-2" />
        <span>3</span>
      </div>
      <div>{{cardData.updated_at}}</div>
    </div>
  </div>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faUserCircle, faStar } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faUserCircle, faStar);

export default {
  name: "ProblemCard",
  props: {
    cardData: {
      type: Object,
      required: true
    }
  },
  components: {
    FontAwesomeIcon
  },
  methods: {
    goProblem(exerciseBooksId) {
      this.$router.push({ name: "problem", params: { id: exerciseBooksId } });
    }
  }
};
</script>

<style scoped>
</style>
