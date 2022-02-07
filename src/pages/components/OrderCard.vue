<template>
  <q-card
    bordered
    flat
    class="q-ma-md"
    :class="[this.order.sent == 'No' ? 'selected' : 'zz']"
    @click="redirect(this.order.id)"
  >
    <div class="q-ma-md">
      <p class="text-bold text-center" style="text-transform: uppercase">
        <y class="text-pink">{{ this.order.fullName }}</y> ORDER #{{
          this.order.id
        }}
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
  props: ['order'],

  setup() {
    return {};
  },

  data() {
    return {
      items: ref(false),
      apiLink: ref(this.$store.state.apiLink),
    };
  },
  mounted() {
    this.startup();
    console.log(this.order);
  },

  methods: {
    startup() {
      api
        .post(this.apiLink + 'orders.php', {
          request: 'getOrder',
          id: this.orderId,
        })
        .then((r) => {
          // var res = r.data;
          this.items = r.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },

    redirect(x) {
      this.$router.push('order2?item=' + x);
    },
  },
};
</script>

<style>
.selected {
  border: 3px solid #d6092b;
}
</style>
