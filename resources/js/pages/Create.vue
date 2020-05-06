<template>
  <div class="h-100 relative">
    <Header>
      <template v-slot:titleName>
        <h5 class="m-0">Create</h5>
      </template>
      <template v-slot:createBtn>
        <div class="w-full box-border px-2">
          <b-button size="lg" block variant="primary">作成</b-button>
        </div>
      </template>
    </Header>

    <main class="w-full h-100 pt-9rem overflow-y-scroll">
      <form class="pb-8 ">
        <div class="bg-gray-200 p-3 mb-3">
          <label class="px-3 block text-gray-700 text-sm font-bold mb-2" for="problem">問題</label>
          <textarea
            class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="problem"
            rows="6"
            cols="40"
          ></textarea>
          <div class="flex justify-between px-3 pt-2">
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
          <label class="block text-gray-700 text-sm font-bold mb-2" for="answer">答え</label>
          <textarea
            class="shadow-sm appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            id="answer"
            rows="6"
            cols="40"
          ></textarea>
          <div class="flex justify-between">
            <p>画像を選択</p>
            <div class="text-right">
              <label>
                <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
                <input type="file" class="hidden" />
              </label>
            </div>
          </div>
        </div>
        <div class="bg-gray-200 p-3 mb-3">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="answer">答え</label>
          <textarea
            class="shadow-sm appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            id="answer"
            rows="6"
            cols="40"
          ></textarea>
          <div class="flex justify-between">
            <p>画像を選択</p>
            <div class="text-right">
              <label>
                <font-awesome-icon icon="angle-right" class="text-2xl text-gray-400" />
                <input type="file" class="hidden" />
              </label>
            </div>
          </div>
        </div>
      </form>
    </main>

    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";

import { library } from "@fortawesome/fontawesome-svg-core";
import { faAngleRight } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faAngleRight);

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
    }
  })
};
</script>

<style>
.st-absolute {
  position: absolute;
  top: 18%;
}
</style>
