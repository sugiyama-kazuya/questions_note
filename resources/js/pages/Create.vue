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
          >作成</button>
        </div>
      </template>
    </Header>

    <main class="w-full h-100 pt-9rem overflow-y-scroll">
      <form class="pb-8">
        <div class="bg-gray-200 p-3 mb-3">
          <label class="px-3 block text-gray-700 font-bold mb-2" for="problem">問題</label>
          <textarea
            class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="problem"
            rows="6"
            cols="40"
            placeholder="問題文"
          ></textarea>
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
        <div class="bg-gray-200 p-3 mb-3">
          <label class="px-3 block text-gray-700 font-bold mb-2" for="answer">解答</label>
          <textarea
            class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="answer"
            rows="6"
            cols="40"
            placeholder="解答文"
          ></textarea>
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
      </form>
      <div class="flex flex-col bg-gray-200">
        <div class="flex">
          <div class="py-2 px-4">
            <font-awesome-icon icon="clipboard-list" class="text-3xl text-gray-400 text-primary" />
          </div>
          <div @click="modalOpen" class="flex justify-between w-full items-center">
            <div>カテゴリ</div>
            <div class="mr-8 flex items-center">
              <p class="m-0 mr-3">{{categoryName}}</p>
              <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
            </div>
          </div>
        </div>
      </div>
    </main>

    <transition name="select">
      <div v-if="isActive" class="absolute inset-x-0 bottom-0 z-10 bg-gray-100 h-30">
        <div class="flex justify-between items-center bg-primary st-select_title">
          <div class="w-1/3"></div>
          <h5 class="text-white text-center mb-0 w-1/3">カテゴリ</h5>
          <div class="pr-3 text-white w-1/3 text-right">
            <font-awesome-icon @click="closeModal" icon="times" class="text-2xl" />
          </div>
        </div>
        <div class="flex flex-col items-center text-center overflow-y-scroll st-select_body">
          <li
            v-for="item in category"
            :key="item.name"
            @click="categorySelect(item.name)"
            class="list-none w-full h-2.5rem py-2 hover:bg-gray-300"
          >{{item.name }}</li>
        </div>
      </div>
    </transition>
    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";

import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faAngleRight,
  faClipboardList,
  faTimes
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faAngleRight, faClipboardList, faTimes);

export default {
  name: "Create",
  components: {
    Header,
    Footer,
    FontAwesomeIcon
  },
  data: () => ({
    form: {
      problem: "",
      answer: ""
    },
    category: [
      { name: "ゲーム" },
      { name: "スポーツ" },
      { name: "数学" },
      { name: "恋愛" },
      { name: "漫画" },
      { name: "心理学" },
      { name: "経済" },
      { name: "プログラミング" }
    ],
    categoryName: "選択してください",
    isActive: false,
    selected: ""
  }),
  methods: {
    modalOpen() {
      this.isActive = true;
    },
    closeModal() {
      this.isActive = false;
    },
    categorySelect(item) {
      this.categoryName = item;
      this.closeModal();
    }
  }
};
</script>

<style>
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

.select-enter-active {
  transform: translate(0px, 0px);
  transition: all 0.4s ease 0s;
}
.select-leave-active {
  transition: all 1s ease 0s;
}
.select-enter,
.select-leave-to {
  /* 非表示の際のCSSはこのブロックに記述 */
  transform: translateY(100vh) translateY(0px);
}
</style>
