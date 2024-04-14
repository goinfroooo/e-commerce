<template>

    <div class="container">
        <div class="row">
            <div class="col-md-10"> <!-- Utilisez la colonne Bootstrap pour les éléments du panier -->
                <div class="card m-2 mb-0">
                    <div class="card-body">
                        <section class=" border-bottom border-3">
                            <h3 class="card-title">Adresse de livraison</h3>
                            <div v-if="profil">
                                <p>{{ profil.name }}</p>
                                <p>{{ profil.adress }}</p>
                                
                            </div>
                            <div v-else class="spinner-border " role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            
                        </section>
                        <section v-if="profil" id="moyen_paiement" class=" border-bottom border-3">
                            <h3 class="card-title">Moyen de Paiement</h3>
                            <div >
                                <p>Paypal</p>
                                <p>Carte bancaire</p>
                                <StripeElements
                                v-if="stripeLoaded"
                                v-slot="{ elements, instance }" 
                                ref="elms"
                                :stripe-key="stripeKey"
                                :instance-options="instanceOptions"
                                :elements-options="elementsOptions"
                            >
                                <StripeElement
                                ref="card"
                                :elements="elements"
                                :options="cardOptions"
                                />
                            </StripeElements>
                            <button type="button" @click="pay">Pay</button>
                                
                            </div>
                            
                            
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted,ref,computed,onBeforeMount, defineProps} from 'vue';
import { formatPrice } from '../scripts/commun';
import Config from "../scripts/config";
import { getCsrfToken,AskCsrfToken,getUserToken,getProfil } from "../scripts/token";
import { Modal } from 'bootstrap';
import adress_form from "./subcomponents/Adress_form.vue"
import { CommandeStore } from '../store/commande';
import { loadStripe } from '@stripe/stripe-js'
import { StripeElements, StripeElement } from 'vue-stripe-js'


onBeforeMount(() => {
      const stripeLoaded = ref(false)
      const stripePromise = loadStripe('your_key')
      stripePromise.then(() => {
        stripeLoaded.value = true;
      })
    });



    const stripeKey = ref('pk_test_TYooMQauvdEDq54NiTphI7jx') // test key
    const instanceOptions = ref({
      // https://stripe.com/docs/js/initializing#init_stripe_js-options
    })
    const elementsOptions = ref({
      // https://stripe.com/docs/js/elements_object/create#stripe_elements-options
    })
    const cardOptions = ref({
      // https://stripe.com/docs/stripe.js#element-options
      value: {
        postalCode: '12345',
      },
    })
    const stripeLoaded = ref(false)
    const card = ref()
    const elms = ref()

    const pay = () => {
      // Get stripe element
      const cardElement = card.value.stripeElement

      // Access instance methods, e.g. createToken()
      elms.value.instance.createToken(cardElement).then((result: object) => {
        // Handle result.error or result.token
        console.log(result)
      })
    }


const props = defineProps(["numbers"])
const profil = ref(null);


onMounted (()=>{
    profil.value = JSON.parse(getProfil())

})




</script>

<style scoped>


</style>