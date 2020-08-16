<template>
    <div class="scroll">
        <div class="h-screen target1-s">
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
                    <p class="target1 text-white text-xl tracking-wider">
                        自分って記憶力ないよなぁと感じたことはありませんか？？
                    </p>
                </div>
                <a class="target-btn-white flex-none text-center" href="#"
                    ><span></span>Scroll</a
                >
            </div>
        </div>
        <div class=" h-screen bg-gray-100">
            <div
                class="scroll-target flex flex-col items-center justify-center"
            >
                <div class="flex-grow flex items-center justify-end ">
                    <div class="w-5/6 text-left">
                        <img
                            :src="'/storage/graph-3078545_1280.png'"
                            alt=""
                            class="bg-cover"
                        />
                        <p class="mb-0 mt-4 target2 text-xl tracking-wider">
                            暗記しても明日には大半の記憶が<br />
                            失ってしまいます。<br />
                            これは人としての当たり前の機能です。<br />
                            ではどうやって人は記憶をするのか？？
                        </p>
                    </div>
                </div>
                <a class="target-btn" href="#"><span></span>Scroll</a>
            </div>
        </div>
        <div class="h-screen  bg-gray-200">
            <div
                class="scroll-target flex flex-col items-center justify-center"
            >
                <div
                    class="flex-grow flex flex-col items-center justify-center"
                >
                    <div class="w-screen text-right">
                        <img
                            :src="'/storage/brain-4961452_1280.jpg'"
                            alt=""
                            class="bg-cover w-5/6"
                        />
                        <p
                            class="mb-0 mt-4 target3 text-xl tracking-wider w-5/6 pt-4 pl-3 leading-loose"
                        >
                            人は思い出すことによって<br />
                            「復習」の回数が増えれば増えるほど<br />
                            記憶の定着に繋がります。
                        </p>
                    </div>
                </div>
                <a class="target-btn " href="#"><span></span>Scroll</a>
            </div>
        </div>
        <div class="h-screen bg-gray-100">
            <div
                class="scroll-target flex flex-col items-center justify-center"
            >
                <div class="flex-grow flex items-center justify-center">
                    <p class=" m-0 target4 text-xl tracking-wider">
                        自作で問題集を作っていくアプリです。<br />
                        自作の問題を作り、反復を繰り返し、知識の定着を促そう。
                    </p>
                </div>
                <BaseBtn @click-btn="goIndex()" class="mb-5 p-3"
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
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 1.4s, transform 0.8s;
}
.target3 {
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 1.4s, transform 0.8s;
}

.target4 {
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

.target1-s {
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
</style>
