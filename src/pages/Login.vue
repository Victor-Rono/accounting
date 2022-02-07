<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-form
    @keyup.enter="validateUser"
    class="q-pa-md constrained"
    v-show="this.visible"
  >
    <div class="q-mt-md">
      <!-- <div class="text-center q-ma-md">
        Need an account??
        <q-btn
          dense
          class="bg-pink text-white q-ml-md"
          label="sign up"
          to="/signup"
        />
      </div> -->

      <q-card
        bordered
        class="q-pa-lg q-ma-md justify-center extra-constrained1"
      >
        <div>
          <p class="text-primary text-center text-bold">log in:</p>
          <div class="row justify-center q-my-md">
            <q-input
              outlined
              v-model="phone"
              dense
              label="email/ Username"
              type="text"
              class="myselector"
            />
          </div>

          <div class="row justify-center q-my-md">
            <q-input
              type="password"
              outlined
              v-model="password"
              dense
              label="password"
              class="myselector"
            />
          </div>

          <div class="row justify-center q-my-md">
            <q-btn
              v-if="!this.loading"
              color="primary"
              @click="validateUser"
              style="width: 150px"
              class="myselector"
            >
              Log In
            </q-btn>

            <q-spinner-oval v-if="this.loading" color="primary" size="2.5rem" />
          </div>

          <!-- <div class="row justify-center q-my-md">
            <q-btn
              v-if="!this.loading"
              color="pink"
              to="/reset"
              style="width: 150px"
              class="myselector"
            >
              Reset Password
            </q-btn>
          </div> -->
        </div>
      </q-card>
    </div>
  </q-form>

  <q-dialog v-model="alert" position="top">
    <q-card>
      <q-card-section class="row justify-center q-px-xl">
        <div class="text-h6 text-primary">
          <q-icon :name="this.icons" size="lg" :color="this.color" />
        </div>
      </q-card-section>

      <q-card-section class="q-pt-none row justify-center">
        {{ errorMessage }}
      </q-card-section>

      <q-card-actions align="center">
        <q-btn flat label="OK" color="primary" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script lang="js">
import { defineComponent, ref } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useRoute } from 'vue-router';
const $q = useQuasar();
export default {
  name: 'Login',
  components:{

  },

  setup(){

      var phoneError= ref('');

      var errorMessage = ref('');

    return {
      phone:ref(''),
      username:ref(''),
      password:ref(''),
      confirm:ref(''),
      phoneError,
      visited:ref(''),
      errorMessage,
      loading:ref(false),
      icons:ref("warning"),
      color:ref(false)

    }
  }
,

mounted(){
  // this.$store.commit('authenticator', null);
  setTimeout(() => {
    this.visible= true;
  }, this.$store.state.spinner * 3);
},


  data(){
    return {
        alert: ref(false),
        visible:ref(false),
        apiLink:ref( this.$store.state.apiLink)

    }
  },

  methods:{

message(a,b,c){
  this.errorMessage = a;
  this.icons = b;
  this.color = c;
  this.alert = true;
  this.loading = false;

},



    processPhone(){

        if(navigator.onLine){
          if(this.phone != ''){

              this.simulateProgress(3)
             api.post('https://fnfcom.com/api/auth.php', {
    request: "login",
    phone:this.phone,
    password:this.password

    },{ timeout: this.$store.state.timeout })
  .then(response=> {
    var res = response.data
    console.log(res.visited)
    if(res.username){


    } else{

this.errorMessage =  `Phone number or username ${this.phone} does not exist in our records`;
this.icons="warning"
this.alert = true;
// this.message()
    }


  }).catch(err=>{
    if(err.code == 'ECONNABORTED'){
     this.errorMessage =  `the internet connection is too slow. Try again later`;
this.alert = true;
    }
  })
          }
        } else{
          this.errorMessage =  `You might not be connected to the internet`;
this.alert = true;
        }
    },


    alertMe () {

    },








    validateUser(){

      if(this.phone && this.password){
        //navigator.onLine
        if(this.phone){
          this.loading = true;


     api.post((this.apiLink+'auth.php'), {
    phone: this.phone,
    password:this.password,
    request: "login",
    time: Date.now()+(3600*3),

    },{ timeout: this.$store.state.timeout })
  .then(response=> {
    var res = response.data
     this.loading = false;
console.log(res)
    if(res.status){

        var token = res;


this.message(`You have logged in successfully`,'check','positive')


        setTimeout(() => {

          this.$store.commit('authenticator', token);


        }, 1800);


    } else{


this.message('The login details you have given are invali','warning','red')

    }


  }).catch(err=>{
    if(err.code == 'ECONNABORTED'){
    this.message('the internet connection is too slow. Try again later','warning','red')
    }
  })

}
  else{
  this.errorMessage =  `You might not be connected to the internet`;
  this.alert = true;
}
//Login

      } else if(this.visited == 'No'){
//register
 if(navigator.onLine){

if(this.password.length >= 5){
     if(this.validateEmail(this.email) === true){
    if(this.password === this.confirm){
      this.simulateProgress(3)
 api.post('https://fnfcom.com/external/api/register.php', {
    username: this.username,
    password:this.password,
    confirm: this.confirm,
    email:this.email,
    phone:this.phone,
    time: Date.now()+(3600*3),

    },{ timeout: this.$store.state.timeout })
  .then(response=> {
    var res = response.data
    console.log(res)
    if(res.visited){
           var token = {
          firstName: res.firstName,
          username: res.username,
          phone: res.phone,
          email:res.email,
          expiry:Date.now() + (3600 *3)
        }

        this.$store.commit('authenticator', token)
    } else{

this.errorMessage =  `${res}`;
this.alert = true;

    }


  }).catch(err=>{
    if(err.code == 'ECONNABORTED'){
     this.errorMessage =  `the internet connection is too slow. Try again later`;
this.alert = true;
    }
  })
    } else{
 this.errorMessage =  `Passwords do not match`;
  this.alert = true;

    }
   } else{
this.errorMessage =  `Invalid email`;
this.alert = true;
   }

} else{
  this.errorMessage =  `Password too short. Must be at least 5 characters`;
this.alert = true;
}


}
  else{
  this.errorMessage =  `You might not be connected to the internet`;
  this.alert = true;
}
//register
      }



    },

    validateEmail(input) {

  var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

  if (input.match(validRegex)) {


    return true;

  } else {



    return false;

  }

},







  }


}
</script>

<style scoped></style>
