<template>

    <div class="container">
        <div class="row">
            <div class="col-md-10"> <!-- Utilisez la colonne Bootstrap pour les éléments du panier -->
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
                                        <div class="border-bottom border-3 border-light border-top-0 border-start-0 border-end-0 my-1 py-1 d-flex row"  v-for="(cart,index) in carts" :key="index">
                                        <div class="me-3 col-4 d-flex align-items-center justify-content-center"><img :src="Config.backendConfig.apiUrl+cart.img_path" class="card-img-top w" :alt="cart.short_desc"></div>
                                        <div class="me-3 col-2 d-flex align-items-center justify-content-center">{{ cart.nom }}</div>
                                        <div class="me-3 col-2 d-flex align-items-center justify-content-center">{{ formatPrice(cart.prix) }}</div>
                                        <div class="me-3 col-1 d-flex align-items-center justify-content-center">{{ cart.qte }} </div>

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

    <div class="modal" id="modal_adress_livraison" tabindex="-1">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Modifier l'adresse de livraison</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adresse_livraison_form">
                    <adress_form></adress_form>
                    <button id="btn_change_adresse_livraison" class="btn btn-primary" @click="change_adress()">Sauvegarder</button>
                </form>
                
    
        </div>
            <div class="modal-footer">
                <button id="btn_dismiss_modal_livraison" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
        </div>
    </div>
    <div class="modal" id="modal_adress_facturation" tabindex="-1">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Modifier l'adresse de facturation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adresse_facturation_form">
                    <adress_form></adress_form>
                    <button id="btn_change_adresse_facturation" class="btn btn-primary" @click="change_adress()">Sauvegarder</button>
                </form>
                
    
        </div>
            <div class="modal-footer">
                <button id="btn_dismiss_modal_facturation" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
        </div>
    </div>

</template>

<script setup lang="ts">
import { onMounted,ref, defineProps} from 'vue';
import { get_cart,formatPrice} from '../scripts/commun';
import Config from "../scripts/config";
import { getCookie } from "../scripts/token";
import { Modal } from 'bootstrap';
import adress_form from "./subcomponents/Adress_form.vue"
import { CommandeStore } from '../store/commande';



const props = defineProps(["numbers"])
const profil = ref(null);
const carts = ref([]);
const facturation_differente = ref(false);

const pay = async () => {


const route = "/stripe/pay";


let options = {
    method: 'POST',
    headers: {
        "X-CSRF-TOKEN":getCookie("X-CSRF-TOKEN"),
    },
    body: JSON.stringify({
            
            "user_token": getCookie("USER-TOKEN"),
            
          }),
}
console.log (options);
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