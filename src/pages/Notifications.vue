<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible || !this.jobs">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-page class="q-pa-md" v-show="this.visible">
    <p class="text-center text-positive text-h6">
      <u>Unread Notifications:</u>
    </p>

    <div class="q-pa-md" style="">
      <div v-for="item in this.jobs" class="q-my-md">
        <q-card
          class="q-pa-xs row justify-center extra-constrained1"
          bordered
          v-if="item.status == 'Unread'"
        >
          <q-card-section>
            <p class="text-center text-bold text-positive">
              {{ item.no + '. ' + item.title }}
            </p>
            {{ item.message }}<br />
            <q-separator class="q-mt-sm" />
            <y class="text-center text-caption text-positive">
              ~ {{ item.date }}
            </y>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- <div class="row justify-center q-mb-md" @click="this.readNotifications()">
      <q-btn
        class="bg-positive text-white"
        label="Mark as read"
        icon="fas fa-check"
        dense
      />
    </div> -->
    <q-separator />
    <q-separator class="q-my-md" />
    <p class="text-center text-primary text-h6"><u>Read Notifications:</u></p>

    <div class="q-pa-md" style="">
      <div class="q-my-md" v-for="item in this.jobs">
        <q-card
          class="q-pa-xs row justify-center extra-constrained1"
          bordered
          v-if="item.status == 'Read'"
        >
          <q-card-section>
            <p class="text-center text-bold text-primary">
              {{ item.no + '. ' + item.title }}
            </p>
            {{ item.message }}<br />
            <q-separator class="q-mt-sm" />
            <y class="text-center text-caption text-primary">
              ~ {{ item.date }}
            </y>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { api } from 'boot/axios';
export default {
  name: 'Jobs',
  data() {
    return {
      jobs: ref(null),
      visible: ref(false),
    };
  },
  methods: {
    openWindow(x) {
      window.open(x, '_blank');
    },

    readNotifications() {
      api
        .post(
          this.$store.state.apiLink + 'general.php',
          {
            request: 'readNotifications',
            username: this.$store.state.myAuth.username,
          },
          { timeout: this.$store.state.timeout }
        )
        .then((res) => {
          console.log(res.data);
          this.$store.commit('notifications', []);
        });
    },

    startup() {
      api
        .post(
          this.$store.state.apiLink + 'general.php',
          {
            // referral: this.$route.query.u,
            request: 'getAllNotifications',
            username: this.$store.state.myAuth.username,
          },
          { timeout: this.$store.state.timeout }
        )
        .then((response) => {
          var res = response.data;
          // console.log(res);
          if (res) {
            // console.log(res);

            this.jobs = res;

            // this.readNotifications();
          } else {
          }
        })
        .catch((err) => {
          if (err.code == 'ECONNABORTED') {
            this.errorMessage = `the internet connection is too slow. Try again later`;
            this.alert = true;
          }
        });
    },
  },

  mounted() {
    // this.jobs = this.$store.state.notifications;
    // console.log(this.jobs);
    this.readNotifications();
    // // this.$store.dispatch('checkOnline');
    setTimeout((t) => {
      this.visible = true;
      if (!this.$store.state.myAuth) {
        this.$store.commit('authenticator', null);
      }
      this.readNotifications();
      // this.readNotifications();
    }, this.$store.state.spinner * 3);
    this.startup();
  },
};
</script>

<style>
.my-card {
  max-width: 450px;
}
</style>
