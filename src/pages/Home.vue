<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-page class="q-pa-md constrained" v-if="this.visible">
    <Panel />

    <q-slide-transition duration="400">
      <div class="q-pa-md constrained">
        <div class="q-mt-md">
          <q-card
            bordered
            class="q-pa-lg q-ma-md justify-center extra-constrained1"
          >
            <div>
              <p class="text-primary text-center text-bold">MY PROFILE</p>
              <div class="row justify-center q-my-md uppercase text-bold">
                FULL NAMES:&nbsp;
                <y class="text-pink">{{
                  ` ${this.$store.state.myAuth?.firstName} ${this.$store.state.myAuth?.lastName}`
                }}</y>
              </div>

              <div class="row justify-center q-my-md text-bold">
                @{{ this.$store.state.myAuth?.username }}
              </div>

              <div class="row justify-center text-bold q-my-md uppercase">
                PHONE NUMBER:&nbsp;
                <y class="text-pink">{{ this.$store.state.myAuth?.phone }}</y>
              </div>

              <div class="row justify-center text-bold q-my-md">
                EMAIL:&nbsp;
                <y class="text-pink">{{ this.$store.state.myAuth?.email }}</y>
              </div>

              <div class="row justify-center text-bold q-my-md uppercase">
                DEPARTMENT:&nbsp;
                <y class="text-pink">{{
                  this.$store.state.myAuth?.department
                }}</y>
              </div>

              <div class="row justify-center text-bold q-my-md uppercase">
                OCCUPATION:&nbsp;
                <y class="text-pink">{{
                  this.$store.state.myAuth?.occupation
                }}</y>
              </div>
            </div>
          </q-card>
        </div>
      </div>
    </q-slide-transition>
    <div class="row justify-center"></div>
  </q-page>
</template>

<script lang="js">
import { defineComponent, ref } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useRoute } from 'vue-router';
import VueNumberFormat from 'vue-number-format';
import Panel from './components/Panel';

const $q = useQuasar();
export default {
  name: 'Home',
  components:{
    Panel,
  },

  setup(){


    return {
        myInfo:ref({}),
        visible:ref(false),
        showOrderForm:ref(false),

    }
  }
,
  data(){
    return {
         department:ref(null),
         options:this.$store.state.options,

    }
  },

  methods:{

     numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      },

      fetchInfo(){
        console.log("info")
      }


  },


  mounted(){

if(!this.$store.state.myAuth){
  this.$store.commit("authenticator", null);
} else{

   this.fetchInfo();

      setTimeout(() => {
    this.visible= true;
  }, this.$store.state.spinner * 3);

}



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
