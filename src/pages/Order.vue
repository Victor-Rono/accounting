<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-page class="q-pa-md constrained" v-if="this.visible">
    <!-- if admin show company details -->
    <div class="row justify-center q-mt-md">
      <u class="text-primary text-bold text-h6">MY ORDERS</u>
    </div>

    <div class="row justify-center q-my-md">
      <q-btn
        icon="fas fa-plus"
        class="bg-primary text-white q-mx-lg"
        @click="addOrder()"
        >&nbsp;ADD</q-btn
      >
    </div>

    <div class="">
      <div class="row justify-center" v-for="order in this.myOrders">
        <OrderCard :order="order" />
        <q-separator class="q-my-md" />
      </div>
    </div>
  </q-page>
</template>

<script lang="js">
import { defineComponent, ref } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useRoute } from 'vue-router';
import VueNumberFormat from 'vue-number-format';
import OrderCard from "./components/OrderCard.vue"

const $q = useQuasar();
export default {
  name: 'Home',
  components:{
OrderCard
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
         myOrders:ref([])

    }
  },

  methods:{

    startup(){
      //get all orders
      api.post(this.$store.state.apiLink+'orders.php',{
        request:'getMyOrders',
        email: this.$store.state.myAuth.email
      }).then(res=>{
        console.log(res.data)
        this.myOrders = res.data;
      },{ timeout: this.$store.state.timeout })
    },

     numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
},

addOrder(){

  api.post(this.apiLink+'orders.php',{
    request:"addOrder",
    email:this.$store.state.myAuth.email,
    department:this.$store.state.myAuth.department,
    occupation:this.$store.state.myAuth.occupation,
    fullName:this.$store.state.myAuth.firstName+' '+this.$store.state.myAuth.lastName,
    //
  },{ timeout: this.$store.state.timeout }).then(res=>{
    // console.log(res.data)
    if(res.data.Success == true){
      // this.$store.commit("selector", res.data.id)
      this.$router.push('/order2?item='+res.data.id)
    } else{

    }
  })

}


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
