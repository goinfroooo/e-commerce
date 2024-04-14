<template>

<section id="articles_preview" class="offset-top">
  <div class="container">
    <div class="row d-flex justify-content-center">
      <div v-if="!all_article" class="spinner-border " role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div class="col-3 my-3" v-for="article in all_article">
        <div class="card p-3">
          <img :src="Config.backendConfig.apiUrl+article.img_path" class="card-img-top" :alt="article.short_description">
          <div class="card-body p-1 mb-3">
              <h5 class="card-title">{{article.nom}}</h5>
              <p class="card-text fs-6 overflow-hidden">{{ article.short_description }}</p>
          </div>
          <ul class="list-group list-group-flush">
              <li class="list-group-item">Prix : {{formatPrice(article.prix)}}</li>
          </ul>
          <div class="card-body pb-0">
            <router-link :to="'/article/' + article.token" class="btn btn-primary">Voir l'article</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
  <table >
    
  </table>

</template>

<script setup lang="ts">
import { ref,onMounted,onBeforeUnmount } from 'vue';
import { getCsrfToken,getUserToken,AskCsrfToken} from "../scripts/token";
import Config from "../scripts/config";
import {formatPrice} from "../scripts/commun";

const intervalId = ref(null);
const all_article = ref(null);

const get_all_articles = async () => {

      try {
        const route = "/all_articles";
        let options = {
            method: 'POST',
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                "Content-Type": "application/json",
            },
            body: JSON.stringify({}),
        }

        
            const response = await fetch(Config.backendConfig.apiUrl + route, options);
            if (!response.ok) {
                throw new Error('La requête a échoué.');
            }
            const data = await response.json();
            console.log(data);
            return data;
        } catch (error) {
            console.error("Erreur lors de l'envoi du formulaire:", error);
        }
    
}

const get_data =   async () => {
  await AskCsrfToken();
  all_article.value =  await get_all_articles(); // Appelez retrieve_game_list lorsque le composant est monté
}

// Définir l'intervalle de 5 secondes en millisecondes




onMounted(async () => {
  get_data();
  intervalId.value = setInterval(get_data, 3000);
  
  
});

onBeforeUnmount( () => {
  
  clearInterval(intervalId.value);
});
</script>

<style scoped lang="scss">

.offset-top {

  margin-top: 70px;
}

.card-body {
    max-height: 8rem; /* ou toute autre valeur que vous voulez pour la hauteur maximale */
    overflow: hidden;
}

.card-text {
    max-height: 20%; /* 20% de la hauteur du parent */
    overflow: hidden;
}

</style>
