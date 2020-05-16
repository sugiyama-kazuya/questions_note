<template>
  <div class="h-100 relative">
    <div class="h-90">
      <Header class="h-8">
        <template v-slot:titleName>
          <h5 class="m-0">Create</h5>
        </template>
      </Header>

      <div
        class="w-full box-border px-3 h-16 focus:outline-none focus:shadow-outline h-10 flex items-center"
      >
        <button
          class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full h-60"
          type="submit"
          form="createForm"
        >作成</button>
      </div>

      <main class="w-full overflow-y-scroll h-82">
        <div class="h-90">
          <ValidationObserver v-slot="{ handleSubmit }">
            <form class="pb-8" id="createForm" @submit.prevent="handleSubmit(newProblemCreate)">
              <div class="bg-gray-200 p-3 mb-3">
                <label class="px-3 block text-gray-700 font-bold mb-2" for="problem">問題</label>
                <ValidationProvider name="問題" rules="required" v-slot="{errors}">
                  <transition name="validateError">
                    <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
                  </transition>
                  <textarea
                    v-model="form.problem"
                    class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    rows="6"
                    cols="40"
                    placeholder="問題文"
                  ></textarea>
                </ValidationProvider>

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
                <label class="px-3 block text-gray-700 font-bold mb-2" for="answer">解答</label>
                <ValidationProvider name="解答" rules="required" v-slot="{errors}">
                  <transition name="validateError">
                    <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
                  </transition>
                  <textarea
                    v-model="form.answer"
                    class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    rows="6"
                    cols="40"
                    placeholder="解答文"
                  ></textarea>
                </ValidationProvider>
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
              <ValidationProvider name="カテゴリ" rules="required" v-slot="{errors}">
                <transition name="validateError">
                  <p v-if="errors[0]" class="text-red-500 text-xs italic pl-3">{{errors[0]}}</p>
                </transition>
                <div class="flex flex-col bg-gray-200 mb-3">
                  <div class="flex">
                    <div class="py-2 px-4">
                      <font-awesome-icon
                        icon="clipboard-list"
                        class="text-3xl text-gray-400 text-primary"
                      />
                    </div>
                    <div
                      @click="openCategoryModal"
                      class="flex justify-between w-full items-center"
                    >
                      <div>カテゴリ</div>
                      <div class="mr-8 flex items-center">
                        <p class="m-0 mr-3">{{selectedCategory}}</p>
                        <input type="text" class="hidden" v-model="form.category" />
                        <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                      </div>
                    </div>
                  </div>
                </div>
              </ValidationProvider>

              <!-- 問題集への追加 -->
              <ValidationProvider name="問題への追加" rules="required" v-slot="{errors}">
                <transition name="validateError">
                  <p v-if="errors[0]" class="text-red-500 text-xs italic pl-3">{{errors[0]}}</p>
                </transition>

                <div class="flex flex-col bg-gray-200 mb-6">
                  <div class="flex">
                    <div class="py-2 px-4">
                      <font-awesome-icon icon="book" class="text-3xl text-gray-400 text-primary" />
                    </div>
                    <div @click="openExericeModal" class="flex justify-between w-full items-center">
                      <div>問題への追加</div>
                      <div class="mr-8 flex items-center">
                        <p class="m-0 mr-3">{{selectedExerciseBook}}</p>
                        <input type="text" class="hidden" v-model="form.exerciseBook" />
                        <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                      </div>
                    </div>
                  </div>
                </div>
              </ValidationProvider>
            </form>
          </ValidationObserver>
        </div>
      </main>
    </div>

    <!-- セレクトモーダル-->
    <!-- カテゴリ-->
    <transition name="select">
      <SelectModal v-if="categoryModal" :items="categoryItems" @selected="categoryPush">
        <template v-slot:title>カテゴリ</template>
      </SelectModal>
    </transition>

    <!-- 問題集へ追加-->
    <transition name="select">
      <SelectModal v-if="problemModal" :items="exerciseBooks" @selected="exerciseBookPush">
        <template v-slot:title>問題集の追加</template>
        <template v-slot:add-list>
          <li
            @click="openAddProblemModal"
            class="list-none w-full h-2.5rem leading-10 mt-2 hover:bg-gray-300"
          >新規追加</li>
        </template>
      </SelectModal>
    </transition>

    <!-- 問題集の新規作成モーダル-->
    <transition name="new-problem">
      <CenterModal v-if="addNewProblemModal">
        <template>
          <h1 class="bg-primary text-center py-3 text-white text-base m-0 font-bold">新規追加</h1>
          <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(addNewProblem)">
              <div class="flex flex-col justify-center items-center p-3 box-border h-100">
                <div class="w-full pm-2 h-100 flex flex-col justify-center">
                  <ValidationProvider name="問題集の名前" rules="required" v-slot="{errors}">
                    <div class="h-4rem">
                      <transition name="validateError">
                        <p
                          v-if="errors[0]"
                          class="text-red-500 text-xs italic m-0 p-1"
                        >{{errors[0]}}</p>
                      </transition>
                      <transition name="validateError">
                        <div v-if="validateErrMsg">
                          <p
                            v-for="msg in validateErrMsg.name"
                            :key="msg"
                            class="text-red-500 text-xs italic m-0 p-1"
                          >{{msg}}</p>
                        </div>
                      </transition>
                    </div>
                    <label class="block text-gray-700" for="username">問題集の名前</label>
                    <input
                      type="text"
                      v-model="exerciseBooksNameForm.name"
                      class="shadow-sm appearance-none border rounded w-full h-2.5rem p-2 focus:outline-none focus:shadow-outline"
                    />
                  </ValidationProvider>
                  <div class="flex justify-end w-full pm-2 mt-3">
                    <button
                      @click="cancel"
                      class="bg-pink-400 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded mr-2 focus:outline-none focus:shadow-outline"
                    >キャンセル</button>
                    <button
                      type="submit"
                      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    >追加</button>
                  </div>
                </div>
              </div>
            </form>
          </ValidationObserver>
        </template>
      </CenterModal>
    </transition>

    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import CreateProblemForm from "../components/CreateProblemForm";
import SelectModal from "../components/SelectModal";
import CenterModal from "../components/CenterModal";

import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faAngleRight,
  faClipboardList,
  faTimes,
  faBook
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faAngleRight, faClipboardList, faTimes, faBook);
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required } from "vee-validate/dist/rules";

export default {
  name: "Create",
  components: {
    Header,
    Footer,
    FontAwesomeIcon,
    CreateProblemForm,
    SelectModal,
    CenterModal,
    ValidationProvider,
    ValidationObserver
  },
  data: () => ({
    form: {
      problem: "",
      answer: "",
      category: "",
      exerciseBook: ""
    },
    selectedCategory: "選択してください",
    selectedExerciseBook: "選択してください",
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
    exerciseBooks: [],
    isSelectActive: false,
    isExerciseActive: false,
    exerciseBooksNameForm: {
      name: ""
    }
  }),
  computed: {
    categoryModal() {
      return this.$store.state.createForm.isCategory;
    },
    problemModal() {
      return this.$store.state.createForm.isExercise;
    },
    addNewProblemModal() {
      return this.$store.state.createForm.isNewProblem;
    },
    validateErrMsg() {
      return this.$store.state.createForm.newExerciseNameErrMsg;
    }
  },
  methods: {
    openCategoryModal() {
      this.$store.commit("createForm/setCategoryModal", true);
    },
    closeCategoryModal() {
      this.$store.commit("createForm/setCategoryModal", false);
    },
    openExericeModal() {
      this.$store.commit("createForm/setProblemModal", true);
    },
    closeExericeModal() {
      this.$store.commit("createForm/setProblemModal", false);
    },
    openAddProblemModal() {
      this.$store.commit("createForm/setNewProblemModal", true);
    },
    categoryPush(category) {
      this.form.category = category;
      this.selectedCategory = category;
    },
    LengthLimit(name) {
      let nameLength = name.length;
      if (nameLength >= 12) {
        const exerciseBookName = name.substring(0, 12) + "...";
        return exerciseBookName;
      }
      return name;
    },
    async exerciseBookPush(name) {
      this.form.exerciseBook = name;
      const afterLengthLimitName = await this.LengthLimit(name);
      this.selectedExerciseBook = afterLengthLimitName;
    },
    async newProblemCreate() {
      await this.$store.dispatch("createForm/createProblem", this.form);
    },
    cancel() {
      this.$store.commit("createForm/setNewProblemModal", false);
      this.$store.commit("createForm/setCreateExerciseBooksNameErr", null);
      this.$store.commit("createForm/setCreateProblemErrorMsg", null);
      this.exerciseBooksNameForm.name = null;
    },
    async addNewProblem() {
      this.$store.commit("createForm/setCreateExerciseBooksNameErr", null);
      await this.$store.dispatch(
        "createForm/createExerciseBooksName",
        this.exerciseBooksNameForm
      );

      if (this.$store.state.createForm.newExerciseNameErrMsg === null) {
        const exerciseBooksName = await this.$store.state.createForm
          .newExerciseName.name;

        this.form.exerciseBook = exerciseBooksName;
        this.selectedExerciseBook = this.LengthLimit(exerciseBooksName);
        this.$store.commit("createForm/setNewProblemModal", false);
        this.$store.commit("createForm/setCloseModal", false);

        return;
      }
    }
  },
  async created() {
    await this.$store.dispatch("createForm/displayCreatingScreen");

    this.exerciseBooks = this.$store.state.createForm.displayData;
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
</style>
