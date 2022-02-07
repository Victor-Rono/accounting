import { store } from 'quasar/wrappers'
import { InjectionKey } from 'vue'
import {
  createStore,
  Store as VuexStore,
  useStore as vuexUseStore,
} from 'vuex'
import { api } from 'boot/axios'

export default createStore({

  state:{
    myAuth:JSON.parse(localStorage.getItem('authAccounting')),
    apiLink:"https://fnfcom.com/accounting/api/",
    spinner: 100,
    timeout: parseInt(10000),
    selected:null,
    notifications:[],


    options: [
      {
      label: 'Admin',
      value: 'Admin',
      description: 'Has more access and permissions than other users',
      icon: 'fas fa-address-book',
     },

     {
      label: 'Procurement',
      value: 'Procurement',
      description: 'Ensures that items needed are bought and delivered',
      icon: 'fas fa-truck',
     },

      {
      label: 'Finance',
      value: 'Finance',
      description: 'Verifies and creates budget for items needed etc..',
      icon: 'fas fa-university',
     },

       {
      label: 'Staff',
      value: 'Staff',
      description: 'Other staff members e.g teachers, cooks etc',
      icon: 'fas fa-user',
     },


    ],



  },

  mutations:{

    logout(state){
      state.numberz =  x;
     console.log(x)
    },


    notifications(state, x){
      state.notifications = x;
    },

    selector(state, x){
      state.selected =  x;
    //  console.log(x)
    },

    authenticator(state, response){
      state.myAuth = response;
      localStorage.setItem('authAccounting', JSON.stringify(response));
      if(state.myAuth === null){
        window.location.assign("/login")
        state.notifications = []
      } else{
        window.location.assign("/")
      }

    }


  },

  actions:{
      selector(x){
        api.post(this.state.apiLink+'orders.php',{
          request:"selector",
          id:x,
          time:Date.now()
        }).then(res=>{
          if(res.data =='Success'){
            this.commit("selector", x)
          }
        })
      },

      getNotifications(){
        if(this.state.myAuth){
          api.post(this.state.apiLink+'general.php',{
            request: 'getNotifications',
            username: this.state.myAuth.username
          },{timeout: this.state.timeout}).then(res=>{
            // console.log(res.data)
            this.commit('notifications', res.data)
          })
        }
      },


  },

  modules:{

  },

  getters:{

  }



})
