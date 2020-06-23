<template>
  <div class="relative h-100">
    <div class="h-90">
      <TheHeader class="h-8 bg-gray-200">
        <template v-slot:titleName>
          <h5 class="m-0">List</h5>
        </template>
      </TheHeader>
      <div class="flex flex-col h-92 text-gray-600">
        <div class="flex border-b h-5">
          <div
            @click="exerciseBookShow()"
            class="w-1/2 flex justify-center items-center p-2"
            :class="exerciseBook.isShow ? 'active-tab' : ''"
          >問題集</div>
          <div
            @click="userShow()"
            class="w-1/2 flex justify-center items-center p-2"
            :class="user.isShow ? 'active-tab' : ''"
          >ユーザー</div>
        </div>
        <main class="w-100 h-95">
          <div class="relative h-100 w-100 overflow-y-scroll py-4">
            <!-- 問題集一覧 -->
            <transition name="main">
              <div v-if="exerciseBook.isShow" class="flex flex-col w-screen h-100">
                <BaseSearchBox :placeholder="exerciseBook.placeholder"></BaseSearchBox>
                <nav class="flex flex-col h-100 w-screen px-4 items-center">
                  <li class="flex flex-col w-4/5 mb-4 p-2 border list-none rounded-md shadow-md">
                    <div class="flex justify-between items-center">
                      <span>PHPとは</span>
                      <font-awesome-icon
                        @click="openEditModal()"
                        icon="edit"
                        class="text-2xl text-gray-600"
                      ></font-awesome-icon>
                    </div>
                    <div class="text-xs text-gray-500">カテゴリー：PHP</div>
                  </li>
                </nav>
              </div>
            </transition>
            <!-- ユーザ一覧 -->
            <transition name="sav">
              <div v-if="user.isShow" class="h-100 w-100">
                <BaseSearchBox :placeholder="user.placeholder"></BaseSearchBox>
                <div class="flex justify-center items-center text-gray-400">ユーザー名を入力してください。</div>
              </div>
            </transition>
          </div>
        </main>
      </div>
    </div>
    <THeFooter />

    <!-- 問題集のセレクトモーダル -->
    <transition name="center-modal">
      <CenterModal v-if="isEditModal" :width="'w-1/3'">
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
            @click.native="closeEditModal()"
            :text="'戻る'"
            :width="'w-2/3'"
            :color="'bg-green-400'"
          ></CenterModalBtn>
        </div>
      </CenterModal>
    </transition>
  </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import THeFooter from "../components/TheFooter";
import CenterModal from "../components/CenterModal";
import CenterModalBtn from "../components/CenterModalBtn";
import BaseSearchBox from "../components/BaseSearchBox";

import { library } from "@fortawesome/fontawesome-svg-core";
import { faEdit } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faEdit);

export default {
  name: "ProblemsList",
  components: {
    FontAwesomeIcon,
    TheHeader,
    THeFooter,
    CenterModal,
    CenterModalBtn,
    BaseSearchBox
  },

  data() {
    return {
      exerciseBook: {
        isShow: true,
        placeholder: "問題集の名前を入力"
      },
      user: {
        isShow: false,
        placeholder: "ユーザー名を入力"
      },
      isEditModal: false
    };
  },

  computed: {},

  methods: {
    exerciseBookShow() {
      this.user.isShow = false;
      this.exerciseBook.isShow = true;
    },
    userShow() {
      this.exerciseBook.isShow = false;
      this.user.isShow = true;
    },
    openEditModal() {
      this.isEditModal = true;
    },
    closeEditModal() {
      this.isEditModal = false;
    },
    goEdit() {
      console.log("成功");
    }
  }
};
</script>

<style scoped>
.active-tab {
  border-bottom: solid 2px #667eea;
  color: #667eea;
  transition: border-bottom 0.4s linear;
}

.main-enter-active,
.main-leave-active,
.sav-enter-active,
.sav-leave-active {
  transition: 0.3s linear;
  position: absolute;
}

.main-enter,
.main-leave-to {
  transform: translateX(-100%);
}
.main-enter-to,
.sav-enter-to {
  transform: translateX(0);
}
.main-leave,
.sav-leave {
  transform: translateX(0);
}
.sav-enter,
.sav-leave-to {
  transform: translateX(100%);
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
