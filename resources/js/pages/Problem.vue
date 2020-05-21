<template>
    <div class="relative h-100">
        <div class="h-90 bg-gray-200">
            <Header class="h-8">
                <template v-if="isBackBtnDisplay" v-slot:leftSide>
                    <font-awesome-icon
                        icon="arrow-left"
                        @click="returnBack"
                        class="text-3xl text-white"
                    ></font-awesome-icon>
                </template>
                <template v-slot:titleName>
                    <h5 class="text-2xl m-0">
                        {{ currentNumber }}/{{ currentProblemData.count }}
                    </h5>
                </template>
                <template v-slot:rightSide>
                    <font-awesome-icon
                        @click="inMiddleEnd"
                        icon="times"
                        class="text-4xl text-white"
                    ></font-awesome-icon>
                </template>
            </Header>
            <main class="h-92">
                <Loading v-if="isLoading" :loading="isLoading" />
                <div class="flex flex-col h-100 py-5">
                    <div class="h-90 px-3 py-4">
                        <div class="bg-white h-100 shadow-sm rounded-sm">
                            <div class="bg-blue-300 h-8 flex items-center">
                                <div class="w-1/3 pl-3 flex items-center">
                                    <font-awesome-icon
                                        icon="exchange-alt"
                                        v-if="displaySecond"
                                        @click="swapProblemAnswer"
                                        class="text-2xl text-gray-600"
                                    ></font-awesome-icon>
                                </div>
                                <div class="w-1/3">
                                    <h1
                                        v-if="problemDisplay"
                                        class="text-white text-center text-base m-0"
                                    >
                                        問題
                                    </h1>
                                    <h1
                                        v-if="answerDisplay"
                                        class="text-white text-center text-base m-0"
                                    >
                                        解答
                                    </h1>
                                </div>
                                <div class="w-1/3"></div>
                            </div>
                            <div class="h-82">
                                <div
                                    class="flex justify-center items-center overflow-y-scroll h-100"
                                >
                                    <transition name="card-text">
                                        <p
                                            v-if="
                                                currentProblemData.problem &&
                                                    problemDisplay
                                            "
                                            class="m-0 p-4"
                                        >
                                            {{
                                                currentProblemData.problem[
                                                    orderNumber
                                                ].content
                                            }}
                                        </p>
                                    </transition>
                                    <transition name="card-text">
                                        <p
                                            v-if="
                                                currentProblemData.problem &&
                                                    answerDisplay
                                            "
                                            class="m-0 p-4"
                                        >
                                            {{
                                                currentProblemData.problem[
                                                    orderNumber
                                                ].answer
                                            }}
                                        </p>
                                    </transition>
                                </div>
                            </div>

                            <div
                                class="h-10 flex justify-end items-center px-3 py-1"
                            >
                                <font-awesome-icon
                                    icon="ellipsis-v"
                                    class="text-2xl text-gray-600"
                                ></font-awesome-icon>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="problemDisplay"
                        class="h-10 px-3 flex items-center"
                    >
                        <DefaultBtn @click.native="showAnswer">
                            <template>答え</template>
                        </DefaultBtn>
                    </div>
                    <div
                        v-if="answerDisplay"
                        class="h-10 px-3 flex items-center"
                    >
                        <AnswerCorrectnessBtn
                            @click.native="
                                incorrect(
                                    currentProblemData.problem[orderNumber].id
                                )
                            "
                            :color="'bg-red-300'"
                            class="mr-3"
                        >
                            <template>×</template>
                        </AnswerCorrectnessBtn>
                        <AnswerCorrectnessBtn @click.native="correct">
                            <template>○</template>
                        </AnswerCorrectnessBtn>
                    </div>
                </div>
            </main>
        </div>
        <Footer />
        <transition name="center-modal">
            <CenterModal v-if="isProblemEndModal">
                <template>
                    <div class="p-3">
                        <div v-if="isCurrectAnswerAll" class="p-3">
                            <p class="text-lg">全問正解です！</p>
                        </div>
                        <div v-if="!isCurrectAnswerAll" class="text-center p-3">
                            <p>{{ `トータル：${currentProblemData.count}` }}</p>
                            <p>{{ `正解数：${currectCount}` }}</p>
                            <p>{{ `不正解数：${incurrectCount}` }}</p>
                        </div>
                        <div class="flex justify-end">
                            <CenterModalBtn
                                v-if="!isCurrectAnswerAll"
                                @click.native="incorrectProblemAgain"
                                :text="endModal.restartText"
                                :color="'bg-blue-400'"
                                class="mr-2"
                            />
                            <CenterModalBtn
                                @click.native="goHome"
                                :text="endModal.endText"
                            />
                        </div>
                    </div>
                </template>
            </CenterModal>
        </transition>
        <transition name="center-modal">
            <CenterModal v-if="endConfimationModal" :backColor="false">
                <div class="py-5 px-3">
                    <div class="p-4 text-center text-lg mb-2">
                        <span>本当に終了でよろしいですか？</span>
                    </div>
                    <div class="flex justify-center p-2">
                        <CenterModalBtn
                            @click.native="goHome"
                            :text="'一覧へ'"
                            :color="'bg-blue-400'"
                            class="mr-2"
                        />
                        <CenterModalBtn
                            @click.native="endConfimationModal = false"
                            :text="'問題へ戻る'"
                        />
                    </div>
                </div>
            </CenterModal>
        </transition>
    </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import Loading from "../components/Loading";
import CenterModal from "../components/CenterModal";
import DefaultBtn from "../components/DefaultBtn";
import AnswerCorrectnessBtn from "../components/AnswerCorrectnessBtn";
import CenterModalBtn from "../components/ProblemPlay/CenterModalBtn";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faTimes,
    faEllipsisV,
    faArrowLeft,
    faExchangeAlt
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
library.add(faTimes, faEllipsisV, faArrowLeft, faExchangeAlt);

export default {
    name: "Problem",
    components: {
        Header,
        Footer,
        FontAwesomeIcon,
        Loading,
        CenterModal,
        CenterModalBtn,
        AnswerCorrectnessBtn,
        DefaultBtn
    },
    data() {
        return {
            normal: {
                problemData: {}
            },
            wrong: {
                problemData: {},
                incorrectId: []
            },
            isCorrectAnswerFlg: false,
            orderNumber: 0,
            currentNumber: 1,
            problemDisplay: true,
            answerDisplay: false,
            displaySecond: false,
            isLoading: false,
            isProblemEndModal: false,
            isLastProblem: false,
            isCurrectAnswerAll: false,
            endModal: {
                restartText: "もう一度",
                endText: "一覧へ"
            },
            endConfimationModal: false
        };
    },
    async mounted() {
        this.isLoading = true;
        await this.$store.dispatch(
            "playProblem/getProblemAndAnswer",
            this.$route.params.id
        );
        this.isLoading = false;

        this.normal.problemData = this.$store.state.playProblem.problemData.problem_data;
    },
    methods: {
        showAnswer() {
            this.problemDisplay = false;
            this.answerDisplay = true;
            this.displaySecond = true;
        },
        nextProblem() {
            // 現在表示されている問題は、初期表示の問題か？間違えた問題か？
            const currentProblemData = this.currentProblemData;
            // 問題が最終問題の場合
            if (currentProblemData.count === this.currentNumber) {
                // 間違えた問題がない場合
                if (!this.wrong.incorrectId.length) {
                    this.isCurrectAnswerAll = true;
                }
                this.problemEnd();
                return;
            }

            this.displaySecond = false;
            this.answerDisplay = false;
            this.problemDisplay = true;
            this.orderNumber++;
            this.currentNumber++;
        },
        correct() {
            this.nextProblem();
        },
        incorrect(problemId) {
            if (problemId) {
                this.wrong.incorrectId.push(problemId);
            }
            this.nextProblem();
        },
        swapProblemAnswer() {
            this.problemDisplay = this.problemDisplay ? false : true;
            this.answerDisplay = this.answerDisplay ? false : true;
        },
        returnBack() {
            // 最後の問題を表示していた場合、ボタン内の文言を変更
            if (this.normal.problemData.count === this.currentNumber) {
                this.isLastProblem = false;
            }

            this.wrong.incorrectId.pop();
            this.orderNumber--;
            this.currentNumber--;
        },
        problemEnd() {
            this.isProblemEndModal = true;
        },
        incorrectProblemAgain() {
            // 間違えた問題がなかった場合
            if (this.wrong.incorrectId.length === 0) {
                return (this.isCurrectAnswerAll = true);
            }

            // 間違えた問題のID
            const incorrectId = this.wrong.incorrectId;
            // 間違えた問題を取得
            const incorrectProblemData = this.normal.problemData.problem.filter(
                function(problem) {
                    return incorrectId.includes(problem.id);
                }
            );
            // 取得した問題の数
            const problemCount = incorrectProblemData.length;

            // 間違えた問題の一覧を作成
            this.wrong.problemData = {
                problem: incorrectProblemData,
                count: problemCount
            };

            this.isCorrectAnswerFlg = true;
            this.displaySecond = false;
            this.answerDisplay = false;
            this.problemDisplay = true;
            this.orderNumber = 0;
            this.currentNumber = 1;
            this.isProblemEndModal = false;
            this.wrong.incorrectId = [];
        },
        goHome() {
            this.$router.push("/home");
        },
        inMiddleEnd() {
            this.endConfimationModal = true;
        }
    },
    computed: {
        isBackBtnDisplay() {
            // 最初の問題の場合は戻るボタンは非表示
            return this.currentNumber >= 2 ? true : false;
        },
        currectCount() {
            return this.currentProblemData.count - this.incurrectCount;
        },
        incurrectCount() {
            return this.wrong.incorrectId.length;
        },
        currentProblemData() {
            return this.isCorrectAnswerFlg
                ? this.wrong.problemData
                : this.normal.problemData;
        }
    }
};
</script>

<style scoped>
.center-modal-enter-active,
.center-modal-leave-active {
    transition: opacity 0.2s;
}

.center-modal-enter,
.center-modal-leave-to {
    opacity: 0;
}

.card-text-enter-active {
    transition: opacity 0.2s;
}

.card-text-enter,
.card-text-leave {
    opacity: 0;
}
</style>
