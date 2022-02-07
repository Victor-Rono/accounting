<template>
  <!-- if admin show company details -->
  <!-- <div class="">
    <div
      class="q-gutter-sm row justify-around"
      v-if="this.$store.state.myAuth?.department == 'Admin'"
    >
      <q-card flat class="box">
        <y class="text-caption text-primary">ORDERS TODAY</y>

        <p class="text-h6 text-pink">&nbsp;&nbsp;90</p>
      </q-card>
      <q-card flat class="box">
        <y class="text-caption text-primary">ORDERS TODAY</y>

        <p class="text-h6 text-pink">&nbsp;&nbsp;90</p>
      </q-card>
      <q-card flat class="box">
        <y class="text-caption text-primary">ORDERS TODAY</y>

        <p class="text-h6 text-pink">&nbsp;&nbsp;90</p>
      </q-card>
    </div>
  </div> -->

  <!-- if accounting or admin, show  -->
  <div class="">
    <div
      class="q-gutter-sm row justify-around"
      v-if="
        this.$store.state.myAuth?.department == 'Admin' ||
        this.$store.state.myAuth?.department == 'Finance'
      "
    >
      <q-card flat class="box" @click="openPage('/open_orders')">
        <y class="text-caption text-primary">OPEN ORDERS</y>

        <p class="text-h6 text-pink">
          &nbsp;&nbsp;{{
            this.dashboard?.filter((r) => {
              return r.authorized == 'Yes' && r.closed == 'No';
            }).length
          }}
        </p>
      </q-card>
      <q-card flat class="box" @click="openPage('/completed')">
        <y class="text-caption text-primary">COMPLETED ORDERS</y>

        <p class="text-h6 text-pink">
          &nbsp;&nbsp;
          {{
            this.dashboard?.filter((r) => {
              return r.completed == 'Yes';
            }).length
          }}
        </p>
      </q-card>
      <q-card flat class="box" @click="openPage('/unassigned_orders')">
        <y class="text-caption text-primary">UNASSIGNED ORDERS</y>

        <p class="text-h6 text-pink">
          &nbsp;&nbsp;
          {{
            this.dashboard?.filter((r) => {
              return (
                r.contractor == null &&
                r.accepted == 'Pending' &&
                r.authorized == 'Pending'
              );
            }).length
          }}
        </p>
      </q-card>

      <q-card flat class="box" @click="openPage('/rejected_orders')">
        <y class="text-caption text-primary">REJECTED ORDERS</y>

        <p class="text-h6 text-pink">
          &nbsp;&nbsp;
          {{
            this.dashboard?.filter((r) => {
              return (
                r.contractor == null &&
                r.accepted == 'Pending' &&
                r.authorized == 'No'
              );
            }).length
          }}
        </p>
      </q-card>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { api } from 'boot/axios';
export default {
  name: 'Panel',
  setup() {},

  data() {
    return {
      dashboard: ref(null),
    };
  },

  methods: {
    startup() {
      api
        .post(
          this.$store.state.apiLink + 'general.php',
          {
            request: 'dashboard',
          },
          { timeout: this.$store.state.timeout }
        )
        .then((res) => {
          console.log(res.data);
          this.dashboard = res.data;
        });
    },

    openPage(x) {
      this.$router.push(x);
    },
  },

  mounted() {
    this.startup();
  },
};
</script>

<style>
.box {
  cursor: pointer;
  border-right: 2px solid pink;
  padding: 5px;
}
</style>
