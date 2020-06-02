<template>
  <div class="relative h-100">
    <div class="h-90">
      <Header class="h-8 bg-gray-200">
        <template v-slot:titleName>
          <h5 class="text-2xl m-0">編集</h5>
        </template>
        <template v-slot:leftSide>
          <font-awesome-icon
            @click="endConfimationModal = true"
            icon="times"
            class="text-3xl text-white"
          ></font-awesome-icon>
        </template>
        <template v-slot:rightSide>
          <button class="text-white focus:outline-none" type="submit" form="profile-edit-form">変更</button>
        </template>
      </Header>

      <main class="h-92 overflow-y-scroll bg-gray-100">
        <div class="flex justify-center items-center h-100">
          <div class="w-full h-100">
            <div v-if="uploadImg" class="flex justify-center mt-4">
              <img class="profile-img h-12rem w-12rem bg-cover" :src="uploadImg" alt />
            </div>
            <ValidationObserver v-slot="{ handleSubmit }">
              <form
                id="profile-edit-form"
                @submit.prevent="handleSubmit(saveProfile)"
                class="px-8 pt-6 pb-8 mb-4"
              >
                <transition name="validateError">
                  <div v-if="saveProfileErrorMsg" class="mb-6">
                    <p
                      v-for="msg in saveProfileErrorMsg.name"
                      :key="msg"
                      class="text-red-500 text-xs italic"
                    >{{msg}}</p>
                  </div>
                </transition>

                <div class="mb-4">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                  <ValidationProvider name="name" rules="required" v-slot="{errors}">
                    <input
                      v-model="profile.name"
                      class="shadow-sm appearance-none border rounded w-2/3 py-2 px-3 mb-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      id="name"
                      type="text"
                      placeholder="Username"
                    />
                    <transition name="validateError">
                      <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
                    </transition>
                  </ValidationProvider>
                </div>
                <div class="mb-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="email">email</label>
                  <ValidationProvider name="email" rules="required" v-slot="{ errors }">
                    <input
                      v-model="profile.email"
                      class="shadow-sm appearance-none border rounded w-2/3 py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                      id="email"
                      type="email"
                      placeholder="example@com"
                    />
                    <transition name="validateError">
                      <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
                    </transition>
                  </ValidationProvider>
                </div>
                <div class="mb-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="email">プロフィール画像</label>
                  <ValidationProvider name="image" rules="required" v-slot="{ errors }">
                    <label class="p-2 border rounded-lg text-gray-500 text-sm shadow-sm">
                      画像を選択
                      <input
                        ref="profileImg"
                        id="profileImg"
                        @change="onFileChange($event)"
                        type="file"
                        class="hidden"
                      />
                    </label>
                    <transition name="validateError">
                      <p v-if="errors[0]" class="text-red-500 text-xs italic">{{errors[0]}}</p>
                    </transition>
                  </ValidationProvider>
                </div>
                <div class="flex items-center justify-between"></div>
              </form>
            </ValidationObserver>
          </div>
        </div>
      </main>
    </div>
    <Footer />
    <transition name="center-modal">
      <CenterModal v-if="endConfimationModal" :backColor="false">
        <div class="py-5 px-3">
          <div class="p-4 text-center text-lg mb-2">
            <span>変更した内容は保存されませんが終了でよろしいでしょうか？</span>
          </div>
          <div class="flex flex-col justify-center p-2">
            <CenterModalBtn
              @click.native="goOwnProfile"
              :text="'プロフィールへ'"
              :color="'bg-blue-400'"
              class="mb-3"
            />
            <CenterModalBtn @click.native="endConfimationModal = false" :text="'編集に戻る'" />
          </div>
        </div>
      </CenterModal>
    </transition>
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import CenterModal from "../components/CenterModal";
import CenterModalBtn from "../components/CenterModalBtn";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faTimes } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faTimes);

import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required } from "vee-validate/dist/rules";

extend("required", {
  ...required,
  message: "{_field_}を入力してください"
});

export default {
  name: "profileEdit",
  components: {
    Header,
    Footer,
    FontAwesomeIcon,
    CenterModal,
    CenterModalBtn,
    ValidationProvider,
    ValidationObserver
  },
  data() {
    return {
      endConfimationModal: false,
      saveProfileErrorMsg: "",
      profile: {
        name: "",
        email: "",
        file: ""
      },
      uploadImg: ""
    };
  },
  methods: {
    editEnd() {},
    goOwnProfile() {
      console.log(this.$route.params);
      this.$router.push(`/profile/${this.$route.params.userId}`);
    },
    saveProfile() {
      console.log("!");
    },
    onFileChange(event) {
      const file = event.target.files[0];
      this.createImg(file);
    },
    createImg(file) {
      console.log(file);
      const profileFileReader = new FileReader();
      profileFileReader.onload = e => {
        this.uploadImg = e.target.result;
      };

      profileFileReader.readAsDataURL(file);
    }
  }
};
</script>

<style scoped>
.profile-img {
  border-radius: 50%;
}

.center-modal-enter-active,
.center-modal-leave-active {
  transition: opacity 0.2s;
}

.center-modal-enter,
.center-modal-leave-to {
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
