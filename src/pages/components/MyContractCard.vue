<template>
  <q-card class="q-pa-md extra-constrained">
    <div class="q-pa-md text-center">
      <div class="text-bold text-h6">
        <tag class="text-pink">{{ this.orderList?.fullName }}</tag>
        ORDER ID
        <tag class="text-pink">#{{ this.orderList?.id }}</tag>
      </div>

      <div class="text-bold text-h6">
        EMAIL:
        <tag class="text-pink">{{ this.orderList?.email }}</tag>
      </div>
    </div>

    <div class="row justify-center">
      <q-form>
        <table class="my-table tbl">
          <tr class="my-table">
            <td class="my-table">No.</td>
            <td class="my-table">Item Name</td>
            <td class="my-table">Quantity</td>
          </tr>
          <tr
            v-for="item in this.orderList?.items"
            class="my-table"
            @dblclick=""
          >
            <td class="my-table">{{ item.no }}</td>
            <td class="my-table">{{ item.itemName }}</td>
            <td class="my-table">{{ item.quantity }}</td>
          </tr>
          <tr v-if="this.sent == 'No'">
            <td class="my-table"></td>
            <td class="my-tablex">
              <q-input
                class="q-mx-md q-my-sm"
                dense
                v-model="this.itemName"
                placeholder="add item"
                type="text"
                outlined
              />
            </td>
            <td class="my-tablex">
              <q-input
                class="q-mx-md q-my-sm"
                dense
                v-model="this.quantity"
                placeholder="quantity"
                type="number"
                outlined
              />
            </td>
            <td class="my-tablex">
              <q-btn
                icon="fas fa-plus"
                @click="makeOrder"
                class="bg-positive text-white q-mx-md q-my-sm"
                v-if="this.quantity && this.itemName"
              />
              <q-btn
                icon="fas fa-plus"
                disable
                class="bg-grey text-white q-mx-md q-my-sm"
                v-else
              />
            </td>
          </tr>
        </table>
      </q-form>
    </div>

    <!-- <div
      class="row justify-center q-pa-md"
      v-if="this.orderList.accepted == 'Pending'"
    >
      <q-btn
        @click="respond('accept')"
        label="Accept"
        icon="fas fa-check"
        flat
        dense
        class="bg-positive text-white q-mx-lg"
      />

      <q-btn
        @click="respond('reject')"
        label="Reject"
        icon="close"
        flat
        dense
        class="bg-red text-white q-mx-lg"
      />
    </div> -->

    <div class="row justify-center q-pa-md">
      <q-btn
        class="bg-primary text-white"
        v-if="this.orderList.completed == 'No'"
        @click="completeOrder"
        icon="check"
        label="Mark as Completed"
      />

      <q-banner
        dense
        class="bg-positive text-white"
        v-if="this.orderList.completed == 'Yes'"
      >
        You have completed this order&nbsp;
        <q-icon class="text-white" name="check" size="sm"></q-icon>
      </q-banner>

      <q-banner
        dense
        class="bg-red text-white"
        v-if="this.orderList.accepted == 'No'"
      >
        You rejected this order
      </q-banner>
    </div>
  </q-card>
</template>

<script>
import { api } from 'boot/axios';
export default {
  name: 'MyContractCard',
  props: ['orderList', 'start', 'started'],

  setup() {},

  methods: {
    redirect(x) {
      // this.$$router.push('/pending_order')
    },

    completeOrder() {
      var x = confirm('Are you sure you want to mark this order as completed?');

      if (x) {
        // this.started = null;
        api
          .post(
            this.$store.state.apiLink + 'contractor.php',
            {
              request: 'completeOrder',
              id: this.orderList.id,
              username: this.$store.state.myAuth.username,
              email: this.$store.state.myAuth.email,
              sender: this.orderList.sender,
            },
            { timeout: this.$store.state.timeout }
          )
          .then((res) => {
            console.log(res.data);
            if (res.data == 'Success') {
              alert('Order Completed');
              window.location.reload();
              // this.$router.push('/success');
              this.start;
            }
          });
      }
    },
  },
};
</script>

<style></style>
