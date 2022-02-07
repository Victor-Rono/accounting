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
          this.allOrders.filter((r) => {
            return r.authorized == 'Yes' && r.closed == 'No';
          }).length
        }}</u
      >
    </div>

    <div class="">
      <div class="row justify-center" v-for="order in this.allOrders">
        <OpenCard
          :order="order"
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
         allOrders:ref(null)

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
