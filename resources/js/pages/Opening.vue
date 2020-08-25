<template>
    <div class="scroll">
        <div class="h-screen target-first max-h-screen">
            <div class="scroll-target m-0 flex flex-col">
                <TheHeader
                    :bg-color="'bg-transparent'"
                    class="h-5rem w-screen text-white font-mono"
                >
                    <template v-slot:leftSide>
                        <router-link
                            to="/openings"
                            class="text-white mr-3 m-0 text-lg"
                            tag="p"
                            >Question's Note</router-link
                        >
                    </template>
                    <template v-slot:rightSide>
                        <router-link to="/login" class="text-white mr-3"
                            >login</router-link
                        >
                        <router-link to="/register" class="text-white"
                            >register</router-link
                        >
                    </template>
                </TheHeader>

                <div class="flex-grow flex items-center justify-center">
                    <p
                        class="target1 text-white text-xl tracking-wider font-normal"
                    >
                        自分って記憶力ないよなぁと感じたことはありませんか？？
                    </p>
                </div>
                <div class="target-btn-white flex-none text-center">
                    <span></span>Scroll
                </div>
            </div>
        </div>
        <div class="h-screen bg-gray-100 max-h-screen">
            <div
                class="h-screen scroll-target flex flex-col items-center justify-center"
            >
                <div
                    class="flex-grow flex flex-col items-center justify-center"
                >
                    <img
                        :src="'/storage/graph-3078545_1280.png'"
                        alt=""
                        class="bg-cover img-second"
                    />
                    <p
                        class=" mb-0 p-3 target2 text-xl tracking-wider flex items-center leading-normal"
                    >
                        人の記憶は1時間後には約50%、24時間後に約75%、1ヶ月後にはほとんど記憶に残っていないという研究結果が出ています。脳の構造上、人は記憶はしにくい仕組みとなっており、暗記など記憶には得意不得意は存在しません。つまり記憶力がないというのは人として当たり前の機能なのです。ではどのように記憶して行けばいいのでしょうか？
                    </p>
                </div>
                <div class="target-btn flex-none"><span></span>Scroll</div>
            </div>
        </div>
        <div class="h-screen bg-gray-200 max-h-screen">
            <div
                class="scroll-target flex flex-col items-center justify-center"
            >
                <div
                    class="flex-grow flex flex-col items-center justify-center"
                >
                    <img
                        :src="'/storage/brain-4961452_1280.jpg'"
                        alt=""
                        class="bg-cover img-third"
                    />
                    <p
                        class="mb-0 target3 text-xl tracking-wider leading-normal px-3 py-4 flex items-center"
                    >
                        それは思い出すということです。「復習」になります。
                        人は思い出すことによって
                        つまり復習の回数が増えれば増えるほど
                        記憶の定着に繋がると言われています。
                        記憶の定着には、日頃から細かく復習するという行動がとても大切になります。
                    </p>
                </div>
                <div class="target-btn flex-none"><span></span>Scroll</div>
            </div>
        </div>
        <div class="h-screen bg-gray-100 max-h-screen">
            <div
                class="scroll-target flex flex-col items-center justify-center"
            >
                <div class="flex-grow flex items-center justify-center">
                    <p
                        class=" m-0 target4 text-xl tracking-wider leading-normal"
                    >
                        このQuestions'
                        Noteは自分で問題集を作成し、いつでもどこでも自分で作成した問題を復習できるアプリです。
                        問題集で復習を繰り返し、知識の定着を促そう。
                    </p>
                </div>
                <BaseBtn @click-btn="goIndex()" class="mb-5 p-3 flex-none"
                    >学習を始める</BaseBtn
                >
            </div>
        </div>
    </div>
</template>

<script>
import TheHeader from "../components/TheHeader";
import BaseBtn from "../components/BaseButton";

export default {
    name: "Opening",

    components: {
        BaseBtn,
        TheHeader
    },

    data() {
        return {
            observer1: null
        };
    },

    mounted() {
        const options = {
            root: null,
            rootMargin: "-50% 0px -50% 0px",
            threshold: 0
        };
        const obj = this;

        const target1 = document.querySelectorAll(".target1");
        target1.forEach(function(target) {
            obj.onIntersect(target);
        });
        const target2 = document.querySelectorAll(".target2");
        target2.forEach(function(target) {
            obj.onIntersect(target);
        });
        const target3 = document.querySelectorAll(".target3");
        target3.forEach(function(target) {
            obj.onIntersect(target);
        });
        const target4 = document.querySelectorAll(".target4");
        target4.forEach(function(target) {
            obj.onIntersect(target);
        });
    },

    methods: {
        onIntersect(target, options = {}) {
            const observer = new IntersectionObserver(
                this.addShowClass,
                options
            );
            observer.observe(target);
        },

        addShowClass(entries) {
            for (const e of entries) {
                if (e.isIntersecting) {
                    e.target.classList.add("show");
                } else {
                    e.target.classList.remove("show");
                }
            }
        },

        goIndex() {
            this.$router.push("/exercise-books");
        }
    }
};
</script>

<style scoped>
.scroll-target {
    height: 100vh;
    scroll-snap-align: start;
}
.target1 {
    opacity: 0;
    padding: 10%;
    transform: translateX(-40px);
    transition: opacity 1.4s, transform 0.8s;
}
.target2 {
    text-indent: 1em;
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 1.4s, transform 0.8s;
}
.target3 {
    text-indent: 1em;
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 1.4s, transform 0.8s;
}

.target4 {
    text-indent: 1em;
    opacity: 0;
    padding: 10%;
    transform: translateX(-40px);
    transition: opacity 1.4s, transform 0.8s;
}

.show {
    opacity: 1;
    transform: translateX(0);
}

.scroll {
    overflow-y: scroll;
    scroll-snap-type: y mandatory;
    width: 100%;
    height: 100vh;
    -webkit-overflow-scrolling: touch;
}

.target-first {
    background-image: url("/storage/frustration-1241534_1920.jpg");
    background-size: cover;
}

.target-btn-show {
    opacity: 1 !important;
}

.target-btn {
    margin-bottom: 1rem;
    opacity: 1;
    transition: opacity 1.4s, transform 0.8s;
    position: relative;
    padding-top: 60px;
    z-index: 2;
    display: inline-block;
    text-decoration: none;
    color: #a0aec0;
    text-align: center;
}

.target-btn span {
    position: absolute;
    top: 0;
    left: 50%;
    width: 46px;
    height: 46px;
    margin-left: -23px;
    border: 1px solid #a0aec0;
    border-radius: 100%;
    box-sizing: border-box;
}

.target-btn span::after {
    position: absolute;
    top: 50%;
    left: 50%;
    content: "";
    width: 16px;
    height: 16px;
    margin: -12px 0 0 -8px;
    border-left: 1px solid #a0aec0;
    border-bottom: 1px solid #a0aec0;
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
    box-sizing: border-box;
}

.target-btn-white {
    margin-bottom: 1rem;
    opacity: 1;
    transition: opacity 1.4s, transform 0.8s;
    position: relative;
    padding-top: 60px;
    z-index: 2;
    display: inline-block;
    text-decoration: none;
    color: #fff;
}
.target-btn-white span {
    position: absolute;
    top: 0;
    left: 50%;
    width: 46px;
    height: 46px;
    margin-left: -23px;
    border: 1px solid #fff;
    border-radius: 100%;
    box-sizing: border-box;
}

.target-btn-white span::after {
    position: absolute;
    top: 50%;
    left: 50%;
    content: "";
    width: 16px;
    height: 16px;
    margin: -12px 0 0 -8px;
    border-left: 1px solid #fff;
    border-bottom: 1px solid #fff;
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg);
    box-sizing: border-box;
}

@media screen and (min-width: 960px) {
    .img-second,
    .img-third {
        max-height: 50%;
        max-width: 50%;
    }
}
</style>
