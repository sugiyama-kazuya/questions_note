<template>
    <div class="relative h-100">
        <div class="h-90 bg-gray-200">
            <Header class="h-8">
                <template v-slot:titleName>
                    <h5>Mypage</h5>
                </template>
            </Header>

            <main class="overflow-y-scroll h-92">
                <div class="bg-white">
                    <div class="text-right py-3 px-3">
                        <DefaultBtn :height="'h-3rem'" :width="'w-1/3'">
                            <template>フォローする</template>
                        </DefaultBtn>
                    </div>
                    <div class="flex flex-col justify-center items-center">
                        <div class="">
                            <img
                                class="rounded-full h-12rem w-48"
                                src="http://placeimg.com/350/250/people"
                                alt="プロフィール画像"
                            />
                        </div>
                        <div class="p-3"><span>名前</span></div>
                    </div>
                    <div class="flex items-center justify-center p-4">
                        <div class="mr-4">フォロー 1</div>
                        <div>フォロワー 2</div>
                    </div>
                </div>

                <Loading if="isLoading" :loading="isLoading" />
                <ProblemCard
                    v-for="cardData in problemCardData"
                    :key="cardData.id"
                    :cardData="cardData"
                    class="mb-4"
                ></ProblemCard>
            </main>
        </div>
        <Footer />
    </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";
import DefaultBtn from "../components/DefaultBtn";
import ProblemCard from "../components/ProblemCard";
import Loading from "../components/Loading";

export default {
    name: "Profile",
    components: {
        Header,
        Footer,
        DefaultBtn,
        ProblemCard,
        Loading
    },
    data() {
        return {
            problemCardData: {}
        };
    }
};
</script>

<style></style>
