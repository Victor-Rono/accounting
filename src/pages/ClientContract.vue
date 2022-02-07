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
        <tag class="text-pink">{{ this.orderList?.fullName }}</tag>
        ORDER ID
        <tag class="text-pink">#{{ this.orderList?.id }}</tag>
      </div>

      <div class="text-bold text-h6">
        PHONE NUMBER:
        <tag class="text-pink">{{ this.orderList?.phone }}</tag
        ><br />
        EMAIL:
        <tag class="text-pink">{{ this.orderList?.email }}</tag>
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
          <tr
            v-for="item in this.orderList?.items"
            class="my-table"
            @dblclick=""
          >
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
    <q-separator class="text-bold q-mt-xl q-mb-md"></q-separator>
    <div class="" v-if="!this.orderList.contractor">
      <div class="q-mt-lg">
        <div class="text-center">Accept or reject Order</div>
      </div>

      <div class="row justify-center q-pa-md">
        <div class="col q-pa-md">
          <q-btn
            @click="respond('accept')"
            label="Accept"
            icon="fas fa-check"
            flat
            dense
            class="bg-positive text-white"
          />
        </div>
        <div class="col q-pa-md">
          <q-btn
            @click="respond('reject')"
            label="Reject"
            icon="close"
            flat
            dense
            class="bg-red text-white"
          />
        </div>
      </div>
      <div class="row justify-center">
        <q-btn
          v-if="this.contractor"
          @click="submitOrder()"
          icon="fas fa-paper-plane"
          class="bg-pink text-white"
          label="submit"
        ></q-btn>

        <q-btn
          v-if="!this.contractor"
          disable
          icon="fas fa-paper-plane"
          class="bg-grey text-white"
          label="submit"
        ></q-btn>
      </div>
    </div>

    <div class="row justify-center">
      <q-badge
        class="text-center text-bold bg-positive text-white"
        v-if="this.orderList?.contractor"
      >
        This order has been sent to one of our contractors
      </q-badge>
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
         orderList:ref(false),
         itemName:ref(null),
         unitPrice:ref(null),
         quantity:ref(null),
         department:ref(null),
         sent:ref(false),
        contractor:ref(null),
        options: ref([

      ]),


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


respond(x){
  api.post(
    this.$store.state.apiLink+'accounting.php',{
      request:'respond',
      id: this.orderQuery,
      response: x
    },{timeout:this.$store.state.timeout}
  ).then(res=>{

  })
}

submitOrder(){
// console.log(this.orderQuery)
console.log(this.contractor.fullName)
 var x = confirm("Are you sure you want to submit this order?");

 if(x){
    api.post(this.$store.state.apiLink+'accounting.php',{
    request:"submitOrder",
    id:this.orderQuery,
    contractor: this.contractor.username,
    fullName:this.contractor.fullName,
    username:this.$store.state.myAuth.username,
    email:this.orderList?.email



  },{ timeout: this.$store.state.timeout }).then(res=>{
    console.log(res.data);

    if(res.data =='Success'){
    alert("Order submitted successfully")
    this.startup();
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
//  this.$store.commit("selector", this.$route.query.item)
   api.post(this.$store.state.apiLink+'orders.php',{
     request:"view_order",
     id:this.orderQuery,
      email: this.$store.state.myAuth.email,
      department:this.$store.state.myAuth.department,
   },{ timeout: this.$store.state.timeout }).then(res=>{
     console.log(res.data)
      if(res.data != 'Unauthorized'){

        this.orderList = res.data;
        this.sent = res.data.sent;
      var i = 0;
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

    api.post(this.$store.state.apiLink+'accounting.php',{

      request:'contractors'

    }, {timeout: this.$store.state.timeout}).then(res=>{
      this.options = res.data;
      console.log(res.data)
    })

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
