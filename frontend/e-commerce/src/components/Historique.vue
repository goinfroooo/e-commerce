<template>

    <section id="historique">
        <div class="container-fluid ps-2 m-3 bg-light">
            <div v-if="!commandes" class="spinner-border " role="status">
                <span class="visually-hidden ">Loading...</span>
            </div>
            <div v-else>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12"> <!-- Utilisez la colonne Bootstrap pour les éléments du panier -->
                            <div class="card m-2 mb-0">
                                <div class="card-body">
                                        <h3 class="card-title">Historique des commandes</h3>
                                            <section class="container my-4"  v-for="commande in commandes" >
                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                    <h4 class="m-0">Commande n°{{ commande.numero }}</h4>
                                                    <span class="text-right">{{ formatDate(commande.date_commande) }}</span>
                                                </div>
                                                <div class="border-bottom border-3 border-light border-top-0 border-start-0 border-end-0 my-1 py-1 d-flex row">

                                                    <div class="me-3 col-4 d-flex align-items-center justify-content-center"></div>
                                                    <div class="me-3 col-2 d-flex align-items-center justify-content-center"></div>
                                                    <div class="me-3 col-2 d-flex align-items-center justify-content-center">Prix</div>
                                                    <div class="me-3 col-1 d-flex align-items-center justify-content-center">Quantité </div>


                                                </div>
                                                <div class="m-0 p-0 overflow-y-auto" style="max-height: 300px; ">
                                                    <div class="my-1 py-1 d-flex row"  v-for="article in commande.articles" :key="index">
                                                        <div class="me-3 col-4 d-flex align-items-center justify-content-center"><img :src="Config.backendConfig.apiUrl+article.img_path" class="card-img-top w" :alt="article.short_desc"></div>
                                                        <div class="me-3 col-2 d-flex align-items-center justify-content-center">{{ article.nom }}</div>
                                                        <div class="me-3 col-2 d-flex align-items-center justify-content-center">{{ formatPrice(article.prix) }}</div>
                                                        <div class="me-3 col-1 d-flex align-items-center justify-content-center">{{ article.qte }} </div>

                                                    </div>
                                                </div>
                                            </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

</template>

<script setup lang="ts">
import { onMounted,ref,computed} from 'vue';
import {formatPrice} from '../scripts/commun';
import { useRouter } from 'vue-router';
import Config from "../scripts/config";
import { getCookie} from "../scripts/token";
import moment from 'moment';


const router = useRouter();
const profil = ref(null) ;
const commandes = ref([])

const formatDate = (date) => {

    let originalDate = new Date(date);

    // Extraction des composantes de la date
    let jour = originalDate.getDate();
    let mois = originalDate.getMonth() + 1; // Les mois commencent à partir de 0, donc ajouter 1
    let annee = originalDate.getFullYear();

    // Formatage de la date
    let dateFormatee = `${jour < 10 ? '0' + jour : jour}-${mois < 10 ? '0' + mois : mois}-${annee}`;

    return dateFormatee;
}



const get_commandes = async () => {

    
try {
    const user_token = getCookie("USER-TOKEN");

if (!user_token) {
    alert ("veuillez vous connectez ou creer un compte");
    return -1;
}

const route = "/commande/get";
let options = {
    method: 'POST',
    headers: {
        "X-CSRF-TOKEN": getCookie("X-CSRF-TOKEN"),
        "Content-Type": "application/json",
    },
    body: JSON.stringify({
        "user_token": user_token,
    }),
}
console.log(options);


    const response = await fetch(Config.backendConfig.apiUrl + route, options);
    if (!response.ok) {
        throw new Error('La requête a échoué.');
    }
    const data = await response.json();
    console.log(data);
    return data.body;
} catch (error) {
    console.error("Erreur lors de l'envoi du formulaire:", error);
    alert("erreur : veuillez contacter l'administrateur du site")
}

}

onMounted ( async ()=>{

    profil.value = JSON.parse(getCookie("Profil"));
    commandes.value = await get_commandes();

})

router.afterEach(() => {
  // Rafraîchir les données du profil à chaque changement de route
  profil.value = JSON.parse(getCookie("Profil"));
});


</script>

<style scoped>


</style>