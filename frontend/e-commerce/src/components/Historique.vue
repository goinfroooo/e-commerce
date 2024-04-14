<template>

    <section id="historique">
        <div class="container-fluid ps-2 m-3 bg-light">
            <div v-if="!profil" class="spinner-border " role="status">
                <span class="visually-hidden ">Loading...</span>
            </div>
            <div v-else>
            
                    
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