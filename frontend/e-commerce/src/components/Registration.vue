<template>

<section class="inscription_form bg-dark p-4">
        <h1 class="d-flex justify-content-center text-white">Inscription</h1>
        <div class="container">
            <div class="row ">
                <form method="post" action="./contact_form_treatment.php" id="contact_form">
                    <div class="mb-3 d-flex">
                        <div class="me-3">
                            <label for="inscription_surname" class="form-label text-white">Prénom</label>
                            <input type="text" class="form-control" id="inscription_surname" name="first_name" placeholder="">
                        </div>
                        <div class="me-3">
                            <label for="contact_name" class="form-label text-white">Nom</label>
                            <input type="text" class="form-control" id="inscription_name" name="last_name" placeholder="">
                        </div>
                        <div class="mb-3 champ">
                        <label for="inscription_date_naissance" class="form-label text-white">Date de naissance</label>
                        <input type="date" class="form-control" id="inscription_date_naissance" name="birthday" placeholder="prenom.nom@email.com">
                    </div>
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="me-3 champ w-25">
                            <label for="phone_extension" class="form-label">&nbsp;</label>
                            <select class="form-select" aria-label="Default select example" >
                                <option  v-for="(extension,country) in phone_prefix" :value="extension" :selected="country==='FR'">{{ country +" "+ extension}} </option>
                            </select>
                        </div>
                        <div class="me-3 champ w-50">
                            <label for="phone" class="form-label text-white">Téléphone</label>
                            <input type="tel" pattern="[0-9]{9}" class="form-control" id="phone" name="phone" placeholder="ex 601234567">
                        </div>
                        <div class=" champ w-50">
                            <label for="inscription_email" class="form-label text-white">Email</label>
                            <input type="email" class="form-control" id="inscription_email" name="email" placeholder="prenom.nom@email.com">
                        </div>
                        
                    </div>

                    <div class="mb-3 champ d-flex">
                        <div class="me-3">
                            <label for="adress_number" class="form-label text-white">Numéro</label>
                            <input type="text" class="form-control" id="adress_number" name="adress_number" placeholder="3B">
                        </div>
                        <div class="w-100">
                            <label for="adress" class="form-label text-white">Adresse</label>
                            <input type="text" class="form-control" id="inscription_name" name="last_name" placeholder="">
                        </div>
                    </div>

                    <div class="mb-3 champ d-flex">
                        <div class="me-3">
                            <label for="Postal_code" class="form-label text-white">Code Postal</label>
                            <input type="text" class="form-control" id="Postal_code" name="Postal_code" placeholder="">
                        </div>
                        <div class="me-3">
                            <label for="City" class="form-label text-white">Ville</label>
                            <input type="text" class="form-control" id="City" name="City" placeholder="">
                        </div>
                        <div class="">
                            <label for="Country" class="form-label text-white">Pays</label>
                            <input type="text" class="form-control" id="Country" name="Country" placeholder="">
                        </div>
                    </div>


                    <div class="g-recaptcha" data-sitekey="your_site_key"></div>

                    <div>
                        <input type="hidden" id="captcha" name="captcha">
                    </div>
                    
                <input class="btn btn-primary w-100" type="button" value = "envoyer" @click="submit_inscription_form()">
                
                </form>
            </div>
        </div>
    </section>
  
  </template>
    
  <script setup lang="ts">
    import { ref,onMounted,defineProps } from 'vue';
    import { getCsrfToken,getUserToken,AskCsrfToken} from "../scripts/token";
    import Config from "../scripts/config";
    import { useRoute } from 'vue-router';
    import {formatPrice,phone_prefix} from "../scripts/commun";
    
    const article = ref(null);
    
const submit_inscription_form = async () => {
    // Récupérer les données du formulaire

    var form = document.getElementById('contact_form');
    var formData = new FormData(form);
    const route = "/user/create_user";
    console.log (formData);
    await AskCsrfToken ();

    let options = {
        method: 'POST',
        headers: {
            "X-CSRF-TOKEN":getCsrfToken(),
        },
        body: formData,
    }
    console.log (options);
    fetch(props.backendConfig.apiUrl+route, options)
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
        alert ("inscription effectuée");

    })
    .catch(error => {
        // Gérer les erreurs de la requête
        console.error("Erreur lors de l'envoi du formulaire:", error);
        alert ("erreur : veuillez contacter l'administrateur du site")
    });
}
  
  onMounted( async () => {
    AskCsrfToken();

  });
  
  
  
  
  
    </script>
    
    <style scoped lang="scss">
    
    
    
    </style>
    