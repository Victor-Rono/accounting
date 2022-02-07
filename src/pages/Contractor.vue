<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-page class="q-pa-md constrained" v-if="this.visible">
    <!-- if admin show company details -->
    <div class="row justify-center q-mt-md">
      <u class="text-primary text-bold text-h6"
        >AVAILABLE ORDERS:&nbsp;
        {{
          this.allOrders?.filter((r) => {
            return r.authorized == 'Yes' && r.closed == 'No';
          }).length
        }}</u
      >
    </div>

    <div class="">
      <div class="row justify-center" v-for="order in this.allOrders">
        <ContractorCard
          :order="order"
          :start="this.startup()"
          v-if="order.authorized == 'Yes' && order.closed == 'No'"
        />
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
import ContractorCard from "./components/ContractorCard.vue"

const $q = useQuasar();
export default {
  name: 'Accountant',
  components:{
ContractorCard
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
         started:ref(false)

    }
  },

  methods:{

startup(){

if(!this.started){
  api.post(this.$store.state.apiLink+'accounting.php',{
  request:"getMyOrders",
  username: this.$store.state.myAuth.username
},{ timeout: this.$store.state.timeout }).then(res=>{
  console.log(res.data)
  this.allOrders = res.data;
  this.started = true;
})
}

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
