<template>
    <header class="bg-light fixed-top py-1">
        <nav class="navbar navbar-expand-md  navbar-light bg-light">
            <div class="container-fluid">
                <router-link to="/" class="navbar-brand text-uppercase fw-bold text-brand ms-4">
                    <span class="bg-primary bg-gradient p-1 rounded-3 text-light">E</span> Commerce
                </router-link>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end me-3" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <router-link to="/" class="nav-link">Profil</router-link>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link " data-bs-toggle="modal" data-bs-target="#modal_connexion">Connexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        
    </header>

    <!-- Modal -->
<div class="modal" id="modal_connexion" tabindex="-1">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Connexion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="./contact_form_treatment.php" id="connexion_form">
                                    <div class="mb-3">
                                        <label for="contact_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="connexion_email" name="connexion_email" placeholder="prenom.nom@email.com">
                                    </div>
                                    <div class="mb-3">
                                        <label for="connexion_password" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" id="connexion_password" name="connexion_password" placeholder="">
                                    </div>

                                    <input class="btn btn-primary w-100" type="button" value = "Connexion" @click="submit_connexion_form()">
                            </form>
                        <p>Pas encore inscrit ? Inscrivez vous <router-link to="/Registration" @click="closeModal">ici</router-link></p>
                    
                        </div>
                            <div class="modal-footer">
                                <button id="btn_dismiss_modal_connexion" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                        </div>
                    </div>
</template>

<script setup lang="ts">


import { getCsrfToken,AskCsrfToken,setCookie } from "../scripts/token";
import {Config} from "../scripts/config";
import { Modal } from 'bootstrap';
import { onMounted } from "vue";

const submit_connexion_form = async () => {
};

const closeModal = () => {
    const btn_dismiss = document.getElementById("btn_dismiss_modal_connexion");
    btn_dismiss.click();
}

onMounted( () => {
  const modal = new Modal(document.getElementById('modal_connexion',{
  keyboard: true
}));

});


</script>

<style scoped lang="scss">


</style>