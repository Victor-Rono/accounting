<template>
  <div class="flex flex-center q-pt-xl" v-if="!visible">
    <q-spinner-oval size="10rem" class="q-mt-xl" color="primary" />
  </div>

  <q-page class="q-pa-md constrained" v-if="this.visible">
    <!-- <div class="row justify-center q-ma-md">
      <q-table
        :rows="this.orderList"
        :columns="this.orderColumns"
        class="text-pink"
      />
    </div> -->

    <!-- if admin show company details -->

    <!-- <q-form @keyup.enter="makeOrder" class="q-pa-md constrained">
      <div class="q-mt-md">
        <q-card
          bordered
          class="q-pa-lg q-ma-md justify-center extra-constrained1"
        >
          <div>
            <p class="text-primary text-center text-bold">Make an Order:</p>
            <div class="row justify-center q-my-md">
              <q-input
                outlined
                v-model="itemName"
                dense
                label="Name of Item"
                type="text"
                class="myselector"
              />
            </div>

            <div class="row justify-center q-my-md">
              <q-input
                outlined
                v-model="quantity"
                dense
                label="Quantity"
                type="number"
                class="myselector"
              />
            </div>

            <div class="row justify-center q-my-md">
              <q-input
                outlined
                v-model="unitPrice"
                dense
                label="Price Per item"
                type="number"
                class="myselector"
              />
            </div>

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
              <q-btn
                v-if="!this.loading"
                color="primary"
                @click="makeOrder"
                style="width: 150px"
                class="myselector"
              >
                Add to List: {{ this.orderList.length }}
              </q-btn>

              <q-spinner-oval
                v-if="this.loading"
                color="primary"
                size="2.5rem"
              />
            </div>
          </div>
        </q-card>
      </div>
    </q-form> -->

    <div class="q-pa-md text-center">
      <div class="text-bold text-h6">
        <tag class="text-pink">{{
          this.$store.state.myAuth?.firstName +
          ' ' +
          this.$store.state.myAuth?.lastName
        }}</tag>
        ORDER ID
        <tag class="text-pink">#{{ this.$store.state.selected }}</tag>
      </div>

      <div class="text-bold text-h6">
        PHONE NUMBER:
        <tag class="text-pink">{{ this.$store.state.myAuth?.phone }}</tag
        ><br />
        EMAIL:
        <tag class="text-pink">{{ this.$store.state.myAuth?.email }}</tag>
      </div>
    </div>

    <div class="row justify-center">
      <q-form @keyup.enter="makeOrder()">
        <table class="my-table tbl">
          <tr class="my-table">
            <td class="my-table">No.</td>
            <td class="my-table">Item Name</td>
            <td class="my-table">Quantity</td>
            <td class="my-table">Add/Delete</td>
          </tr>
          <tr v-for="item in this.orderList" class="my-table" @dblclick="">
            <td class="my-table">{{ item.no }}</td>
            <td class="my-table">{{ item.itemName }}</td>
            <td class="my-table">{{ item.quantity }}</td>
            <td class="my-table">
              <q-btn
                class="bg-red text-white"
                icon="fas fa-trash"
                @click="this.deleter(item.id)"
                flat
                v-if="this.sent == 'No'"
              />

              <q-btn
                class="bg-grey text-white"
                icon="fas fa-trash"
                disable
                flat
                v-if="this.sent == 'Yes'"
              />
            </td>
          </tr>
          <tr v-if="this.sent == 'No'">
            <td class="my-table"></td>
            <td class="my-tablex">
              <q-input
                class="q-mx-md q-my-sm"
                dense
                v-model="this.itemName"
                placeholder="add item"
                type="text"
                outlined
                id="focus-input"
              />
            </td>
            <td class="my-tablex">
              <q-input
                class="q-mx-md q-my-sm"
                dense
                v-model="this.quantity"
                placeholder="quantity"
                type="number"
                outlined
              />
            </td>
            <td class="my-tablex">
              <q-btn
                icon="fas fa-plus"
                @click="makeOrder"
                class="bg-positive text-white q-mx-md q-my-sm"
                v-if="this.quantity && this.itemName"
              />
              <q-btn
                icon="fas fa-plus"
                disable
                class="bg-grey text-white q-mx-md q-my-sm"
                v-else
              />
            </td>
          </tr>
        </table>
      </q-form>
    </div>

    <div class="row justify-center q-pa-md">
      <q-btn
        v-if="this.orderList.length > 0 && this.sent == 'No'"
        @click="submitOrder()"
        icon="fas fa-paper-plane"
        class="bg-pink text-white"
        label="submit"
      ></q-btn>

      <q-btn
        v-if="this.orderList.length < 1 && this.sent == 'No'"
        disable
        icon="fas fa-paper-plane"
        class="bg-grey text-white"
        label="submit"
      ></q-btn>

      <q-btn
        v-if="this.sent == 'Yes'"
        disable
        icon="fas fa-check"
        class="bg-white text-positive"
        flat
        label="SUBMITTED"
      ></q-btn>
    </div>
  </q-page>
</template>

<script lang="js">
import { defineComponent, ref } from 'vue';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useRoute } from 'vue-router';
import VueNumberFormat from 'vue-number-format';

const $q = useQuasar();
export default {
  name: 'Home',
  components:{

  },

  setup(){


    return {
        myInfo:ref({}),
        visible:ref(false),
        // showOrderFor:ref(false)
        kayak:ref(null)
    }
  }
,
  data(){
    return {
         department:ref(null),
         options:this.$store.state.options,
         orderQuery:ref(this.$route.query.item),
         orderList:ref([]),
         itemName:ref(null),
         unitPrice:ref(null),
         quantity:ref(null),
         department:ref(null),
         sent:ref(false),
         orderColumns: ref([
        {
          name: 'id',
          align: 'left',
          label: 'No.',
          field: 'no',
          sortable: true,
          sort: (a, b) => parseInt(a, 10) - parseInt(b, 10),
        },
        {
          name: 'ItemName',
          align: 'left',
          label: 'Item Name',
          field: 'itemName',
          sortable: true,
        },
         {
          name: 'quantity',
          align: 'left',
          label: 'Quantity',
          field: 'quantity',
          sortable: true,
          // sort: (a, b) => parseInt(a, 10) - parseInt(b, 10),
        },

        // {
        //   name: 'unitPrice',
        //   align: 'center',
        //   label: 'Unit Price',
        //   field: 'unitPrice',
        //   sortable: true,
        //   sort: (a, b) => parseInt(a, 10) - parseInt(b, 10),
        // },

        // {
        //   name: 'totalPrice',
        //   align: 'center',
        //   label: 'Total Price',
        //   field: 'totalPrice',
        //   sortable: true,
        //   sort: (a, b) => parseInt(a, 10) - parseInt(b, 10),
        // },
        // {
        //   name: 'Date',
        //   align: 'center',
        //   label: 'Date',
        //   field: 'date',
        //   sortable: true,
        // },


        ])

    }
  },

  methods:{

submitOrder(){
// console.log(this.orderQuery)
 var x = confirm("Are you sure you want to submit this order?");

 if(x){
    api.post(this.$store.state.apiLink+'orders.php',{
    request:"submitOrder",
    email: this.$store.state.myAuth.email,
    fullName: this.$store.state.myAuth.firstName +' '+this.$store.state.myAuth.lastName,
    username: this.$store.state.myAuth.username,
    phone: this.$store.state.myAuth.phone,
     orderQuery:this.orderQuery,


  },{ timeout: this.$store.state.timeout }).then(res=>{
    // console.log(res.data);
 this.startup();
    if(res.data =='Success'){
    alert("Order submitted successfully")

    }


  })
 }

},


deleter(x){
  var c = confirm("Do you want to delete this item??")

  if(c){
    api.post(this.$store.state.apiLink+"orders.php",
  {
    id: x,
    request:"delete"
  },{ timeout: this.$store.state.timeout }).then(res=>{
    console.log(res.data)
    if(res.data == 'Success'){
      this.startup();
    }
  })
  }
},

     numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
},


startup(){
 this.$store.commit("selector", this.$route.query.item)
   api.post(this.$store.state.apiLink+'orders.php',{
     request:"order_items",
     id:this.orderQuery,
      email: this.$store.state.myAuth.email
   },{ timeout: this.$store.state.timeout }).then(res=>{
    //  console.log(res.data)
      if(res.data != 'Unauthorized'){
        this.orderList = res.data.items;
        this.sent = res.data.sent;
      var i = 0;
// document.getElementById("focus-input").focus();

     if(this.orderList.length > 0){
        for(i = 0; i <= (this.orderList.length -1); i++ ){
        this.orderList[i].quantity = this.numberWithCommas(this.orderList[i].quantity);
      }
     }
      } else{
        alert("Invalid link");
        this.$store.commit("authenticator", null)
      }



   })
        this.itemName = null;
        this.quantity = null;
},


makeOrder(){

  if(this.quantity && this.itemName){
    api.post(this.$store.state.apiLink+'orders.php', {
      request:"makeOrder",
      itemName:this.itemName,
      quantity:this.quantity,
      email:this.$store.state.myAuth.email,
      department:this.$store.state.myAuth.department,
      occupation:this.$store.state.myAuth.occupation,
      fullName:this.$store.state.myAuth.firstName+' '+this.$store.state.myAuth.lastName,

    },{ timeout: this.$store.state.timeout }).then(res=>{
      // console.log(res.data);
//  this.startup();
      if(res.data == 'Success'){

        this.startup();
      }
    })
  } else{
    console.log("err")
  }

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
