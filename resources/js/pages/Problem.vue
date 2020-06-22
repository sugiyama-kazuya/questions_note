<template>
  <div class="relative h-100">
    <div class="h-90 bg-gray-200">
      <TheHeader class="h-8">
        <template v-if="isBackBtnDisplay" v-slot:leftSide>
          <font-awesome-icon icon="arrow-left" @click="returnBack" class="text-3xl text-white"></font-awesome-icon>
        </template>
        <template v-slot:titleName>
          <h5
            v-if="!noProblem"
            class="text-2xl m-0"
          >{{ currentNumber }}/{{ currentProblemData.count }}</h5>
        </template>
        <template v-slot:rightSide>
          <font-awesome-icon
            v-if="!noProblem"
            @click="inMiddleEnd"
            icon="times"
            class="text-4xl text-white"
          ></font-awesome-icon>
        </template>
      </TheHeader>
      <main class="h-92">
        <div v-if="!noProblem" class="flex flex-col h-100 py-5">
          <div class="h-90 px-3 py-4">
            <div class="bg-white h-100 shadow-sm rounded-sm">
              <div class="bg-blue-300 h-8 flex items-center">
                <div class="w-1/3 pl-3 flex items-center">
                  <font-awesome-icon
                    icon="exchange-alt"
                    v-if="displaySecond"
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
                <div class="flex flex-col justify-center items-center h-100">
                  <div class="h-100 w-full overflow-y-scroll">
                    <transition name="card-text">
                      <p
                        v-if="
                            currentProblemData.problemData[orderNumber] &&
                                problemDisplay
                            "
                        class="m-0 p-4 h-100 w-full flex items-center justify-center"
                      >
                        {{
                        currentProblemData.problemData[
                        orderNumber
                        ].content
                        }}
                      </p>
                    </transition>
                    <transition name="card-text">
                      <p
                        v-if="
                            currentProblemData.problemData[orderNumber] &&
                                answerDisplay
                            "
                        class="m-0 p-4 h-100 w-full flex items-center justify-center"
                      >
                        {{
                        currentProblemData.problemData[
                        orderNumber
                        ].answer
                        }}
                      </p>
                    </transition>
                  </div>
                  <div
                    v-if="currentProblemData.problemData[orderNumber].url"
                    class="mt-auto w-full p-2 overflow-x-scroll whitespace-no-wrap"
                  >
                    参考
                    :
                    <a
                      :href="currentProblemData.problemData[orderNumber].url"
                    >{{currentProblemData.problemData[orderNumber].url}}</a>
                  </div>
                </div>

                <!-- 問題のセレクトモーダル -->
                <transition name="center-modal">
                  <CenterModal v-if="editModal" :width="'w-1/3'">
                    <div class="flex flex-col items-center py-4 px-2">
                      <CenterModalBtn
                        @click.native="goEdit()"
                        :text="'編集'"
                        :width="'w-2/3'"
                        :color="'bg-blue-400'"
                        class="mb-3"
                      ></CenterModalBtn>
                      <CenterModalBtn
                        @click.native="openDeleteConfimationModal"
                        :text="'削除'"
                        :width="'w-2/3'"
                        class="mb-3"
                      ></CenterModalBtn>
                      <CenterModalBtn
                        @click.native="closeEditModal"
                        :text="'戻る'"
                        :width="'w-2/3'"
                        :color="'bg-blue-400'"
                      ></CenterModalBtn>
                    </div>
                  </CenterModal>
                </transition>

                <!-- 問題削除時の確認モーダル -->
                <transition name="center-modal">
                  <CenterModal v-if="deleteConfimationModal" :backColor="false">
                    <div class="py-5 px-3">
                      <div class="p-4 text-center text-lg mb-2">
                        <span>現在表示されている問題と答えを削除しますが、本当によろしいですか？</span>
                      </div>
                      <div class="flex justify-center p-2">
                        <CenterModalBtn @click.native="deleteProblem" :text="'削除'" class="mr-2" />
                        <CenterModalBtn
                          @click.native="deleteConfimationModal = false"
                          :text="'問題へ戻る'"
                          :color="'bg-blue-400'"
                        />
                      </div>
                    </div>
                  </CenterModal>
                </transition>
              </div>

              <div class="h-10 flex justify-end items-center px-3 py-1">
                <font-awesome-icon
                  v-if="isProblemsLoginUser"
                  @click="openEditModal"
                  icon="ellipsis-v"
                  class="text-2xl text-gray-600"
                ></font-awesome-icon>
              </div>
            </div>
          </div>
          <div v-if="problemDisplay" class="h-10 px-3 flex items-center">
            <BaseButton @click.native="showAnswer">
              <template>答え</template>
            </BaseButton>
          </div>
          <div v-if="answerDisplay" class="h-10 px-3 flex items-center">
            <BaseButton
              :color="'bg-red-300'"
              class="mr-3"
              @click.native="
                                incorrect(
                                    currentProblemData.problemData[orderNumber].id
                                )
                            "
            >
              <template>×</template>
            </BaseButton>
            <BaseButton :color="'bg-blue-300'" @click.native="correct">
              <template>○</template>
            </BaseButton>
          </div>

          <!-- 問題削除後のフラッシュメッセージ -->
          <transition name="center-modal">
            <CenterModal v-if="isFlashMsg">
              <div class="p-2 bg-gray-800 text-white rounded-md">{{flashMsg.text}}</div>
            </CenterModal>
          </transition>
        </div>
        <!-- 表示する問題がない場合のモーダル -->
        <transition name="center-modal">
          <CenterModal v-if="noProblem" :backColor="false">
            <div class="py-5 px-3">
              <div class="p-4 text-center text-lg mb-2">
                <span>表示する問題がありません。問題を追加してください。</span>
              </div>
              <div class="flex justify-center p-2">
                <CenterModalBtn @click.native="deleteProblem" :text="'作成画面へ'" class="mr-2" />
                <CenterModalBtn
                  @click.native="deleteConfimationModal = false"
                  :text="'一覧へ戻る'"
                  :color="'bg-blue-400'"
                />
              </div>
            </div>
          </CenterModal>
        </transition>
      </main>
    </div>
    <TheFooter />
    <TheLoading v-if="loading.isLoading" :loading="loading.isLoading" :opacity="loading.opacity" />

    <!-- 全問終了後のモーダル -->
    <transition name="center-modal">
      <CenterModal v-if="isProblemEndModal">
        <template>
          <div class="p-3">
            <div v-if="isCurrectAnswerAll" class="p-3">
              <p class="text-lg">全問正解です！</p>
            </div>
            <div v-if="!isCurrectAnswerAll" class="text-center p-3">
              <p>{{ `トータル：${currentProblemData.count}` }}</p>
              <p>{{ `正解数：${currectAnswerCount}` }}</p>
              <p>{{ `不正解数：${inCurrectAnswerCount}` }}</p>
            </div>
            <div class="flex justify-end">
              <CenterModalBtn
                v-if="!isCurrectAnswerAll"
                @click.native="incorrectProblemAgain"
                :text="endModal.restartText"
                :color="'bg-blue-400'"
                class="mr-2"
              />
              <CenterModalBtn @click.native="goHome" :text="endModal.endText" />
            </div>
          </div>
        </template>
      </CenterModal>
    </transition>

    <!-- 途中終了時の確認モーダル -->
    <transition name="center-modal">
      <CenterModal v-if="endConfimationModal" :backColor="false">
        <div class="py-5 px-3">
          <div class="p-4 text-center text-lg mb-2">
            <span>本当に終了でよろしいですか？</span>
          </div>
          <div class="flex justify-center p-2">
            <CenterModalBtn
              @click.native="goHome"
              :text="'一覧へ'"
              :color="'bg-blue-400'"
              class="mr-2"
            />
            <CenterModalBtn @click.native="endConfimationModal = false" :text="'問題へ戻る'" />
          </div>
        </div>
      </CenterModal>
    </transition>
  </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import TheLoading from "../components/TheLoading";
import CenterModal from "../components/CenterModal";
import BaseButton from "../components/BaseButton";
import CenterModalBtn from "../components/CenterModalBtn";

import { INTERNAL_SERVER_ERROR, OK } from "../util";

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
    TheHeader,
    TheFooter,
    FontAwesomeIcon,
    TheLoading,
    CenterModal,
    CenterModalBtn,
    BaseButton
  },

  data() {
    return {
      normal: {
        problemData: {},
        count: null
      },
      //間違えた問題だけもう一度
      again: {
        problemData: {},
        count: null,
        incorrectAnswerId: []
      },
      userId: null,
      isCorrectAnswerFlg: false,
      orderNumber: 0,
      currentNumber: 1,
      problemDisplay: true,
      answerDisplay: false,
      displaySecond: false,
      loading: {
        isLoading: false,
        opacity: 0.5
      },
      isProblemEndModal: false,
      isLastProblem: false,
      isCurrectAnswerAll: false,
      endModal: {
        restartText: "もう一度",
        endText: "一覧へ"
      },
      endConfimationModal: false,
      editModal: false,
      deleteConfimationModal: false,
      flashMsg: {
        text: "問題を削除しました",
        speed: 2000
      }
    };
  },

  computed: {
    isBackBtnDisplay() {
      // 最初の問題の場合は戻るボタンは非表示
      return this.currentNumber >= 2 ? true : false;
    },
    // 正解数
    currectAnswerCount() {
      return this.currentProblemData.count - this.inCurrectAnswerCount;
    },
    //  不正解数
    inCurrectAnswerCount() {
      return this.again.incorrectAnswerId.length;
    },
    // 現在の問題データ
    currentProblemData() {
      return this.isCorrectAnswerFlg ? this.again : this.normal;
    },
    // 問題集の作成者がログインユーザーかどうか
    isProblemsLoginUser() {
      return Number(this.$store.state.auth.user.id) === Number(this.userId)
        ? true
        : false;
    },
    // 問題の有無
    noProblem() {
      return this.currentProblemData.count === null ? true : false;
    },
    // フラッシュメッセージの有無
    isFlashMsg() {
      return this.$store.state.flashMessage.visible;
    }
  },

  async mounted() {
    this.loading.isLoading = true;
    const response = await axios
      .get(`/api/problems/${this.$route.params.id}`)
      .catch(error => response.error || error);

    if (response.status === INTERNAL_SERVER_ERROR) {
      this.$route.push("/500");
      return;
    }

    if (response.status === OK) {
      console.log(response.data);
      const exerciseBooks = response.data.exercise_books;
      if (exerciseBooks) {
        this.normal.problemData = exerciseBooks.problems;
        this.normal.count = exerciseBooks.count;
        this.userId = exerciseBooks.user_id;
      }
      this.loading.isLoading = false;
    }
  },

  methods: {
    showAnswer() {
      this.problemDisplay = false;
      this.answerDisplay = true;
      this.displaySecond = true;
    },
    nextProblem() {
      // 現在表示されている問題は、初期表示の問題か？間違えた問題か？
      const currentProblemData = this.currentProblemData;
      // 問題が最終問題の場合
      if (currentProblemData.count === this.currentNumber) {
        // 間違えた問題がない場合
        if (!this.again.incorrectAnswerId.length) {
          this.isCurrectAnswerAll = true;
        }
        this.problemEnd();
        return;
      }

      this.displaySecond = false;
      this.answerDisplay = false;
      this.problemDisplay = true;
      this.orderNumber++;
      this.currentNumber++;
    },
    correct() {
      this.nextProblem();
    },
    incorrect(problemId) {
      if (problemId) {
        this.again.incorrectAnswerId.push(problemId);
      }
      this.nextProblem();
    },
    swapProblemAnswer() {
      this.problemDisplay = this.problemDisplay ? false : true;
      this.answerDisplay = this.answerDisplay ? false : true;
    },
    returnBack() {
      // 最後の問題を表示していた場合、ボタン内の文言を変更
      if (this.normal.problemData.count === this.currentNumber) {
        this.isLastProblem = false;
      }

      this.putOffProblem();
    },
    problemEnd() {
      this.isProblemEndModal = true;
    },
    incorrectProblemAgain() {
      // 間違えた問題がなかった場合
      if (this.again.incorrectAnswerId.length === 0) {
        return (this.isCurrectAnswerAll = true);
      }

      // 間違えた問題のID
      const incorrectAnswerId = this.again.incorrectAnswerId;
      // 間違えた問題を取得
      const incorrectProblemData = this.normal.problemData.filter(function(
        problem
      ) {
        return incorrectAnswerId.includes(problem.id);
      });
      // 取得した問題の数
      const problemCount = incorrectProblemData.length;

      // 間違えた問題の一覧を作成
      this.again = {
        problemData: incorrectProblemData,
        count: problemCount
      };

      this.isCorrectAnswerFlg = true;
      this.displaySecond = false;
      this.answerDisplay = false;
      this.problemDisplay = true;
      this.orderNumber = 0;
      this.currentNumber = 1;
      this.isProblemEndModal = false;
      this.again.incorrectAnswerId = [];
    },
    goHome() {
      this.$router.push("/problems");
    },
    inMiddleEnd() {
      this.endConfimationModal = true;
    },
    openEditModal() {
      this.editModal = true;
    },
    closeEditModal() {
      this.editModal = false;
    },
    openDeleteConfimationModal() {
      this.closeEditModal();
      this.deleteConfimationModal = true;
    },
    closeDeleteConfimationModal() {
      this.deleteConfimationModal = false;
    },
    async deleteProblem() {
      const problemId = this.currentProblemData.problemData[this.orderNumber]
        .id;

      const url = `/api/problems/${problemId}`;
      const response = await axios
        .delete(url)
        .catch(error => response.error || error);

      if (response.status === INTERNAL_SERVER_ERROR) {
        this.$route.push("/500");
        return;
      }

      if (response.status === OK) {
        //現在表示されている問題から対象の問題を削除
        const deletedProblems = this.currentProblemData.problemData.filter(
          function(question) {
            return problemId !== question.id;
          }
        );

        this.currentProblemData.problemData = deletedProblems;
        this.currentProblemData.count = deletedProblems.length;

        // 二問目以降であった場合は問題を繰り下げる
        if (this.currentNumber >= 2) {
          this.putOffProblem();
        }

        this.closeDeleteConfimationModal();
        await this.$store.dispatch("flashMessage/showFlashMsg");
        await this.$store.dispatch(
          "flashMessage/hideFlashMsg",
          this.flashMsg.speed
        );
      }
    },
    // 問題を繰り下げる
    putOffProblem() {
      this.orderNumber--;
      this.currentNumber--;
      this.again.incorrectAnswerId.pop();
    },

    goEdit() {
      const problemId = this.currentProblemData.problemData[this.orderNumber]
        .id;
      console.log(problemId);
      this.$router.push(`/problems/${problemId}/edit`);
    }
  }
};
</script>

<style scoped>
.center-modal-enter-active,
.center-modal-leave-active {
  transition: opacity 0.2s;
}

.center-modal-enter,
.center-modal-leave-to {
  opacity: 0;
}

.card-text-enter-active {
  transition: opacity 0.2s;
}

.card-text-enter,
.card-text-leave {
  opacity: 0;
}
</style>
