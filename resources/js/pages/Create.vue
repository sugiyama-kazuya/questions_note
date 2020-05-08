<template>
  <div class="h-100 relative">
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

    <main class="w-full h-100 pt-9rem overflow-y-scroll">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form class="pb-8" id="createForm" @submit.prevent="handleSubmit(newProblemCreate)">
          <!-- 問題の追加 -->
          <div class="bg-gray-200 p-3 mb-3">
            <label class="px-3 block text-gray-700 font-bold mb-2" for="problem">問題</label>
            <ValidationProvider name="問題" rules="required" v-slot="{errors}">
              <transition name="validateError">
                <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
              </transition>
              <textarea
                v-model="form.problem"
                class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="problem"
                rows="6"
                cols="40"
                placeholder="問題文"
              ></textarea>
            </ValidationProvider>

            <ValidationProvider name="問題の画像" rules="required" v-slot="{ errors, validate }">
              <transition name="validateError">
                <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
              </transition>
              <div class="flex justify-between px-3 items-center">
                <p class="m-0">画像を選択</p>
                <div class="text-right">
                  <label>
                    <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                    <input type="file" class="hidden" @change="validate" />
                  </label>
                </div>
              </div>
            </ValidationProvider>
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
                id="解答"
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
                  <input type="file" class="hidden" @change="validateUploads" />
                </label>
              </div>
            </div>
          </div>

          <!-- カテゴリの追加 -->
          <div class="flex flex-col bg-gray-200 mb-3">
            <div class="flex">
              <div class="py-2 px-4">
                <font-awesome-icon
                  icon="clipboard-list"
                  class="text-3xl text-gray-400 text-primary"
                />
              </div>
              <div @click="openSelectModal" class="flex justify-between w-full items-center">
                <div>カテゴリ</div>
                <div class="mr-8 flex items-center">
                  <p class="m-0 mr-3">{{form.category}}</p>
                  <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                </div>
              </div>
            </div>
          </div>

          <!-- 問題集への追加 -->
          <div class="flex flex-col bg-gray-200">
            <div class="flex">
              <div class="py-2 px-4">
                <font-awesome-icon icon="book" class="text-3xl text-gray-400 text-primary" />
              </div>
              <div @click="openExericeOpen" class="flex justify-between w-full items-center">
                <div>問題への追加</div>
                <div class="mr-8 flex items-center">
                  <p class="m-0 mr-3">{{form.exerciseBook}}</p>
                  <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                </div>
              </div>
            </div>
          </div>
        </form>
      </ValidationObserver>
    </main>

    <!-- モーダル部分 -->
    <!-- カテゴリモーダル -->
    <transition name="select">
      <div v-if="isSelectActive" class="absolute inset-x-0 bottom-0 z-10 bg-gray-100 h-30">
        <div class="flex justify-between items-center bg-primary st-select_title">
          <div class="w-1/3"></div>
          <h5 class="text-white text-center mb-0 w-1/3">カテゴリ</h5>
          <div class="pr-3 text-white w-1/3 text-right">
            <font-awesome-icon @click="closeSelctModal" icon="times" class="text-2xl" />
          </div>
        </div>
        <div class="flex flex-col items-center text-center overflow-y-scroll st-select_body">
          <li
            v-for="item in categoryItems"
            :key="item.name"
            @click="categorySelect(item.name)"
            class="list-none w-full h-2.5rem py-2 hover:bg-gray-300"
          >{{item.name }}</li>
        </div>
      </div>
    </transition>

    <!-- 問題集への追加 モーダル-->
    <transition name="exercise">
      <div v-if="isExerciseActive" class="absolute inset-x-0 bottom-0 z-10 bg-gray-100 h-30">
        <div class="flex justify-between items-center bg-primary st-select_title">
          <div class="w-1/3"></div>
          <h5 class="text-white text-center mb-0 w-1/3">追加する問題集</h5>
          <div class="pr-3 text-white w-1/3 text-right">
            <font-awesome-icon @click="closeExericeModal" icon="times" class="text-2xl" />
          </div>
        </div>
        <div class="flex flex-col items-center text-center overflow-y-scroll st-select_body">
          <li
            @click="createNewProblem"
            class="list-none w-full h-2.5rem py-2 hover:bg-gray-300"
          >新規追加</li>
          <li
            v-for="item in exerciseBooks"
            :key="item.name"
            @click="exericeSelect(item.name)"
            class="list-none w-full h-2.5rem py-2 hover:bg-gray-300"
          >{{item.name }}</li>
        </div>
      </div>
    </transition>

    <!-- 問題の新規追加 モーダル-->
    <transition name="new-problem">
      <div v-if="isNewProblem" class="modal-mask relative">
        <div class="bg-white rounded w-2/3 h-40 m-0 m-auto shadow absolute inset-0">
          <h5 class="bg-primary text-center py-2 text-white text-base h-20 m-0 leading-400">新規追加</h5>
          <div class="flex flex-col justify-center items-center p-3 h-80 box-border">
            <div class="w-full pm-2 h-100 flex flex-col justify-center">
              <label class="block text-gray-700 font-bold" for="username">問題集の名前</label>
              <input
                type="text"
                v-model="form.newProblem"
                class="shadow-sm appearance-none border rounded w-full h-2.5rem p-2 focus:outline-none focus:shadow-outline"
              />
              <div class="flex justify-end w-full pm-2 mt-3">
                <button
                  @click="preNewProblem"
                  class="bg-pink-400 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded mr-2 focus:outline-none focus:shadow-outline"
                >戻る</button>
                <button
                  @click="addNewProblem(form.newProblem)"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                >追加</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required } from "vee-validate/dist/rules";

extend("required", {
  ...required,
  message: "{_field_}を入力してください"
});

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
  name: "Create",
  components: {
    Header,
    Footer,
    FontAwesomeIcon,
    ValidationProvider,
    ValidationObserver
  },
  data: () => ({
    form: {
      problem: "",
      answer: "",
      category: "選択してください",
      exerciseBook: "選択してください",
      newProblem: ""
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
    exerciseBooks: [
      { name: "PHPについて" },
      { name: "JAVAについて" },
      { name: "RUBYについて" }
    ],
    isSelectActive: false,
    isExerciseActive: false,
    isNewProblem: false
  }),
  methods: {
    openSelectModal() {
      this.isSelectActive = true;
    },
    closeSelctModal() {
      this.isSelectActive = false;
    },
    openExericeOpen() {
      this.isExerciseActive = true;
    },
    closeExericeModal() {
      this.isExerciseActive = false;
    },
    openCreateProblemModal() {
      this.isNewProblem = true;
    },
    closeCreateProblemModal() {
      this.isNewProblem = false;
    },
    categorySelect(item) {
      this.form.category = item;
      this.closeSelctModal();
    },
    exericeSelect(problem) {
      this.form.exerciseBook = problem;
      this.closeExericeModal();
    },
    createNewProblem() {
      this.openCreateProblemModal();
    },
    addNewProblem(problem) {
      this.closeCreateProblemModal();
      this.closeExericeModal();
      this.form.exerciseBook = problem;
    },
    preNewProblem() {
      this.closeCreateProblemModal();
    },
    newProblemCreate() {
      console.log("create");
    },
    validateUploads() {
      const file = event.target.files[0];
      const name = file.name;
      const size = file.size;
      const type = file.type;
      const errors = "";

      if (size > 3000000) {
        errors += "ファイルの上限サイズ3MBを超えています\n";
      }

      //拡張子は .jpg .gif .png . pdf のみ許可
      if (
        type != "image/jpeg" &&
        type != "image/gif" &&
        type != "image/png" &&
        type != "application/pdf"
      ) {
        errors +=
          ".jpg、.gif、.png、.pdfのいずれかのファイルのみ許可されています\n";
      }
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

.exercise-enter-active {
  transform: translate(0px, 0px);
  transition: all 0.4s ease 0s;
}
.exercise-leave-active {
  transition: all 1s ease 0s;
}
.exercise-enter,
.exercise-leave-to {
  transform: translateY(100vh) translateY(0px);
}

.new-problem-enter-active,
.new-problem-leave-active {
  transition: opacity 0.4s;
}

.new-problem-enter,
.new-problem-leave-to {
  opacity: 0;
}

.validateError-enter-active,
.validateError-leave-active {
  transition: opacity 0.5s;
}

.validateError-enter,
.validateError-leave-to {
  opacity: 0;
}
</style>
