<template>

    <div class="container">

        <div  class="row">
            <div class="col-md-12"> <!-- Utilisez la colonne Bootstrap pour les éléments du panier -->
                <div class="card m-2 mb-0">
                    <div class="card-body">
                        <section v-if="carts" id="recapitulatif" class=" border-bottom border-3">
                            <h3 class="card-title">Récapitulatif </h3>
                            
                                <div class="container" >
                                    <div class="border-bottom border-3 border-light border-top-0 border-start-0 border-end-0 my-1 py-1 d-flex row">

                                        <div class="me-3 col-4 d-flex align-items-center justify-content-center"></div>
                                        <div class="me-3 col-2 d-flex align-items-center justify-content-center"></div>
                                        <div class="me-3 col-2 d-flex align-items-center justify-content-center">Prix</div>
                                        <div class="me-3 col-1 d-flex align-items-center justify-content-center">Quantité </div>


                                    </div>
                                    <div class="m-0 p-0 overflow-y-auto" style="max-height: 300px; ">
                                        <div class="m-0 p-0"  v-for="(cart,index) in carts" :key="index">
                                            <div class="border-bottom border-3 border-light border-top-0 border-start-0 border-end-0 my-1 py-1 d-flex row" v-if="cart.standby==false">
                                                <div class="me-3 col-4 d-flex align-items-center justify-content-center"><img :src="Config.backendConfig.apiUrl+cart.img_path" class="card-img-top w" :alt="cart.short_desc"></div>
                                                <div class="me-3 col-2 d-flex align-items-center justify-content-center">{{ cart.nom }}</div>
                                                <div class="me-3 col-2 d-flex align-items-center justify-content-center">{{ formatPrice(cart.prix) }}</div>
                                                <div class="me-3 col-1 d-flex align-items-center justify-content-center">{{ cart.qte }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </section>
                        <section class=" border-bottom border-3">
                          <div class="container">
                            <div class="row">
                              <div class="col-6">
                                <h3 class="card-title">Adresse de livraison</h3>
                                <div v-if="profil">
                                    <p>{{ profil.name }}</p>
                                    <p>{{ profil.adresse_livraison }}</p>
                                    
                                </div>
                            <div v-else class="spinner-border " role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                              </div>
                              <div class="col-6">
                                <h3 class="card-title">Adresse de facturation</h3>
                                <label class="me-3" for="facturation_differente_checkbox">Adresse de facturation differente</label>
                                <input class="btn btn-primary" id="facturation_differente_checkbox" type="checkbox" name="facturation_differente_checkbox" v-model="facturation_differente">
                                <div v-if="facturation_differente">
                                  <div v-if="profil">
                                    <p>{{ profil.name }}</p>
                                    <p>{{ profil.adresse_facturation }}</p>
                                  </div>
                                  <div v-else class="spinner-border " role="status">
                                    <span class="visually-hidden">Loading...</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                            
                            
                        </section>
                        <section v-if="profil" id="moyen_paiement" class=" border-bottom border-3">
                            <h3 class="card-title">Paiement</h3>
                            <div >
                                <button type="button" @click="pay">Passer la commande</button> 
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup lang="ts">
import { onMounted,ref,Ref} from 'vue';
import { get_cart,formatPrice} from '../scripts/commun';
import {CartItem,profilItem} from '../scripts/interface';
import Config from "../scripts/config";
import { getCookie } from "../scripts/token";




const profil: Ref<profilItem | null> = ref<profilItem | null>(null);
const carts: Ref<CartItem[] | null> = ref <CartItem[] | null>(null);
const facturation_differente: Ref<boolean> = ref <boolean>(false);

const pay = async () => {


const route = "/stripe/pay";


let options = {
    method: 'POST',
    headers: {
        "X-CSRF-TOKEN":getCookie("X-CSRF-TOKEN"),
        "Content-Type": "application/json",
    },
    body: JSON.stringify({
            
            "user_token": getCookie("USER-TOKEN"),
            
          }),
}
//console.log (options);
fetch(Config.backendConfig.apiUrl+route, options)
.then(response => {
    
    if (!response.ok) {
        throw new Error('La requête a échoué.');
    }
    return response.json();
}) // Si le script PHP renvoie du JSON
.then(data => {
   console.log(data);
   window.open(data.url, '_blank');


})
.catch(error => {
    // Gérer les erreurs de la requête
    console.error(error);
    
});

}

onMounted ( async ()=>{
    profil.value = JSON.parse(getCookie("Profil"));
    carts.value=await get_cart();
})




</script>

<style scoped>


</style>