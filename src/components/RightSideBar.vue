<template>
  <div class="q-pa-md">
    <p class="text-primary text-h6">
      {{ this.$store.state.myAuth?.firstName }}
      {{ this.$store.state.myAuth?.lastName }}
    </p>
    <!-- <y class="q-pa-lg text-pink"> - {{ this.$store.state.selected }} </y> -->
    <q-item> </q-item>
  </div>
</template>

<script>
export default {
  name: 'RightSideBar',
};
</script>

<style></style>
