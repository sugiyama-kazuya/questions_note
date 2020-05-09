<template>
  <div class="h-100 relative flex flex-col">
    <Header>
      <template v-slot:titleName>
        <h5 class="m-0">Create</h5>
      </template>
      <template v-slot:createBtn>
        <div class="w-full box-border px-3 h-16 focus:outline-none focus:shadow-outline">
          <button
            class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full"
            type="submit"
            form="createForm"
          >作成</button>
        </div>
      </template>
    </Header>

    <main class="w-full h-75 overflow-y-scroll">
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
                <div @click="openCategoryModal" class="flex justify-between w-full items-center">
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
    </main>

    <!-- セレクトモーダル-->
    <!-- カテゴリ-->
    <transition name="select">
      <SelectModal v-if="categoryModal" :items="categoryItems" @selected="categoryPush">
        <template v-slot:title>カテゴリ</template>
      </SelectModal>
    </transition>

    <!-- 問題集への追加-->
    <transition name="select">
      <SelectModal v-if="problemModal" :items="exerciseBooks" @selected="exerciseBookPush">
        <template v-slot:title>問題集の追加</template>
        <template v-slot:add-list>
          <li
            @click="openAddProblemModal"
            class="list-none w-full h-2.5rem py-2 hover:bg-gray-300"
          >新規追加</li>
        </template>
      </SelectModal>
    </transition>

    <!-- 問題集の新規作成モーダル-->
    <transition name="new-problem">
      <NewProblemModal v-if="addNewProblemModal" @selected="exerciseBookPush" />
    </transition>

    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import CreateProblemForm from "../components/CreateProblemForm";
import SelectModal from "../components/SelectModal";
import NewProblemModal from "../components/NewProblemModal";

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
    NewProblemModal,
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
    exerciseBooks: [
      { name: "PHPについて" },
      { name: "JAVAについて" },
      { name: "RUBYについて" }
    ],
    isSelectActive: false,
    isExerciseActive: false
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
    exerciseBookPush(exerciseBook) {
      this.form.exerciseBook = exerciseBook;
      this.selectedExerciseBook = exerciseBook;
    },
    async newProblemCreate() {
      await this.$store.dispatch("createForm/createProblem", this.form);
    }
  }
};
</script>

<style>
.modal-mask {
  position: fixed;
  z-index: 9999;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}
.st-absolute {
  position: absolute;
  top: 18%;
}
.st-select_title {
  height: 15%;
}

.st-select_body {
  height: 85%;
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
