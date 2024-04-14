<template>

    <section id="profil">
        <div class="container-fluid ps-2 m-3 bg-light">
            <div v-if="!profil" class="spinner-border " role="status">
                <span class="visually-hidden ">Loading...</span>
            </div>
            <div v-else>
            <div class="row me-2">
                <div class="card col-12">
                    <div class="d-flex">
                        <img class="rounded-3 me-3" :src="Config.backendConfig.apiUrl+'/storage/img/profil.PNG'" height="50px" width="50px">
                        <h1 class="d-flex justify-content-center card-title">Profil</h1>
                    </div>
                    
                    <div class="card-body rounded-3 border border-2 border-black my-2">
                        <table class=" w-100 " >
                            <tr>
                                <td class="p-3 rounded-3 bg-light w-25">nom</td>
                                <td class="p-3  w-25"><input type="text" v-model="profil.name" class="form-control"></td>
                                <td class="p-3  bg-light w-25">Date de naissance</td>
                                <td class="p-3  w-25"><input type="date" v-model="profil.birthday" class="form-control"></td>

                            </tr>
                            <tr>

                                <td class="p-3  bg-light w-25" style="--bs-bg-opacity: .8;">Adresse mail</td>
                                <td class="p-3 w-25">{{ profil.email}} <br><button class="btn btn-primary" hidden>modifier l'email</button></td>
                                <td class="p-3  bg-light w-25" style="--bs-bg-opacity: .8;">Téléphone</td>
                                <td class="p-3 w-25"><input v-model="profil.phone" class="form-control"></td>
                            </tr>
                            <tr>
                                
                                <td class="p-3  bg-light w-25">Profil crée le</td>
                                <td class="p-3 w-25">{{ date_creation}}</td>
                            </tr>

                            
                        </table>
                    </div>
                    <button class="bg-light border-3" @click="save_change()">Sauvegarder</button>
                </div>
            </div>
            <div  class="row">
                <div class="card col-6">
                    <div class="card-title"><h3>Adresse de livraison</h3></div>
                    <div class="card-body">
                    <div class="p-3" >{{ profil.adress}} <br><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_adress_livraison">modifier l'adresse</button></div>
                    </div>
                    
                </div>
                <div class="card col-6">
                    <div class="card-title"><h3>Adresse de facturation</h3></div>
                    <div class="card-body">

                    <div class="p-3 ">{{ profil.adress}} <br><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_adress_facturation">modifier l'adresse</button></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

        <!-- Modal -->
    <div class="modal" id="modal_adress_livraison" tabindex="-1">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Connexion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adresse_livraison_form">
                    <adress_form></adress_form>
                    <button class="btn btn-primary" @click="change_adress_livraison()">Sauvegarder</button>
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
                <h5 class="modal-title fw-bold">Connexion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adresse_facturation_form">
                    <adress_form></adress_form>
                    <button class="btn btn-primary" @click="change_adress_facturation()">Sauvegarder</button>
                </form>
                
    
        </div>
            <div class="modal-footer">
                <button id="btn_dismiss_modal_facturation" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
        </div>
    </div>
        
    </section>

</template>

<script setup lang="ts">
import { onMounted,ref,computed} from 'vue';
import { useRouter } from 'vue-router';
import { formatPrice } from '../scripts/commun';
import Config from "../scripts/config";
import { getCsrfToken,AskCsrfToken,getUserToken,getProfil,setCookie } from "../scripts/token";
import { Modal } from 'bootstrap';
import adress_form from "./subcomponents/Adress_form.vue"


const router = useRouter();
const profil = ref(null) ;
const date_creation = computed (() =>{
    //let dateString = '2024-03-08T10:02:46.000000Z';
    let dateString = profil.value.created_at;
    
    const months = [
        "janvier", "février", "mars", "avril", "mai", "juin",
        "juillet", "août", "septembre", "octobre", "novembre", "décembre"
    ];

    const date = new Date(dateString);
    const day = date.getDate();
    const monthIndex = date.getMonth();
    const year = date.getFullYear();

    const formattedDate = day + ' ' + months[monthIndex] + ' ' + year;
    return formattedDate;
});

const date_anniversaire = computed (() =>{
    let dateString = profil.value.birthday;
    const months = [
        "janvier", "février", "mars", "avril", "mai", "juin",
        "juillet", "août", "septembre", "octobre", "novembre", "décembre"
    ];

    const [year, month, day] = dateString.split('-');
    const formattedDate = `${day} ${months[parseInt(month) - 1]} ${year}`;

    return formattedDate;
});


const save_change = async () => {

    let formData = new FormData();

    // Parcourir les clés de l'objet JSON
    for (var key in profil.value) {
        if (profil.value.hasOwnProperty(key)) {
            // Ajouter chaque paire clé-valeur à l'objet FormData
            formData.append(key, profil.value[key]);
        }
    }
    formData.append("user_token",getUserToken());
    console.log(formData);


    const route = "/user/update_profil";
    await AskCsrfToken ();

    let options = {
        method: 'POST',
        headers: {
            "X-CSRF-TOKEN":getCsrfToken(),
        },
        body: formData,
    }
    console.log (options);
    fetch(Config.backendConfig.apiUrl+route, options)
    .then(response => {
        console.log(response)
        if (!response.ok) {
            throw new Error('La requête a échoué.');
        }
        return response.text();
    }) // Si le script PHP renvoie du JSON
    .then(data => {
        // Traiter la réponse du serveur (si nécessaire)
        console.log(data);
        setCookie("Profil",JSON.stringify(profil.value),30)
        alert ("profil mis à jour");

    })
    .catch(error => {
        // Gérer les erreurs de la requête
        console.error(error);
        
    });
    
}

const change_adress_livraison = ($event) => {
    event?.preventDefault();
    const form =document.getElementById ("adresse_livraison_form");
    const formData: FormData = new FormData(form);
    const adress: string = formData.get("adress_number")+" "+formData.get("adress")+" "+formData.get("postal_code")+" "+formData.get("city")+" "+formData.get("country");
    profil.value.adress=adress;
    save_change();
    document.getElementById("btn_dismiss_modal_livraison").click();

}


onMounted (()=>{

    profil.value = JSON.parse(getProfil());
    //const modal_livraison = new Modal(document.getElementById('modal_adresse_livraison'),{keyboard: true});
    //const modal_facturation = new Modal(document.getElementById('modal_adresse_facturation'),{keyboard: true});

})

router.afterEach(() => {
  // Rafraîchir les données du profil à chaque changement de route
  profil.value = JSON.parse(getProfil());
});


</script>

<style scoped>


</style>