<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-page class="q-pa-md constrained" v-if="this.visible">
    <!-- if admin show company details -->
    <div class="row justify-center q-mt-md">
      <u class="text-primary text-bold text-h6"
        >UNASSIGNED ORDERS:&nbsp;
        {{
          this.allOrders?.filter((r) => {
            return r.contractor == null;
          }).length
        }}
      </u>
    </div>

    <div class="">
      <div class="row justify-center" v-for="order in this.allOrders">
        <AccountantCard
          :order="order"
          :start="this.startup()"
          v-if="!order.contractor"
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
import AccountantCard from "./components/AccountantCard.vue"

const $q = useQuasar();
export default {
  name: 'Accountant',
  components:{
AccountantCard
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
         fitered:ref(0)

    }
  },

  methods:{

startup(){

api.post(this.$store.state.apiLink+'accounting.php',{
  request:"getOrders",
  email: this.$store.state.myAuth.email
},{ timeout: this.$store.state.timeout }).then(res=>{
  this.allOrders = res.data.filter(r=>{return r.authorized =='Pending'});

  var x = res.data;

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
