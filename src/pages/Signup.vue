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
      <q-card
        bordered
        class="q-pa-lg q-ma-md justify-center extra-constrained1"
      >
        <div>
          <p class="text-primary text-center text-bold">
            Fill in the details below to register:
          </p>
          <div class="row justify-center q-my-md">
            <q-input
              outlined
              v-model="firstName"
              dense
              label="First Name"
              type="text"
              class="myselector"
            />
          </div>

          <div class="row justify-center q-my-md">
            <q-input
              outlined
              v-model="lastName"
              dense
              label="Last Name"
              type="text"
              class="myselector"
            />
          </div>

          <div class="row justify-center q-my-md">
            <q-input
              outlined
              v-model="username"
              dense
              label="Username"
              type="text"
              class="myselector"
              maxlength="10"
            />
          </div>

          <div class="row justify-center q-my-md">
            <q-input
              outlined
              v-model="email"
              dense
              label="email"
              type="email"
              class="myselector"
            />
          </div>

          <div class="row justify-center q-my-md">
            <q-input
              outlined
              v-model="phone"
              dense
              label="Phone Number"
              type="tel"
              class="myselector"
              maxlength="10"
            />
          </div>
          <p class="text-center text-bold">GENDER</p>
          <div class="row justify-center q-my-md">
            <div class="q-gutter-sm">
              <q-radio v-model="gender" val="Male" label="Male" />
              <q-radio v-model="gender" val="Female" label="Female" />
            </div>
          </div>
        </div>

        <div class="">
          <div class="q-my-md row justify-center" @click="this.reset">
            <q-select
              outlined
              transition-show="flip-up"
              transition-hide="flip-down"
              bg-color=""
              label-color=""
              color="primary"
              v-model="department"
              :options="options"
              label="Choose a Department"
              options-selected-class="text-primary"
              class="myselector"
              dense
              style="width: auto"
              :rules="[(val) => val || 'Cannot be empty']"
            >
              <template v-slot:option="scope" @click="this.reset">
                <q-item v-bind="scope.itemProps">
                  <q-item-section avatar>
                    <q-icon :name="scope.opt.icon" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>{{ scope.opt.label }}</q-item-label>
                    <q-item-label caption>{{
                      scope.opt.description
                    }}</q-item-label>
                  </q-item-section>
                </q-item>
              </template>
            </q-select>
          </div>

          <div class="row justify-center q-my-md">
            <q-input
              outlined
              v-model="occupation"
              dense
              label="Occupation e.g teacher, accountant etc.."
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
            <q-input
              type="password"
              outlined
              v-model="confirmPassword"
              dense
              label="Confirm password"
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
              Register
            </q-btn>

            <q-spinner-oval v-if="this.loading" color="primary" size="2.5rem" />
          </div>

          <div class="row justify-center q-my-md">
            <q-btn
              v-if="!this.loading"
              color="pink"
              to="/reset"
              style="width: 150px"
              class="myselector"
            >
              Reset Password
            </q-btn>
          </div>
        </div>
      </q-card>
    </div>
  </q-form>

  <q-dialog v-model="alert">
    <q-card>
      <q-card-section class="row justify-center q-px-xl">
        <div class="text-h6">
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
  name: 'Signup',
  components:{

  },

  setup(){

      var phoneError= ref('');
      // var alert= ref(false);
      var errorMessage = ref('');

    return {
      firstName:ref(null),
      lastName:ref(null),
      username:ref(null),
      email:ref(null),
      phone:ref(null),
      password:ref(null),
      confirmPassword:ref(null),
      county:ref(null),
      subCounty:ref(null),
      ward:ref(""),
      paypal:ref(""),
      phoneError,
      visited:ref(''),
      errorMessage,
      loading:ref(false),
      icons:ref(null),
      color:ref(null),
      department:ref(null),
      occupation:ref(null),
      gender:ref(null)

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
         apiLink:ref( this.$store.state.apiLink),
         options:ref(this.$store.state.options)

    }
  },

  methods:{




    message (a,b,c) {

      this.errorMessage = a;
      this.icons = b;
      this.color = c;
      this.loading = false;
      this.alert = true;

    },








    validateUser(){
if(this.$store.state.myAuth.department =='Admin'){
  if(this.firstName && this.lastName && this.username && this.email && this.phone && this.password
       && this.confirmPassword  ){
        //navigator.onLine
        if(3 > 2){
          this.loading = true;

  if(this.password === this.confirmPassword){
if(this.validateEmail(this.email)){
 if(this.phone.substring(0,2) == '07' || this.phone.substring(0,2) == '01' && !isNaN(this.phone)){
    api.post((this.apiLink+'auth.php'), {
    firstName:this.firstName,
    lastName:this.lastName,
    username:this.username,
    email:this.email,
    phone: this.phone,
    password:this.password,
    occupation:this.occupation,
    department:this.department.value,
    gender:this.gender,
    // ward:this.ward,
    // paypal:this.paypal,
    request: "signup",

    // referral: JSON.parse(localStorage.getItem('referral')),

    },{ timeout: this.$store.state.timeout })
  .then(response=> {
    var res = response.data
     this.loading = false;
      console.log(res)
    if(res.response == "Success"){

        var token = res;

        this.message( this.firstName +' '+this.lastName + "registered successfully", 'check', 'positive')


        setTimeout(() => {
          this.$store.dispatch("checkOnline");
          // this.$store.commit('authenticator', token);

        }, 1800);


    } else{

this.message(res, 'warning', 'red')

    }


  }).catch(err=>{
    this.message( `the internet connection is too slow. Try again later`, 'warning', 'red')
    }
  )
 } else{
   this.message( `The phone number shold start with 07 or 01 and must be numbers only`, 'warning', 'red')
 }
} else{
  this.message( `Your email is badly formatted`, 'warning', 'red')
}
  } else{
   this.message( `Passwords do not match`, 'warning', 'red')
  }




}
  else{
  // this.errorMessage =  ``;
  this.message( `You are not connected to the internet`, 'warning', 'red')
//Login

      }


       }
} else{
  this.message( `You don't have authorization to register a new user`, 'warning', 'red')
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
