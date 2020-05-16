<template>
  <div class="relative h-100">
    <div class="h-90 bg-gray-200">
      <Header class="h-8">
        <template v-if="backButton" v-slot:leftSide>
          <font-awesome-icon icon="arrow-left" @click="returnBack" class="text-3xl text-white"></font-awesome-icon>
        </template>
        <template v-slot:titleName>
          <h5 class="text-2xl m-0">{{currentProblem}}/{{problemData.count}}</h5>
        </template>
        <template v-slot:rightSide>
          <font-awesome-icon icon="times" class="text-4xl text-white"></font-awesome-icon>
        </template>
      </Header>
      <main class="h-92">
        <Loading v-if="isLoading" :loading="isLoading" />
        <div v-if="!isLoading" class="flex flex-col h-100 py-5">
          <div class="h-90 px-3 py-4">
            <div class="bg-white h-100 shadow-sm rounded-sm">
              <div class="bg-blue-300 h-8 flex items-center">
                <div class="w-1/3 pl-3 flex items-center">
                  <font-awesome-icon
                    icon="exchange-alt"
                    v-if="problemInitial"
                    @click="swapProblemAnswer"
                    class="text-2xl text-gray-600"
                  ></font-awesome-icon>
                </div>
                <div class="w-1/3">
                  <h1 v-if="problemDisplay" class="text-white text-center text-base m-0">問題</h1>
                  <h1 v-if="answerDisplay" class="text-white text-center text-base m-0">解答</h1>
                </div>
                <div class="w-1/3"></div>
              </div>
              <div class="h-82">
                <div class="flex justify-center items-center overflow-y-scroll h-100">
                  <p
                    v-if="problemData.problem"
                    v-show="problemDisplay"
                    class="m-0 p-4"
                  >{{problemData.problem[problemNumber].content}}</p>
                  <p
                    v-if="problemData.problem"
                    v-show="answerDisplay"
                    class="m-0 p-4"
                  >{{problemData.problem[problemNumber].answer}}</p>
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
      </main>
    </div>
    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import Loading from "../components/Loading";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faTimes,
  faEllipsisV,
  faArrowLeft,
  faExchangeAlt
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faTimes, faEllipsisV, faArrowLeft, faExchangeAlt);

export default {
  name: "Problem",
  components: {
    Header,
    Footer,
    FontAwesomeIcon,
    Loading
  },
  data() {
    return {
      problemData: {},
      problemNumber: 0,
      currentProblem: 1,
      problemDisplay: true,
      answerDisplay: false,
      problemInitial: false,
      isLoading: false
    };
  },
  async mounted() {
    this.isLoading = true;
    await this.$store.dispatch(
      "playProblem/getProblemAndAnswer",
      this.$route.params.id
    );
    this.isLoading = false;

    this.problemData = this.$store.state.playProblem.problemData.problem_data;
  },
  methods: {
    showAnswer() {
      this.problemDisplay = false;
      this.answerDisplay = true;
      this.problemInitial = true;
    },
    nextProblem() {
      this.problemInitial = false;
      this.answerDisplay = false;
      this.problemDisplay = true;
      this.problemNumber++;
      this.currentProblem++;
    },
    swapProblemAnswer() {
      this.problemDisplay = this.problemDisplay ? false : true;
      this.answerDisplay = this.answerDisplay ? false : true;
    },
    returnBack() {
      this.problemNumber--;
      this.currentProblem--;
    }
  },
  computed: {
    backButton() {
      if (this.problemNumber === 0) {
        return false;
      }

      return true;
    }
  }
};
</script>

<style scoped>
</style>
