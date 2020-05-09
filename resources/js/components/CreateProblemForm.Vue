<template>
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
  <!-- <div class="bg-gray-200 p-3 mb-3">
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
  </div>-->

  <!-- カテゴリの追加 -->
  <!-- <div class="flex flex-col bg-gray-200 mb-3">
    <div class="flex">
      <div class="py-2 px-4">
        <font-awesome-icon icon="clipboard-list" class="text-3xl text-gray-400 text-primary" />
      </div>
      <div @click="openCategoryModal" class="flex justify-between w-full items-center">
        <div>カテゴリ</div>
        <div class="mr-8 flex items-center">
          <p class="m-0 mr-3">{{form.category}}</p>
          <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
        </div>
      </div>
    </div>
  </div>
  -->
  <!-- 問題集への追加 -->
  <!-- <div class="flex flex-col bg-gray-200">
    <div class="flex">
      <div class="py-2 px-4">
        <font-awesome-icon icon="book" class="text-3xl text-gray-400 text-primary" />
      </div>
      <div @click="openExericeModal" class="flex justify-between w-full items-center">
        <div>問題への追加</div>
        <div class="mr-8 flex items-center">
          <p class="m-0 mr-3">{{form.exerciseBook}}</p>
          <font-awesome-icon icon="angle-right" class="text-3xl text-gray-400" />
        </div>
      </div>
    </div>
  </div>-->
</template>

<script>
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required } from "vee-validate/dist/rules";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faAngleRight,
  faClipboardList,
  faTimes,
  faBook
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faAngleRight, faClipboardList, faTimes, faBook);

extend("required", {
  ...required,
  message: "{_field_}を入力してください"
});

export default {
  name: "createProblemForm",
  components: {
    ValidationProvider,
    ValidationObserver,
    FontAwesomeIcon
  },
  data: () => ({
    form: {
      probelm: "",
      answer: "",
      category: "",
      exerciseBook: ""
    }
  }),
  methods: {
    newProblemCreate() {
      alert("create");
    },
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
    }
  }
};
</script>

<style lang="scss">
</style>
