<template>

    <section id="profil">
        <div class="container-fluid ps-2 m-3 bg-light">
            <div v-if="!profil" class="spinner-border " role="status">
                <span class="visually-hidden ">Loading...</span>
            </div>
            <div v-else class="row ">
                
                <h1 class="d-flex justify-content-center">Profil</h1>
                <table class="border border-black border-3 w-100" >
                    <tr>
                        <td class="p-3 bg-dark bg-gradient fw-bold text-white w-25">nom</td>
                        <td class="p-3 bg-light text-dark w-25"><input v-model="profil.name" class="form-control"></td>
                        <td class="p-3 bg-dark bg-gradient fw-bold text-white w-25">Date de naissance</td>
                        <td class="p-3 bg-light text-dark w-25"><input v-model="profil.birthday" class="form-control"></td>

                    </tr>
                    <tr>

                        <td class="p-3 bg-primary bg-gradient fw-bold w-25" style="--bs-bg-opacity: .8;">Adresse mail</td>
                        <td class="p-3 bg-light text-dark w-25">{{ profil.email}} <br><button class="btn btn-primary">modifier l'email</button></td>
                        <td class="p-3 bg-primary bg-gradient fw-bold w-25" style="--bs-bg-opacity: .8;">Téléphone</td>
                        <td class="p-3 bg-light text-dark border border-1 w-25"><input v-model="profil.phone" class="form-control"></td>
                    </tr>
                    <tr>
                        <td class="p-3 bg-dark bg-gradient fw-bold text-white w-25">Adresse</td>
                        <td class="p-3 bg-light text-dark w-25">{{ profil.adress}} <br><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_adress">modifier l'adresse</button></td>
                        <td class="p-3 bg-dark bg-gradient fw-bold text-white w-25">Profil crée le</td>
                        <td class="p-3 bg-light text-dark w-25">{{ date_creation}}</td>
                    </tr>

                    
                </table>
                <button @click="save_change()">Sauvegarder</button>

            </div>
        </div>

        <!-- Modal -->
    <div class="modal" id="modal_adress" tabindex="-1">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Connexion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <adress_form></adress_form>
    
        </div>
            <div class="modal-footer">
                <button id="btn_dismiss_modal_connexion" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
        </div>
    </div>
        
    </section>

</template>

<script setup>
import { onMounted,ref,computed} from 'vue';
import { formatPrice } from '../scripts/commun';
import Config from "../scripts/config";
import { getCsrfToken,AskCsrfToken,getUserToken,getProfil } from "../scripts/token";
import { Modal } from 'bootstrap';
import adress_form from "./subcomponents/Adress_form.vue"

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
        alert ("profil mis à jour");

    })
    .catch(error => {
        // Gérer les erreurs de la requête
        console.error(error);
        
    });
    
}


onMounted (()=>{

    profil.value = JSON.parse(getProfil())
    const modal = new Modal(document.getElementById('modal_connexion',{keyboard: true}));
})


</script>

<style scoped>


</style>