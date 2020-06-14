<template>
  <div class="h-100 relative">
    <div class="h-90">
      <TheHeader class="h-8">
        <template v-slot:titleName>
          <h5 class="m-0">Edit</h5>
        </template>
      </TheHeader>

      <div class="w-full box-border px-3 h-16 h-10 flex items-center">
        <button
          type="submit"
          form="createForm"
          class="bg-primary text-white font-bold py-2 px-4 rounded w-full h-60 focus:outline-none"
        >作成</button>
      </div>

      <main class="w-full overflow-y-scroll h-82">
        <div class="h-90">
          <form id="createForm" class="pb-8" @submit.prevent="newProblemCreate">
            <div class="bg-gray-200 p-3 mb-3">
              <FormLabel>
                <template>問題</template>
              </FormLabel>
              <transition name="validateError">
                <div v-if="validationErrorMsg.problem">
                  <FormValidationErrorMessage :errorMessages="validationErrorMsg.problem" />
                </div>
              </transition>
              <FormTextarea v-model="form.problem" :placeholder="'問題文'"></FormTextarea>

              <div class="flex justify-between px-3 items-center">
                <p class="m-0">画像を選択</p>
                <div class="text-right">
                  <label>
                    <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                    <input type="file" class="hidden" />
                  </label>
                </div>
              </div>
            </div>

            <!-- 解答の追加 -->
            <div class="bg-gray-200 p-3 mb-3">
              <FormLabel>
                <template>解答</template>
              </FormLabel>
              <transition name="validateError">
                <div v-if="validationErrorMsg.answer">
                  <FormValidationErrorMessage :errorMessages="validationErrorMsg.answer" />
                </div>
              </transition>
              <FormTextarea v-model="form.answer" :placeholder="'解答文'"></FormTextarea>
              <div class="flex justify-between px-3 items-center">
                <p class="m-0">画像を選択</p>
                <div class="text-right">
                  <label>
                    <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                    <input type="file" class="hidden" />
                  </label>
                </div>
              </div>
            </div>

            <!-- カテゴリの追加 -->
            <transition name="validateError">
              <div v-if="validationErrorMsg.category" class="px-3">
                <FormValidationErrorMessage :errorMessages="validationErrorMsg.category" />
              </div>
            </transition>
            <div class="flex flex-col bg-gray-200 mb-3">
              <div class="flex">
                <div class="py-2 px-4">
                  <font-awesome-icon
                    icon="clipboard-list"
                    class="text-3xl text-gray-400 text-primary"
                  />
                </div>
                <div @click="openSelectedCategory" class="flex justify-between w-full items-center">
                  <FormLabel>
                    <template>カテゴリ</template>
                  </FormLabel>
                  <div class="mr-8 flex items-center">
                    <p class="m-0 mr-3">{{ selectedCategory.recordText }}</p>
                    <input type="text" class="hidden" v-model="form.category" />
                    <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                  </div>
                </div>
              </div>
            </div>

            <!-- 問題集への追加 -->
            <transition name="validateError">
              <div v-if="validationErrorMsg.exerciseBook" class="px-3">
                <FormValidationErrorMessage :errorMessages="validationErrorMsg.exerciseBook" />
              </div>
            </transition>

            <div class="flex flex-col bg-gray-200 mb-6">
              <div class="flex">
                <div class="py-2 px-4">
                  <font-awesome-icon icon="book" class="text-3xl text-gray-400 text-primary" />
                </div>
                <div
                  @click="openSelectedExerciseBook"
                  class="flex justify-between w-full items-center"
                >
                  <FormLabel>
                    <template>問題集への追加</template>
                  </FormLabel>
                  <div class="mr-8 flex items-center">
                    <p class="m-0 mr-3">{{ selectedExerciseBook.recordText }}</p>
                    <input v-model="form.exerciseBook" type="text" class="hidden" />
                    <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </main>
      <!-- フラッシュメッセージ -->
      <transition name="center-modal">
        <CenterModal v-if="isFlashMsg">
          <div class="p-2 bg-gray-800 text-white rounded-md">{{flashMsg.text}}</div>
        </CenterModal>
      </transition>
    </div>

    <!-- セレクトモーダル-->
    <!-- カテゴリ-->
    <transition name="select">
      <SelectModal
        v-if="selectedCategory.isModal"
        :items="categoryItems"
        @selected="categoryRecordChanged"
        @close="closeSelectedCategory"
      >
        <template v-slot:title>カテゴリ</template>
      </SelectModal>
    </transition>

    <!-- 問題集へ追加-->
    <transition name="select">
      <SelectModal
        v-if="selectedExerciseBook.isModal"
        :items="exerciseBooks"
        @selected="exerciseBookRecordChanged"
        @close="closeSelectedExerciseBook"
      >
        <template v-slot:title>問題集の追加</template>
        <template v-slot:add-list>
          <li
            @click="openExerciseBookNewAdd"
            class="list-none w-full h-2.5rem leading-10 mt-2 hover:bg-gray-300"
          >新規追加</li>
        </template>
      </SelectModal>
    </transition>

    <!-- 問題集の新規作成モーダル-->
    <transition name="new-problem">
      <CenterModal v-if="exerciseBookNewAdd.isModal">
        <template>
          <h1 class="bg-primary text-center py-3 text-white text-base m-0 font-bold">新規追加</h1>
          <form>
            <div class="flex flex-col justify-center items-center p-3 box-border h-100">
              <div class="w-full pm-2 h-100 flex flex-col justify-center">
                <div class="h-2.5rem">
                  <transition name="validateError">
                    <div v-if="exerciseBookNewAdd.errorMsg">
                      <p
                        v-for="errorMsg in exerciseBookNewAdd.errorMsg.name"
                        :key="errorMsg"
                        class="text-red-500 text-xs italic m-0 p-1"
                      >{{ errorMsg }}</p>
                    </div>
                  </transition>
                </div>
                <FormLabel>
                  <template>問題集の名前</template>
                </FormLabel>
                <input
                  v-model="exerciseBookNameForm.name"
                  type="text"
                  class="shadow-sm appearance-none border rounded w-full h-2.5rem p-2 focus:outline-none focus:shadow-outline"
                />
                <div class="flex justify-end w-full pm-2 mt-3">
                  <BaseButton
                    :color="'bg-pink-400'"
                    :text="'text-white'"
                    @click.native="exerciseBookNewAddCancel"
                  >
                    <template>キャンセル</template>
                  </BaseButton>
                  <BaseButton
                    :color="'bg-blue-500'"
                    :text="'text-white'"
                    @click.native="createExerciseBook"
                  >
                    <template>追加</template>
                  </BaseButton>
                </div>
              </div>
            </div>
          </form>
        </template>
      </CenterModal>
    </transition>
    <TheFooter />
    <TheLoading v-if="isLoading" :loading="isLoading" />
  </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import TheLoading from "../components/TheLoading";
import SelectModal from "../components/SelectModal";
import CenterModal from "../components/CenterModal";
import FormLabel from "../components/form/FormLabel";
import FormTextarea from "../components/form/FormTextarea";
import FormValidationErrorMessage from "../components/form/FormValidationErrorMessage";
import BaseButton from "../components/BaseButton";

import { UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR, OK } from "../util";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faAngleRight,
  faClipboardList,
  faTimes,
  faBook
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faAngleRight, faClipboardList, faTimes, faBook);

export default {
  name: "ProbelmCreate",
  components: {
    TheHeader,
    TheFooter,
    TheLoading,
    FontAwesomeIcon,
    SelectModal,
    CenterModal,
    FormLabel,
    FormTextarea,
    FormValidationErrorMessage,
    BaseButton
  },

  data: () => ({
    exerciseBooks: [],
    form: {
      problem: "",
      answer: "",
      category: "",
      exerciseBook: ""
    },
    validationErrorMsg: "",
    selectedCategory: {
      isModal: false,
      recordText: "選択してください"
    },
    categoryItems: [
      { name: "ゲーム" },
      { name: "スポーツ" },
      { name: "数学" },
      { name: "恋愛" },
      { name: "漫画" },
      { name: "心理学" },
      { name: "経済" },
      { name: "プログラミング" }
    ],
    exerciseBookNewAdd: {
      isModal: false,
      errorMsg: null
    },
    exerciseBookNameForm: {
      name: ""
    },
    selectedExerciseBook: {
      isModal: false,
      recordText: "選択してください"
    },
    flashMsg: {
      text: "問題を作成しました。",
      speed: 2000
    },
    isLoading: false
  }),

  computed: {
    isFlashMsg() {
      return this.$store.state.flashMessage.visible;
    }
  },

  async created() {
    this.isLoading = true;
    const response = await axios
      .get("/api/problems/create")
      .catch(error => error.response || error);

    if (response.status === INTERNAL_SERVER_ERROR) {
      this.$route.push("/500");
      return;
    }

    if (response.status === OK) {
      this.exerciseBooks = response.data;
      this.isLoading = false;
      return;
    }
  },

  methods: {
    openSelectedCategory() {
      this.selectedCategory.isModal = true;
    },

    closeSelectedCategory(status) {
      this.selectedCategory.isModal = status;
    },

    // カテゴリーレコードの文字を変更
    categoryRecordChanged(category) {
      this.form.category = category;
      this.selectedCategory.recordText = category;
    },

    openSelectedExerciseBook() {
      this.selectedExerciseBook.isModal = true;
    },

    closeSelectedExerciseBook(status) {
      this.selectedExerciseBook.isModal = status;
    },

    openExerciseBookNewAdd() {
      this.exerciseBookNewAdd.isModal = true;
      this.selectedExerciseBook.isModal = false;
    },

    exerciseBookNewAddCancel() {
      this.exerciseBookNewAdd.isModal = false;
      this.exerciseBookNewAdd.errorMsg = null;
      this.exerciseBookNameForm.name = null;
    },

    //　問題集への追加レコードの文字を変更
    exerciseBookRecordChanged(name) {
      this.form.exerciseBook = name;
      this.selectedExerciseBook.recordText = this.LengthaAdjustment(name);
    },

    // 問題集の新規作成
    async createExerciseBook() {
      const response = await axios
        .post("/api/problems/newExerciseName", this.exerciseBookNameForm)
        .catch(error => error.response || error);

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.exerciseBookNewAdd.errorMsg = response.data.errors;
        return;
      }

      if (response.status === OK) {
        const exerciseBookName = response.data.name;
        this.form.exerciseBook = exerciseBookName;
        this.selectedExerciseBook.recordText = this.LengthaAdjustment(
          exerciseBookName
        );
        this.exerciseBookNewAdd.isModal = false;
        this.exerciseBookNameForm.name = "";
        return;
      }
    },

    //文字の長さの調整
    LengthaAdjustment(name) {
      let nameLength = name.length;
      if (nameLength >= 12) {
        const exerciseBookName = name.substring(0, 12) + "...";
        return exerciseBookName;
      }
      return name;
    },

    async newProblemCreate() {
      const response = await axios
        .post("/api/problems", this.form)
        .catch(error => error.response || error);

      if (response.status === INTERNAL_SERVER_ERROR) {
        this.$route.push("/500");
        return;
      }

      if (response.status === UNPROCESSABLE_ENTITY) {
        this.validationErrorMsg = response.data.errors;
        console.log(this.validationErrorMsg);
        return;
      }

      if (response.status === OK) {
        await this.$store.commit("flashMessage/setVisible", true);
        if (this.isFlashMsg) {
          this.$store.dispatch(
            "flashMessage/hideFlashMsg",
            this.flashMsg.speed
          );
          this.clearForm();
        }
      }
    },
    clearForm() {
      this.form = {
        problem: "",
        answer: "",
        category: "",
        exerciseBook: ""
      };
      this.selectedCategory.recordText = "選択してください";
      this.selectedExerciseBook.recordText = "選択してください";
    }
  }
};
</script>

<style scoped>
.st-absolute {
  position: absolute;
  top: 18%;
}

.st-new-problem {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto;
}
.select-enter-active {
  transform: translate(0px, 0px);
  transition: all 0.4s ease 0s;
}
.select-leave-active {
  transition: all 1s ease 0s;
}
.select-enter,
.select-leave-to {
  transform: translateY(100vh) translateY(0px);
}

.validateError-enter-active,
.validateError-leave-active {
  transition: opacity 0.5s;
}

.validateError-enter,
.validateError-leave-to {
  opacity: 0;
}

.new-problem-enter-active,
.new-problem-leave-active {
  transition: opacity 0.5s;
}

.new-problem-enter,
.new-problem-leave-to {
  opacity: 0;
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

