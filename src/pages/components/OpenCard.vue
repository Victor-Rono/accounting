<template>
  <q-card
    bordered
    flat
    class="q-ma-md blue-border"
    :class="[this.order.sent == 'No' ? 'selected' : 'zz']"
    @click="redirect(this.order.id)"
  >
    <div class="q-ma-md">
      <p class="text-bold text-center" style="text-transform: uppercase">
        <y class="text-pink">{{ this.order.fullName }}</y> ORDER #{{
          this.order.id
        }}
        &nbsp;
        <q-btn flat size="sm" class="text-primary" icon="fas fa-gavel" no-caps
          ><q-badge class="bg-red text-white" floating rounded>{{
            this.bids.length
          }}</q-badge></q-btn
        >
      </p>
    </div>

    <div class="q-ma-md">
      <table class="my-table" style="min-width: 23rem">
        <tr class="my-table">
          <th class="my-table">No.</th>
          <th class="my-table">Item Name</th>
          <th class="my-table">Quantity</th>
        </tr>
        <tr class="my-table" v-for="item in this.order.order_items">
          <td class="my-table">{{ item.id }}</td>
          <td class="my-table">{{ item.itemName }}</td>
          <td class="my-table">{{ item.quantity }}</td>
        </tr>
      </table>
      <p class="text-bold q-my-sm text-center">
        DEPARTMENT: {{ this.order.department }} <br />
        <y class="text-primary">{{ this.order.date }}</y>
      </p>
    </div>
  </q-card>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useRoute } from 'vue-router';
import VueNumberFormat from 'vue-number-format';

export default {
  name: 'AccountantCard',
  props: ['order', 'start', 'loadData'],

  setup() {
    return {};
  },

  data() {
    return {
      items: ref(false),
      apiLink: ref(this.$store.state.apiLink),
      options: ref([]),
      contractor: ref(null),
      bids: ref([]),
    };
  },
  mounted() {
    // this.startup();
    api
      .post(
        this.$store.state.apiLink + 'accounting.php',
        {
          request: 'bids',
          id: this.order.id,
        },
        { timeout: this.$store.state.timeout }
      )
      .then((res) => {
        this.bids = res.data;
        // console.log(res.data);
      });
  },

  methods: {
    startup() {
      if (this.order.id) {
        api
          .post(this.apiLink + 'orders.php', {
            request: 'getOrder',
            id: this.order.id,
          })
          .then((r) => {
            // var res = ;
            // console.log(r.data);
            this.items = r.data;
          })
          .catch((err) => {
            console.log(err);
          });
      }
    },

    redirect(x) {
      // this.$router.push('view_order?item=' + x);
      this.loadData(this.bids, this.order);
    },

    respond(x) {
      if (x == 'accept') {
        var check = confirm('Accept this order?');
        if (check) {
          api
            .post(this.$store.state.apiLink + 'accounting.php', {
              request: 'authorize',
              id: this.order.id,
              email: this.order.email,
              admin: this.$store.state.myAuth.username,
            })
            .then((res) => {
              if (res.data == 'Success') {
                alert('Order accepted');
                this.start;
              }
            });
        }
      } else if (x == 'reject') {
        var reason = prompt('GIVE A REASON FOR REJECTING THE ORDER');

        if (reason) {
          api
            .post(this.$store.state.apiLink + 'accounting.php', {
              request: 'decline',
              id: this.order.id,
              reason: reason,
              email: this.order.email,
              admin: this.$store.state.myAuth.username,
            })
            .then((res) => {
              if (res.data == 'Success') {
                alert('Order Declined');
                this.start;
              }
            });
        }
      }
    },
  },
};
</script>

<style>
.selected {
  border: 3px solid #d6092b;
}
</style>
