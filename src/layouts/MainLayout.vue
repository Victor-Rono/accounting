<template>
  <!--
  Give credit to the creator of this icon
   <a href="https://www.freeiconspng.com/img/25617">Earth In Png</a> -->
  <q-layout view="hHh LpR fFf" class="">
    <q-header
      bordered
      :class="[lightMode ? 'bg-white text-primary' : 'bg-dark text-primary ']"
    >
      <q-toolbar class="constrained">
        <q-btn
          class="q-mt-sm"
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <!-- <q-btn
          flat
          dense
          round
          icon="eva-camera-outline"
          aria-label="Menu"
          class="large-screen-only q-mt-sm q-mr-xs"
          size="md"
          to="/camera"
        /> -->

        <q-toolbar-title class="title q-mr-md" v-if="!this.search">
          <!-- <y class="small-screen-only text-caption">
            <q-avatar size="xl">
              <img src="../assets/earth2.jpg" />
            </q-avatar>
            {{ appName }}</y
          > -->

          <y class="" @click="this.$router.push('/')" style="cursor: pointer">
            <!-- <q-avatar size="xl">
              <img src="../assets/earth2.jpg" />
            </q-avatar> -->

            {{ appName }}</y
          >

          <!-- "icons/favicon-96x96.png" -->
          <!-- <q-icon name="public" class="q-mb-md" size="lg" /> -->
        </q-toolbar-title>
        <q-toolbar-title
          class="title q-mr-md large-screen-only"
          v-if="this.search"
        >
          <!-- <q-avatar size="xl"> <img src="../assets/earth2.jpg" /> </q-avatar> -->
          <!-- <q-icon name="public" class="q-mb-md" size="lg" /> -->
          {{ appName }}
        </q-toolbar-title>

        <div class="row justify-around" v-if="this.search">
          <q-btn
            v-if="this.search"
            class="q-mr-none text-red-14"
            flat
            dense
            round
            icon="fas fa-arrow-left"
            @click="toggleSearch"
          />
          <!-- <q-icon name="public" class="q-mb-md" size="lg" /> -->
          <q-toolbar-title class="title">
            <q-input
              v-if="this.search"
              outlined
              v-model="text"
              placeholder="Search"
              class=""
              dense
              autofocus
            />
          </q-toolbar-title>

          <!-- <q-btn
            flat
            dense
            round
            icon="search"
            aria-label="Menu"
            class="q-mr-md"
            @click="searchItem"
            v-if="this.search"
          /> -->
        </div>
        <!-- <q-input type="text" placeholder="Search" bordered /> -->

        <!-- <q-toggle
          v-if="!this.search"
          v-model="toggleValue"
          checked-icon="eva-moon-outline"
          color="primary"
          unchecked-icon="eva-sun-outline"
          @click="modeToggle"
          size="md"
          class="q-mr-md"
        /> -->

        <!-- <q-btn
          flat
          dense
          round
          icon="search"
          aria-label="Menu"
          class="q-mr-sm"
          @click="toggleSearch"
          v-if="!this.search"
          @click="toggleRightDrawer"
        /> -->
        <!-- <p v-if="!connected" class="text-red-14 q-ma-md">No internet</p> -->

        <q-btn
          label="log out"
          aria-label="Menu"
          v-if="this.$store.state.myAuth?.username"
          class="q-mr-sm"
          @click="this.$store.commit('authenticator', null)"
        />

        <q-btn
          class="q-ml-lg"
          icon="eva-moon"
          @click="modeToggle"
          round
          size="md"
          v-if="this.lightMode"
          flat
          dense
        />
        <q-btn
          class="q-ml-lg"
          icon="eva-sun-outline"
          @click="modeToggle"
          color="white"
          round
          size="md"
          v-if="!this.lightMode"
          flat
          dense
        />

        <q-btn
          class="q-ml-lg"
          icon="fas fa-bell"
          to="/notifications"
          round
          size="md"
          flat
          dense
          v-if="this.$store.state.myAuth"
          ><q-badge
            color="red"
            v-if="this.$store.state.notifications.length > 0"
            rounded
            floating
            >{{ this.$store.state.notifications.length }}</q-badge
          ></q-btn
        >
      </q-toolbar>

      <q-tabs
        dense
        align="center"
        class="text-body1 large-screen-only constrained"
      >
        <!-- <y v-for="tab in this.myTabs">
          <q-route-tab :to="tab.to" :label="tab.label" :icon="tab.icon" />
        </y> -->
      </q-tabs>
    </q-header>

    <!-- <q-footer
      bordered
      class="small-screen-only"
      :class="[lightMode ? 'bg-white text-primary ' : 'bg-dark text-primary ']"
    >
      <q-tabs
        align="center"
        active-color="primary"
        indicator-color="transparent"
      >
        <q-route-tab to="/"
          ><q-icon name="eva-home-outline" size="md"
        /></q-route-tab>
        <q-route-tab to="/camera">
          <q-icon name="eva-camera-outline" size="md" />
        </q-route-tab>
      </q-tabs>
    </q-footer> -->

    <!-- <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item-label header> </q-item-label>
      </q-list>
    </q-drawer> -->
    <q-drawer
      show-if-above
      v-model="leftDrawerOpen"
      side="left"
      behavior="normal"
      width="230"
      bordered
    >
      <!-- drawer content -->
      <q-list class="q-mt-xl" v-if="this.$store.state.myAuth">
        <q-item
          clickable
          v-ripple
          to="/register"
          class=""
          v-if="this.$store.state.myAuth?.department == 'Admin'"
        >
          <q-item-section avatar>
            <q-icon color="" name="fas fa-user" size="sm" />
          </q-item-section>

          <q-item-section>Register User</q-item-section>
        </q-item>

        <q-item
          clickable
          v-ripple
          to="/order"
          class=""
          v-if="this.$store.state.myAuth?.occupation != 'Contractor'"
        >
          <q-item-section avatar>
            <q-icon color="" name="fas fa-plus" size="sm" />
          </q-item-section>

          <q-item-section>Add Order</q-item-section>
        </q-item>

        <q-item
          clickable
          v-ripple
          to="/contractor"
          class=""
          v-if="this.$store.state.myAuth?.occupation == 'Contractor'"
        >
          <q-item-section avatar>
            <q-icon color="" name="fas fa-paper-plane" size="sm" />
          </q-item-section>

          <q-item-section>Available Orders</q-item-section>
        </q-item>

        <q-item
          clickable
          v-ripple
          to="/my_contracts"
          class=""
          v-if="this.$store.state.myAuth?.occupation == 'Contractor'"
        >
          <q-item-section avatar>
            <q-icon color="" name="fas fa-check" size="sm" />
          </q-item-section>

          <q-item-section>My Orders</q-item-section>
        </q-item>

        <!-- <y v-for="tab in this.myTabs">
          <q-item clickable v-ripple :to="tab.to" class="">
            <q-item-section avatar>
              <q-icon color="" :name="tab.icon" size="sm" />
            </q-item-section>

            <q-item-section>{{ tab.label }}</q-item-section>
          </q-item>
        </y> -->
      </q-list>
    </q-drawer>

    <q-drawer
      show-if-above
      v-model="rightDrawerOpen"
      side="right"
      behavior="normal"
      class=""
      bordered
    >
      <!-- drawer content -->
      <RightSideBar />
    </q-drawer>

    <q-page-container class="">
      <!-- <q-pull-to-refresh @refresh="this.confirm = true"> </q-pull-to-refresh> -->
      <keep-alive>
        <router-view />
      </keep-alive>
    </q-page-container>
  </q-layout>

  <q-dialog v-model="confirm" persistent>
    <q-card>
      <q-card-section class="row items-center">
        <q-avatar icon="fas fa-refresh" color="primary" text-color="white" />
        <span class="q-ml-sm">{{ noInternet }}</span>
      </q-card-section>

      <q-card-actions align="center">
        <q-btn flat label="No" color="negative" v-close-popup />
        <q-btn flat label="Yes" color="primary" @click="reload" v-close-popup />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script lang="js">
// import { useQuasar } from 'quasar'

import { defineComponent, ref } from 'vue'
import { useQuasar } from 'quasar'
import RightSideBar from '../components/RightSideBar.vue'
import { api } from 'boot/axios';
// $ = useQuasar();

export default defineComponent({
  name: 'MainLayout',

  components: {
RightSideBar
  },

  setup () {



var leftDrawerOpen = ref(true);
var rightDrawerOpen = ref(true);
const $q = useQuasar();


const search = ref(false);
// console.log('LOCALSTORAGE: '+JSON.parse(localStorage.getItem('lightMode')))
$q.dark.set(!(JSON.parse(localStorage.getItem('lightMode'))))
    return {
      appName:ref("Accounting"),

      confirm:ref(false),
      noInternet:ref("You have no internet connection"),
      // myWidth: ref(window.innerWidth),
    connected: ref(navigator.onLine),
    search,
      toggleSearch () {
        search.value = !search.value
      },
      lightMode:ref(JSON.parse(localStorage.getItem('lightMode'))),
      leftDrawerOpen,
      toggleValue: ref(!JSON.parse(localStorage.getItem('lightMode'))),
     rightDrawerOpen,
     toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      },
      toggleRightDrawer () {
        rightDrawerOpen.value = !rightDrawerOpen.value
      },





    }
    // toggleDark()
  },

data(){
  var authenticated = 'x';
  return{
authenticated,
apiLink:ref(this.$store.state.apiLink),


 myTabs: [
        // {
        //   to: '/about',
        //   label: 'About us',
        //   icon: 'fas fa-question',
        //   className: '',
        // },
        // {
        //   to: '/reg',
        //   label: 'Register User',
        //   icon: 'fas fa-user',
        //   className: '',
        // },

        // {
        //   to: '/order',
        //   label: 'make an order',
        //   icon: 'fas fa-edit',
        //   className: '',
        // },
        {
          to: '/myorders',
          label: `my orders`,
          icon: 'fas fa-gift',
          className: '',
        },

        {
          to: '/buildings',
          label: 'buildings',
          icon: 'fas fa-building',
          className: '',
        },
        {
          to: '/services',
          label: 'services',
          icon: 'fas fa-ribbon',
          className: '',
        },

        {
          to: '/businesses',
          label: 'businesses',
          icon: 'eva-gift-outline',
          className: '',
        },

        {
          to: '/privacy',
          label: 'Privacy',
          icon: 'fas fa-lock',
          className: '',
        },

      ],
  }
},
  methods:{

reload(){
  window.location.reload()
},

    toggleDark(){
      this.$q.dark.toggle();
      this.lightMode = !this.lightMode;
      this.toggleValue = !this.toggleValue
      //start


// Put the object into storage
localStorage.setItem('lightMode', JSON.stringify(this.lightMode));

// Retrieve the object from storage

    },

    modeToggle(){
       this.$q.dark.toggle();
      this.lightMode = !this.lightMode;
      localStorage.setItem('lightMode', JSON.stringify(this.lightMode));
    },


    startup(){



        api.post((this.apiLink+"auth.php"),{
          request:"auth"
        },{timeout: this.$store.state.timeout}).then(response=>{
          var res = response.data;
          // console.log(res);
        })
    },

    getNotifications(){
     this.$store.dispatch("getNotifications");
    }
  },

  mounted(){

    this.getNotifications();

    setInterval(()=>{
      this.getNotifications();
    }, 10000)

    this.startup();

  var expiry = this.$store.state.myAuth?.expiry;

  if(Date.now > expiry){
    this.$store.commit('authenticator', null);
  }





    if(JSON.parse(localStorage.getItem('notFirstTime')) === null){
      localStorage.setItem('lightMode', JSON.stringify(false));
      localStorage.setItem('notFirstTime', JSON.stringify(false));
      this.toggleDark()
// console.log("TOGGLED")

    }

    if(window.innerWidth <= 1025 ){
      this.toggleRightDrawer();
      this.toggleLeftDrawer();
    }

  },

  watch:{
    // authenticated(newVal, oldVal){
    //   if(newVal == null || newVal == undefined){
    //     window.location.assign("/#/login")
    //   }
    // }



    }

})
</script>

<style scoped>
.title {
}

@media (max-width: 599px) {
  .title {
    /* text-align: center; */
  }
}
</style>
