<template>
  <div class="flex flex-col h-100 py-5">
    <div class="h-90 px-3 py-4">
      <div class="bg-white h-100 shadow-sm rounded-sm">
        <div class="bg-blue-300 h-8 flex items-center justify-center">
          <h1 v-if="problemDisplay" class="text-white text-center text-base m-0">問題</h1>
          <h1 v-if="answerDisplay" class="text-white text-center text-base m-0">解答</h1>
        </div>
        <div class="h-82">
          <div class="flex justify-center items-center overflow-y-scroll h-100">
            <p
              v-if="problemData.problem"
              v-show="problemDisplay"
              class="m-0 p-4"
            >{{problemData.problem[currentProblem].content}}</p>
            <p
              v-if="problemData.problem"
              v-show="answerDisplay"
              class="m-0 p-4"
            >{{problemData.problem[currentProblem].answer}}</p>
          </div>
        </div>

        <div class="h-10 flex justify-end items-center px-3 py-1">
          <font-awesome-icon icon="ellipsis-v" class="text-2xl text-gray-600"></font-awesome-icon>
        </div>
      </div>
    </div>
    <div class="h-10 px-3 flex items-center">
      <button
        v-if="problemDisplay"
        @click="showAnswer"
        class="bg-blue-300 w-full h-70 rounded-sm shadow-sm text-white font-bold"
      >答えを見る</button>
      <button
        v-if="answerDisplay"
        @click="nextProblem"
        class="bg-blue-300 w-full h-70 rounded-sm shadow-sm text-white font-bold"
      >次の問題へ</button>
    </div>
  </div>
</template>

<script>
import { library } from "@fortawesome/fontawesome-svg-core";
import { faEllipsisV } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faEllipsisV);

export default {
  name: "ProblemPlayCrad",
  components: {
    FontAwesomeIcon
  },
  props: {
    problemData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      currentProblem: 0,
      problemDisplay: true,
      answerDisplay: false
    };
  },
  methods: {
    showAnswer() {
      this.problemDisplay = false;
      this.answerDisplay = true;
    },
    nextProblem() {
      this.answerDisplay = false;
      this.problemDisplay = true;
      this.currentProblem++;
    }
  }
};
</script>

<style>
</style>
