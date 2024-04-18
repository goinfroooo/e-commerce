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
                                <td class="p-3 w-25">{{ profil.email}} <br><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_mail">modifier l'email</button></td>
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
                    <div class="p-3" >{{ profil.adresse_livraison}} <br><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_adress_livraison">modifier l'adresse</button></div>
                    </div>
                    
                </div>
                <div class="card col-6">
                    <div class="card-title"><h3>Adresse de facturation</h3></div>
                    <div class="card-body">

                    <div class="p-3 ">{{ profil.adresse_facturation}} <br><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_adress_facturation">modifier l'adresse</button></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

        <!-- Modal -->
        <div class="modal" id="modal_mail" tabindex="-1">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Modifier l'adresse mail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adresse_mail_form">
                    <input class="me-3" type="text" name="new_mail" placeholder="john.doe@email.com">
                    <button class="btn btn-primary" @click="change_mail()">Sauvegarder</button>
                </form>
                
    
        </div>
            <div class="modal-footer">
                <button id="btn_dismiss_modal_livraison" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
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
        
    </section>

</template>

<script setup lang="ts">
import { onMounted,ref,computed} from 'vue';
import { useRouter } from 'vue-router';
import Config from "../scripts/config";
import { AskCsrfToken,getCookie, setCookie } from "../scripts/token";
import adress_form from "./subcomponents/Adress_form.vue"
import { Modal } from 'bootstrap';


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
    formData.append("user_token",getCookie("USER-TOKEN"));
    console.log(formData);


    const route = "/user/update_profil";
    await AskCsrfToken ();

    let options = {
        method: 'POST',
        headers: {
            "X-CSRF-TOKEN":getCookie("X-CSRF-TOKEN"),
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
        return response.json();
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

const change_adress = ($event) => {
    event?.preventDefault();
    const type_adress = (event?.target.id).split("_")[3];
    console.log(type_adress);
    const form =document.getElementById ("adresse_"+type_adress+"_form");
    const formData: FormData = new FormData(form);
    const adress: string = formData.get("adress_number")+" "+formData.get("adress")+" "+formData.get("postal_code")+" "+formData.get("city")+" "+formData.get("country");
    profil.value["adresse_"+type_adress]=adress;
    save_change();
    document.getElementById("btn_dismiss_modal_livraison").click();

}

const change_mail = async ($event) => {

event?.preventDefault();
let formData = new FormData(document.getElementById("adresse_mail_form"));

formData.append("user_token",getCookie("USER-TOKEN"));
console.log(formData);


const route = "/user/change_mail";
await AskCsrfToken ();

let options = {
    method: 'POST',
    headers: {
        "X-CSRF-TOKEN":getCookie("X-CSRF-TOKEN"),
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
    return response.json();
}) // Si le script PHP renvoie du JSON
.then(data => {
    // Traiter la réponse du serveur (si nécessaire)
    console.log(data);
    if (data.message==="sucess"){
        alert ("Un e-mail a été envoyé à la nouvelle adresse. Veuillez confirmer le changement en cliquant sur le lien dans le mail");
    }
    else {
        alert ("le message de confirmation n'a pas pu etre envoyé. Veuillez vérifier l'adresse mail avant de réessayer. Si le problème persiste contactez l'administrateur du site.");
    }
})
.catch(error => {
    // Gérer les erreurs de la requête
    console.error(error);
    
});

}


onMounted (()=>{

    const modal_mail = new Modal(document.getElementById('modal_mail',{keyboard: true}));
    const modal_livraison = new Modal(document.getElementById('modal_adress_livraison',{keyboard: true}));
    const modal_modal_facturation = new Modal(document.getElementById('modal_adress_facturation',{keyboard: true}));
    profil.value = JSON.parse(getCookie("Profil"));
    //const modal_livraison = new Modal(document.getElementById('modal_adresse_livraison'),{keyboard: true});
    //const modal_facturation = new Modal(document.getElementById('modal_adresse_facturation'),{keyboard: true});

})

router.afterEach(() => {
  // Rafraîchir les données du profil à chaque changement de route
  profil.value = JSON.parse(getCookie("Profil"));
});


</script>

<style scoped>


</style>