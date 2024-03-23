<template>


  <div class="container mt-5" >
    <div v-if="!article" class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    <div v-else class="card">
        <img :src="Config.backendConfig.apiUrl+article.img_path" class="card-img-top" :alt="article.short_description">
        <div class="card-body">
            <h5 class="card-title">{{article.nom}}</h5>
            <p class="card-text">{{ article.description }}</p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Prix : {{formatPrice(article.prix)}}</li>
        </ul>
        <div class="card-body">
            <a href="#" class="card-link">Acheter</a>
        </div>
    </div>
  </div>

</template>
  
<script setup lang="ts">
  import { ref,onMounted,defineProps } from 'vue';
  import { getCsrfToken,getUserToken,AskCsrfToken} from "../scripts/token";
  import Config from "../scripts/config";
  import { useRoute } from 'vue-router';
  import {formatPrice} from "../scripts/commun";

  const article = ref(null);

  const get_article_data = async (token) => {
    console.log(token);
    try {
      //const articleId = this.$route.params.id;
      
      const route = "/article";
      let options = {
          method: 'POST',
          headers: {
              "X-CSRF-TOKEN": getCsrfToken(),
              "Content-Type": "application/json",
          },
          body: JSON.stringify({
            "article_token": token,
          }),
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
          alert("erreur : veuillez contacter l'administrateur du site")
      }

  }

onMounted( async () => {
  AskCsrfToken();
  const route = useRoute();
  const articleToken = route.params.token; 
  article.value = await get_article_data(articleToken);
});





  </script>
  
  <style scoped lang="scss">
  
  
  
  </style>
  