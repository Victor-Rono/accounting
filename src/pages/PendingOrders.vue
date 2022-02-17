<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-page class="q-pa-md constrained" v-if="this.visible">
    <!-- if admin show company details -->
    <div class="row justify-center q-mt-md">
      <u class="text-primary text-bold text-h6"
        >PENDING ORDERS:&nbsp;
        {{
          this.allOrders?.filter((r) => {
            return r.authorized == 'Yes' && r.closed == 'No';
          }).length
        }}</u
      >
    </div>

    <div class="">
      <div class="row justify-center" v-for="order in this.allOrders">
        <PendingCard
          :start="this.startup()"
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
            <y class="text-pink">{{ this.componentData?.fullName }}</y> ORDER
            #{{ this.componentData?.id }}
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
          BIDS FOR ORDER NUMBER {{ this.componentData?.id }}
        </div>
        <div class="q-mx-md q-my-lg">
          <table class="my-table" style="min-width: 23rem">
            <tr class="my-table">
              <th class="my-table">No.</th>
              <th class="my-table">Contractor Name</th>
              <th class="my-table">Price</th>
              <th class="my-table">Deadline</th>
              <th class="my-table">Approve</th>
            </tr>
            <tr class="my-table" v-for="item in this.bids">
              <td class="my-table">{{ item?.no }}</td>
              <td class="my-table">{{ item?.fullName }}</td>
              <td class="my-table">{{ item?.price }}</td>
              <td class="my-table">{{ item?.deadline }}</td>
              <td class="my-table">
                <q-btn
                  class="bg-positive text-white"
                  dense
                  flat
                  no-caps
                  icon="check"
                  @click="approveBid(item?.id, item?.order_id, item?.fullName)"
                  >Approve</q-btn
                >
              </td>
            </tr>
          </table>
          <p class="text-bold q-my-md text-center">
            DEPARTMENT: {{ this.componentData?.department }} <br />
            <y class="text-primary">{{ this.componentData?.date }}</y>
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
import PendingCard from "./components/PendingCard.vue"

const $q = useQuasar();
export default {
  name: 'Accountant',
  components:{
PendingCard
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
  request:"pendingOrders",
  email: this.$store.state.myAuth.email
},{ timeout: this.$store.state.timeout }).then(res=>{
  this.allOrders = res.data;
})

},

approveBid(bidder_id,order_id, contractor){

  var verify = confirm("Assign this order to " +contractor+ " ?");

  if(verify){
    setTimeout(()=>{this.alert = false;}, 700)
    api.post(this.$store.state.apiLink+'accounting.php',{
      request:"acceptBid",
      id: bidder_id,
      order_id: order_id
    }).then(res=>{
      // console.log(res.data);
      if(res.data == 'Success'){
        alert("Order number "+ order_id + "has been assigned to "+ contractor);
        this.alert = false;
        this.startup();
      }
    })


  }

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
