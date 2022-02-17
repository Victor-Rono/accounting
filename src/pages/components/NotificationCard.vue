<template>
  <q-card
    class="q-pa-xs row justify-center extra-constrained1"
    bordered
    @click="redirect(this.item.route)"
  >
    <q-card-section>
      <p class="text-center text-bold text-primary">
        {{ item.no + '. ' + item.title }}
      </p>
      {{ item.message }}<br />
      <q-separator class="q-mt-sm" />
      <y class="text-center text-caption text-primary"> ~ {{ item.date }} </y>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  name: 'NotificationCard',
  props: ['item', 'redirect'],

  startup() {
    route: this.item.route;
  },

  methods: {},

  mounted() {
    // console.log(this.item);
  },
};
</script>

<style></style>
