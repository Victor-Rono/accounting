<template>
  <q-card class="my-card q-my-xs" flat bordered>
    <q-item>
      <q-item-section avatar>
        <q-avatar>
          <img src="https://cdn.quasar.dev/img/boy-avatar.png" />
        </q-avatar>
      </q-item-section>

      <q-item-section>
        <q-item-label>Victor Rono</q-item-label>
        <q-item-label caption lines="1" class="text-primary"
          >@username</q-item-label
        >
      </q-item-section>
      <q-item-section>
        <q-item-label class="text-primary text-caption"
          ><q-icon size="sm" name="room" /> <h7>{{ post.location }}</h7>
        </q-item-label>
      </q-item-section>
    </q-item>

    <q-separator />
    <q-img :src="post.imageUrl" />
    <q-separator />
    <q-card-section class="q-pt-sm">
      {{ post.caption }}
    </q-card-section>
    <q-separator />
    <q-item class="row justify-around">
      <q-item-label class="text-primary q-mb-none q-mt-sm">
        ~{{ post.date }}
      </q-item-label>

      <q-item-label class="q-mb-none">
        <q-icon size="xs" name="fas fa-comment" /> 168
      </q-item-label>

      <q-item-label class="q-mb-none">
        <q-icon size="xs" name="eva-heart-outline" /> 360
      </q-item-label>

      <q-item-label class="q-mb-none">
        <q-icon size="xs" name="share" /> 45
      </q-item-label>
    </q-item>
    <!-- <q-card-section horizontal>

      <q-card-section>
        {{ lorem }}
      </q-card-section>

      <q-separator vertical />

      <q-card-section class="col-4">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
      </q-card-section>
    </q-card-section> -->
  </q-card>
</template>

<script>
import { date } from 'quasar';
export default {
  name: 'Card',
  props: ['post'],
  setup() {
    return {
      lorem:
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    };
  },

  methods: {
    dateFilter(value) {
      const timeStamp = value.toString();
      const formattedString = date.formatDate(
        timeStamp,
        'YYYY-MM-DDTHH:mm:ss.SSSZ'
      );
      return formattedString;
    },
  },
  filters: {},
};
</script>

<style scoped>
.my-card {
}

.q-img {
  min-height: 200px;
}
</style>
