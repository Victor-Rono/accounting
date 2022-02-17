<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-page class="q-pa-md constrained" v-if="this.visible">
    <!-- if admin show company details -->
    <div class="row justify-center q-mt-md">
      <u class="text-primary text-bold text-h6"
        >MY CONTRACTS:&nbsp;
        {{
          this.allOrders?.filter((r) => {
            return r.authorized == 'Yes' && r.closed == 'No';
          }).length
        }}</u
      >
    </div>

    <div class="">
      <div class="row justify-center" v-for="order in this.allOrders">
        <MyContractCard
          :orderList="order"
          :start="this.startup(x)"
          :started="this.started"
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
import MyContractCard from "./components/MyContractCard"

const $q = useQuasar();
export default {
  name: 'Accountant',
  components:{
MyContractCard
  },

  setup(){


    return {
        myInfo:ref({}),
        visible:ref(false),
         started:ref(true)
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


    }
  },

  methods:{

startup(x){
if(x || (x === undefined && !this.started)){
api.post(this.$store.state.apiLink+'contractor.php',{
  request:"myContracts",
  contractor: this.$store.state.myAuth.username
}).then(res=>{
  console.log("started")
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
if(this.$route.fullPath == '/success'){
  this.$router.push('/my_contracts');
} else{

}
this.startup(this.started);
      setTimeout(() => {
    this.visible= true;
    this.started = false;
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
