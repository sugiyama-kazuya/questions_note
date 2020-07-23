<template>
    <div class="h-100 relative">
        <div class="h-90">
            <TheHeader class="h-8">
                <template v-slot:titleName>
                    <h5 class="m-0">Create</h5>
                </template>
            </TheHeader>

            <div class="h-92">
                <div class="w-full box-border px-3 h-16 h-10 flex items-center">
                    <button
                        type="submit"
                        form="createForm"
                        class="bg-blue-400 text-white font-bold py-2 px-4 rounded w-full h-60 focus:outline-none outline-none focus:bg-blue-400"
                    >
                        作成
                    </button>
                </div>

                <main class="w-full overflow-y-scroll h-90 relative">
                    <!-- 問題の追加 -->
                    <form
                        id="createForm"
                        class="pb-8"
                        @submit.prevent="newProblemCreate"
                    >
                        <div class="bg-gray-200 p-3 mb-3">
                            <FormLabel>
                                <template>問題</template>
                            </FormLabel>
                            <transition name="validateError">
                                <div v-if="validationErrorMsg.problem">
                                    <FormValidationErrorMessage
                                        :errorMessages="
                                            validationErrorMsg.problem
                                        "
                                    />
                                </div>
                            </transition>
                            <FormTextarea
                                v-model="form.problem"
                                :placeholder="'問題文 ＊200文字以内入力可'"
                            ></FormTextarea>

                            <div class="flex justify-between px-3 items-center">
                                <p class="m-0">画像を選択</p>
                                <div class="text-right">
                                    <label>
                                        <font-awesome-icon
                                            icon="angle-right"
                                            class="text-3xl text-gray-400"
                                        />
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
                                    <FormValidationErrorMessage
                                        :errorMessages="
                                            validationErrorMsg.answer
                                        "
                                    />
                                </div>
                            </transition>
                            <FormTextarea
                                v-model="form.answer"
                                :placeholder="'解答文 ＊200文字以内入力可'"
                            ></FormTextarea>
                            <div class="flex justify-between px-3 items-center">
                                <p class="m-0">画像を選択</p>
                                <div class="text-right">
                                    <label>
                                        <font-awesome-icon
                                            icon="angle-right"
                                            class="text-3xl text-gray-400"
                                        />
                                        <input type="file" class="hidden" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- URLの追加 -->
                        <transition name="validateError">
                            <div v-if="validationErrorMsg.url" class="px-3">
                                <FormValidationErrorMessage
                                    :errorMessages="validationErrorMsg.url"
                                />
                            </div>
                        </transition>
                        <div class="flex flex-col bg-gray-200 mb-3">
                            <div class="flex p-1">
                                <div class="py-2 px-4">
                                    <span>URL</span>
                                </div>
                                <div
                                    class="flex justify-between w-full items-center"
                                >
                                    <input
                                        v-model="form.url"
                                        type="text"
                                        class="py-2 px-2 w-full bg-gray-200 focus:outline-none"
                                        placeholder="https://"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- 問題集への追加 -->
                        <transition name="validateError">
                            <div
                                v-if="validationErrorMsg.exerciseBook"
                                class="px-3"
                            >
                                <FormValidationErrorMessage
                                    :errorMessages="
                                        validationErrorMsg.exerciseBook
                                    "
                                />
                            </div>
                        </transition>

                        <div class="flex flex-col bg-gray-200 mb-6">
                            <div class="flex">
                                <div class="py-2 px-4">
                                    <font-awesome-icon
                                        icon="book"
                                        class="text-3xl text-blue-400"
                                    />
                                </div>
                                <div
                                    @click="openSelectedExerciseBook"
                                    class="flex justify-between w-full items-center"
                                >
                                    <span>問題集への追加</span>
                                    <div class="mr-8 flex items-center">
                                        <p class="m-0 mr-3">
                                            {{
                                                exerciseBookSelected.recordText
                                            }}
                                        </p>
                                        <input
                                            v-model="form.exerciseBook"
                                            type="text"
                                            class="hidden"
                                        />
                                        <font-awesome-icon
                                            icon="angle-right"
                                            class="text-3xl text-gray-400"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <TheLoading
                        v-if="loading.isLoading"
                        :loading="loading.isLoading"
                        :opacity="loading.opacity"
                    />
                </main>
            </div>
            <!-- フラッシュメッセージ -->
            <transition name="center-modal">
                <CenterModal v-if="isFlashMsg">
                    <div class="p-2 bg-gray-800 text-white rounded-md">
                        {{ flashMsg.text }}
                    </div>
                </CenterModal>
            </transition>
        </div>
        <TheFooter />

        <!-- 問題集への追加（セレクトボックス）-->
        <transition name="select">
            <SelectModal
                v-if="exerciseBookSelected.isModal"
                :items="exerciseBooks"
                @selected="exerciseBookRecordChanged"
                @close="closeSelectedExerciseBook"
            >
                <template v-slot:title class="py-2">問題集の追加</template>
                <template v-slot:add-list>
                    <li
                        @click="openExerciseBookNewAdd"
                        class="list-none w-full h-2.5rem leading-10 mt-2 hover:bg-gray-300"
                    >
                        新規追加
                    </li>
                </template>
            </SelectModal>
        </transition>

        <!-- 問題集の新規作成（モーダル）-->
        <transition name="new-problem">
            <CenterModal v-if="exerciseBookNewAdd.isModal">
                <template>
                    <h1
                        class="bg-blue-400 text-center py-3 text-white text-base m-0 font-bold"
                    >
                        新規追加
                    </h1>
                    <form>
                        <div
                            class="flex flex-col justify-center items-center p-3 box-border h-100"
                        >
                            <div
                                class="w-full pm-2 h-100 flex flex-col justify-center"
                            >
                                <div class="py-2">
                                    <transition name="validateError">
                                        <div v-if="exerciseBookNewAdd.errorMsg">
                                            <p
                                                v-for="errorMsg in exerciseBookNewAdd.errorMsg"
                                                :key="errorMsg.index"
                                                class="text-red-500 text-xs italic m-0 p-1"
                                            >
                                                {{ errorMsg[0] }}
                                            </p>
                                        </div>
                                    </transition>
                                </div>
                                <FormLabel class="mb-1">
                                    <template>問題集の名前</template>
                                </FormLabel>
                                <FormText
                                    v-model="
                                        exerciseBookNewAdd.form.exerciseBook
                                    "
                                />

                                <div class="flex justify-end w-full pm-2 mt-3">
                                    <BaseButton
                                        :color="'bg-pink-400'"
                                        :text="'text-white'"
                                        @click.native="cancelExerciseBookNewAdd"
                                    >
                                        <template>キャンセル</template>
                                    </BaseButton>
                                    <BaseButton
                                        :color="'bg-blue-400'"
                                        :text="'text-white'"
                                        @click.native="createExerciseBook"
                                        class="ml-2"
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
    </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import TheFooter from "../components/TheFooter";
import TheLoading from "../components/TheLoading";
import SelectModal from "../components/SelectModal";
import CenterModal from "../components/CenterModal";
import FormLabel from "../components/form/FormLabel";
import FormText from "../components/form/FormText";
import FormTextarea from "../components/form/FormTextarea";
import FormValidationErrorMessage from "../components/form/FormValidationErrorMessage";
import BaseButton from "../components/BaseButton";

import { UNPROCESSABLE_ENTITY, INTERNAL_SERVER_ERROR, OK } from "../util";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faAngleRight,
    faClipboardList,
    faTimes,
    faBook,
    faSearch
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
        FormText,
        FormTextarea,
        FormValidationErrorMessage,
        BaseButton
    },

    data: () => ({
        exerciseBooks: [],
        form: {
            problem: "",
            answer: "",
            url: "",
            exerciseBook: ""
        },
        validationErrorMsg: "",
        // 問題集の新規追加
        exerciseBookNewAdd: {
            isModal: false,
            errorMsg: null,
            form: {
                exerciseBook: ""
            }
        },

        // 問題集のセレクトボックス
        exerciseBookSelected: {
            isModal: false,
            recordText: "選択してください"
        },
        flashMsg: {
            text: "問題を作成しました。",
            speed: 2000
        },
        loading: {
            isLoading: false,
            fullPage: false,
            opacity: 0
        }
    }),

    computed: {
        isFlashMsg() {
            return this.$store.state.flashMessage.visible;
        }
    },

    async mounted() {
        this.loading.isLoading = true;
        const response = await axios
            .get("/api/problems/create")
            .catch(error => error.response || error);

        if (response.status === INTERNAL_SERVER_ERROR) {
            this.$route.push("/500");
            return;
        }

        if (response.status === OK) {
            this.exerciseBooks = response.data.exercise_book_list;
            this.loading.isLoading = false;
            return;
        }
    },

    methods: {
        // 問題集への追加のセレクトボックスを開く
        openSelectedExerciseBook() {
            this.exerciseBookSelected.isModal = true;
        },

        // 問題集への追加のセレクトボックスを閉じる
        closeSelectedExerciseBook(status) {
            this.exerciseBookSelected.isModal = status;
        },

        //問題集への追加レコードの文字を変更
        exerciseBookRecordChanged(name) {
            this.form.exerciseBook = name;
            this.exerciseBookSelected.recordText = this.LengthAdjustment(name);
        },

        // 問題集の新規作成モーダルを開く
        openExerciseBookNewAdd() {
            this.exerciseBookNewAdd.isModal = true;
            this.exerciseBookSelected.isModal = false;
        },

        // 問題集の新規作成モーダルのキャンセル
        cancelExerciseBookNewAdd() {
            this.exerciseBookNewAdd.isModal = false;
            this.exerciseBookNewAddClearForm();
        },

        // 問題集の新規作成
        async createExerciseBook() {
            const response = await axios
                .post("/api/exercise-books", this.exerciseBookNewAdd.form)
                .catch(error => error.response || error);

            if (response.status === UNPROCESSABLE_ENTITY) {
                this.exerciseBookNewAdd.errorMsg = response.data.errors;
                return;
            }

            if (response.status === OK) {
                const exerciseBook = response.data.exerciseBook;
                this.assignmentToEach("exerciseBook", exerciseBook);
                this.exerciseBookNewAddClearForm();
                this.exerciseBookNewAdd.isModal = false;
                return;
            }
        },

        // 問題の作成
        async newProblemCreate() {
            this.exerciseBookNewAdd.errorMsg = "";
            const response = await axios
                .post("/api/problems", this.form)
                .catch(error => error.response || error);

            if (response.status === INTERNAL_SERVER_ERROR) {
                this.$route.push("/500");
                return;
            }

            if (response.status === UNPROCESSABLE_ENTITY) {
                this.validationErrorMsg = response.data.errors;
                return;
            }

            if (response.status === OK) {
                await this.$store.commit("flashMessage/setVisible", true);
                if (this.isFlashMsg) {
                    this.$store.dispatch(
                        "flashMessage/hideFlashMsg",
                        this.flashMsg.speed
                    );
                    this.allClearForm();
                }
            }
        },

        /** Iterator */
        assignmentToEach(genre, item) {
            const target = String(genre);
            const targetSelected = String(`${target}Selected`);
            this.form[target] = item;
            this.exerciseBookNewAdd.form[target] = item;
            this[targetSelected].recordText = this.LengthAdjustment(item);
        },

        //文字の長さの調整
        LengthAdjustment(name) {
            let nameLength = name.length;
            if (nameLength >= 12) {
                const exerciseBookName = name.substring(0, 12) + "...";
                return exerciseBookName;
            }
            return name;
        },

        allClearForm() {
            this.form = {
                problem: "",
                answer: "",
                exerciseBook: ""
            };

            this.exerciseBookNewAddClearForm();
            this.exerciseBookSelected.recordText = "選択してください";
        },

        // 問題集の作成モーダルのフォームをクリア
        exerciseBookNewAddClearForm() {
            this.exerciseBookNewAdd = {
                errorMsg: "",
                form: {
                    exerciseBook: ""
                }
            };
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
