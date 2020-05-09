<template>
  <div class="modal-mask relative">
    <div class="bg-white rounded w-2/3 h-40 m-0 m-auto shadow absolute inset-0">
      <h5 class="bg-primary text-center py-2 text-white text-base h-20 m-0 leading-400">新規追加</h5>
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(addNewProblem)" class="h-80">
          <div class="flex flex-col justify-center items-center p-3 box-border h-100">
            <div class="w-full pm-2 h-100 flex flex-col justify-center">
              <ValidationProvider name="問題集の名前" rules="required" v-slot="{errors}">
                <transition name="validateError">
                  <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
                </transition>
                <label class="block text-gray-700 font-bold" for="username">問題集の名前</label>
                <input
                  type="text"
                  v-model="data"
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
    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required } from "vee-validate/dist/rules";

extend("required", {
  ...required,
  message: "{_field_}は入力必須です"
});

export default {
  name: "NewProblemModal",
  components: {
    ValidationProvider,
    ValidationObserver
  },
  props: {
    value: {
      type: String
    }
  },
  data: () => ({
    data: ""
  }),

  methods: {
    cancel() {
      this.$store.commit("createForm/setNewProblemModal", false);
    },
    addNewProblem() {
      this.$emit("selected", this.data);
      this.$store.commit("createForm/setNewProblemModal", false);
      this.$store.commit("createForm/setCloseModal", false);
    }
  }
};
</script>

<style>
.validateError-enter-active,
.validateError-leave-active {
  transition: opacity 0.5s;
}

.validateError-enter,
.validateError-leave-to {
  opacity: 0;
}
</style>
