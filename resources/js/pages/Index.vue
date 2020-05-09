<template>
  <div class="relative h-full">
    <Header>
      <template v-slot:titleName>
        <h5>HOME</h5>
      </template>
    </Header>
    <Footer />
  </div>
</template>

<script>
import Header from "../components/Header";
import Footer from "../components/Footer";

export default {
  name: "Index",
  components: {
    Header,
    Footer
  }
};
</script>
