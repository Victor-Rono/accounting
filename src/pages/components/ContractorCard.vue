<template>
  <q-card
    bordered
    flat
    class="q-ma-md"
    :class="[this.submitted ? 'selected' : 'zz']"
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

    <div class="row justify-center" v-if="this.bidPrice && this.deadLine">
      <div class="q-pa-sm">
        <strong><u>YOUR BID:</u></strong
        ><br />
        <tag>
          <tag class="text-bold">PRICE (KSH):</tag>
          <tag class="text-primary text-bold"
            >&nbsp;
            {{ this.numberWithCommas(this.bidPrice) }}
          </tag> </tag
        ><br />
        <tag>
          <tag class="text-bold">DEADLINE:</tag>
          <tag class="text-primary text-bold"
            >&nbsp;
            {{ this.deadLine }}
          </tag>
        </tag>
      </div>
    </div>

    <div class="row justify-center q-pa-md">
      <q-badge
        class="bg-positive text-white"
        v-if="this.order.accepted == 'Yes'"
      >
        You have already made a bid for this order
      </q-badge>
      <div class="">
        <q-btn
          class="bg-pink text-white q-mx-sm"
          icon-right="fas fa-gavel"
          label="Bid"
          v-if="!this.bidPrice || !this.deadLine"
          no-caps
          flat
          @click="this.prompt = true"
        ></q-btn>

        <q-btn
          class="bg-primary text-white q-mx-sm"
          icon="fas fa-paper-plane"
          label="Submit"
          v-if="this.bidPrice && this.deadLine && !this.submitted"
          no-caps
          flat
          dense
          @click="submit()"
        ></q-btn>

        <q-btn
          class="bg-red text-white"
          icon="fas fa-times"
          label="cancel"
          v-if="this.bidPrice && this.deadLine && !this.submitted"
          no-caps
          flat
          dense
          @click="this.deadLine = this.bidPrice = null"
        ></q-btn>
      </div>
    </div>

    <q-dialog v-model="dateDialog">
      <q-card>
        <p class="text-primary text-bold q-ma-sm">
          WHEN WILL YOU COMPLETE THE ORDER?
        </p>
        <q-date v-model="deadLine" minimal>
          <div class="row items-center justify-end q-gutter-sm">
            <q-btn label="Cancel" color="primary" flat v-close-popup />
            <q-btn
              label="OK"
              color="primary"
              flat
              @click="this.save()"
              v-close-popup
            />
          </div>
        </q-date>
      </q-card>
    </q-dialog>
  </q-card>

  <q-dialog v-model="prompt" persistent>
    <q-card style="min-width: 350px">
      <q-card-section>
        <div class="text-h6">Your Price (numbers only)</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-input
          dense
          v-model="price"
          autofocus
          @keyup.enter="prompt = false"
        />
      </q-card-section>

      <q-card-actions align="right" class="text-primary">
        <q-btn flat label="Cancel" @click="this.price = null" v-close-popup />
        <q-btn flat label="Ok" @click="this.bid()" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useRoute } from 'vue-router';
import VueNumberFormat from 'vue-number-format';

export default {
  name: 'AccountantCard',
  props: ['order', 'start'],

  setup() {
    return {};
  },

  data() {
    return {
      items: ref(false),
      apiLink: ref(this.$store.state.apiLink),
      bidPrice: ref(null),
      deadLine: ref(null),
      submitted: ref(null),
      dateDialog: ref(false),
      proxyDate: ref('WHEN WILL THE ORDER BE DELIVERED?'),
      prompt: ref(false),
      price: ref(null),
    };
  },
  mounted() {
    this.startup();
    console.log(this.order);

    api
      .post(this.$store.state.apiLink + 'accounting.php', {
        request: 'myBid',
        id: this.order.id,
        username: this.$store.state.myAuth.username,
      })
      .then((res) => {
        var x = res.data;
        console.log(x);
        if (x.price && x.deadline) {
          this.deadLine = x.deadline;
          this.bidPrice = x.price;
          this.submitted = true;
        }
      });
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

    bid() {
      if (this.price) {
        if (!isNaN(this.price)) {
          this.bidPrice = this.price;
          var deadline = null;

          setTimeout((t) => {
            this.dateDialog = true;

            if (deadline) {
              this.deadLine = deadline;
              console.log(price, deadline);
            }
          }, 600);
        } else {
          alert('Price must be a number');
        }
      }
    },
    numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    },
    save() {
      console.log(this.proxyDate);
      const date = new Date();
      let d = date.getDate();
      if (d < 10) {
        d = '0' + d;
      }

      let m = date.getMonth() + 1;
      if (m < 10) {
        m = '0' + m;
      }
      let y = date.getFullYear();
      console.log(`${y}/${m}/${d}`);
    },
    submit() {
      var x = confirm('Submit your bid?');
      if (x) {
        api
          .post(this.$store.state.apiLink + 'accounting.php', {
            request: 'submitBid',
            id: this.order.id,
            price: this.bidPrice,
            deadline: this.deadLine,
            username: this.$store.state.myAuth.username,
            fullName: `${this.$store.state.myAuth.firstName} ${this.$store.state.myAuth.lastName}`,
          })
          .then((res) => {
            if (res.data == 'Success') {
              alert('Your bid was submitted');
              this.startup();
              this.start;
              this.submitted = true;
            }
          });
      }
    },

    redirect(x) {
      this.$router.push('pending_order?item=' + x);
    },
  },
};
</script>

<style>
.selected {
  border: 3px solid #d80e51;
}
</style>
