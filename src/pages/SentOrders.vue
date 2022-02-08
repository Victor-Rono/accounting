<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-page class="q-pa-md constrained" v-if="this.visible">
    <!-- if admin show company details -->
    <div class="row justify-center q-mt-md">
      <u class="text-primary text-bold text-h6"
        >OPEN ORDERS:&nbsp;
        {{
          this.allOrders?.filter((r) => {
            return r.authorized == 'Yes' && r.closed == 'No';
          }).length
        }}</u
      >
    </div>

    <div class="">
      <div class="row justify-center" v-for="order in this.allOrders">
        <OpenCard
          :order="order"
          :loadData="loadData"
          v-if="order.authorized == 'Yes' && order.closed == 'No'"
        />
      </div>
    </div>

    <q-dialog v-model="alert">
      <q-card>
        <div class="q-ma-md">
          <p class="text-bold text-center" style="text-transform: uppercase">
            <y class="text-pink">{{ this.componentData.fullName }}</y> ORDER #{{
              this.order.id
            }}
            &nbsp;
            <q-btn
              flat
              size="sm"
              class="text-primary"
              icon="fas fa-gavel"
              no-caps
              ><q-badge class="bg-red text-white" floating rounded>{{
                this.bids.length
              }}</q-badge></q-btn
            >
          </p>
        </div>
        <div class="text-center text-h6">
          BIDS FOR ORDER NUMBER {{ this.component.id }}
        </div>
        <div class="q-ma-md">
          <table class="my-table" style="min-width: 23rem">
            <tr class="my-table">
              <th class="my-table">No.</th>
              <th class="my-table">Contractor Name</th>
              <th class="my-table">Price</th>
              <th class="my-table">Deadline</th>
              <th class="my-table">Approve</th>
            </tr>
            <tr class="my-table" v-for="item in this.bids">
              <td class="my-table">{{ item.id }}</td>
              <td class="my-table">{{ item.fullName }}</td>
              <td class="my-table">{{ item.price }}</td>
              <td class="my-table">{{ item.deadline }}</td>
              <td class="my-table">
                <q-btn
                  class="bg-positive text-white"
                  dense
                  no-caps
                  icon="check"
                  @click="approveBid(item.id, item.contractor)"
                  >Approve</q-btn
                >
              </td>
            </tr>
          </table>
          <p class="text-bold q-my-sm text-center">
            DEPARTMENT: {{ this.order.department }} <br />
            <y class="text-primary">{{ this.order.date }}</y>
          </p>
        </div>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script lang="js">
import { defineComponent, ref } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useRoute } from 'vue-router';
import VueNumberFormat from 'vue-number-format';
import OpenCard from "./components/OpenCard.vue"

const $q = useQuasar();
export default {
  name: 'Accountant',
  components:{
OpenCard
  },

  setup(){


    return {
        myInfo:ref({}),
        visible:ref(false),
        // showOrderFor:ref(false),
    }
  }
,
  data(){
    return {
         department:ref(null),
         options:this.$store.state.options,
         apiLink:this.$store.state.apiLink,
         allOrders:ref(null),
         componentData:ref(null),
         bids:ref(null),
         alert:ref(false)

    }
  },

  methods:{

startup(){

api.post(this.$store.state.apiLink+'accounting.php',{
  request:"getOrders",
  email: this.$store.state.myAuth.email
},{ timeout: this.$store.state.timeout }).then(res=>{
  this.allOrders = res.data;
})

},

approveBid(x,y){
console.log(x, y)
},

loadData(x, y){
  this.bids = x;
  this.componentData = y;
  console.log(x);
  console.log(y);
  this.alert = true;
},


     numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
},


  },


  mounted(){
    // this.fetchInfo();
this.startup();
      setTimeout(() => {
    this.visible= true;
  }, this.$store.state.spinner * 3);
  }


}
</script>

<style lang="css" scoped>
.mycard {
  min-width: 400px;
}

.box {
  border-right: 2px solid pink;
  padding: 1rem;
}
</style>
