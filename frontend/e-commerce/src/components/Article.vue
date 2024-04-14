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
        <div class="card-body d-flex justify-content-center align-items-center">
          
          <input v-if="article.stock!=0" class="form-control me-3" id="qte" name="qte" type="number" min="0" :max="article.stock" step="1" value="1" style="width: 10%;">
          <button v-if="article.stock!=0"
            id="add-to-cart-btn"
            href="#"
            class="card-link btn btn-primary me-3"
            @click="add_to_cart">
            Ajouter au panier
          </button>
          <p class="text-danger p-0 m-0">{{ article.stock }} article(s) restants</p>
          
        </div>
    </div>
  </div>

</template>
  
<script setup lang="ts">
  import { ref,onMounted } from 'vue';
  import { getCsrfToken,getUserToken,AskCsrfToken} from "../scripts/token";
  import Config from "../scripts/config";
  import { useRoute } from 'vue-router';
  import {formatPrice,get_article_data} from "../scripts/commun";
  import {Popover} from "bootstrap";
  const article = ref(null);

  const showPopover = (event) => {
    const pop = new Popover(event.target, { placement: 'right', trigger: 'manual', content: "L'article a bien été ajouté au panier"});
    pop.show();
    setTimeout(() => {
        pop.hide();
    }, 2000);
};



const add_to_cart = async (event) => {

  
    try {
      const user_token = getUserToken();

      if (!user_token) {
        alert ("veuillez vous connectez ou creer un compte");
        return -1;
      }
      
      const route = "/cart/add";
      let options = {
          method: 'POST',
          headers: {
              "X-CSRF-TOKEN": getCsrfToken(),
              "Content-Type": "application/json",
          },
          body: JSON.stringify({
            "article_token": article.value.token,
            "user_token": user_token,
            "qte": document.getElementById("qte").value,
            
          }),
      }
      console.log(options);

      
          const response = await fetch(Config.backendConfig.apiUrl + route, options);
          if (!response.ok) {
              throw new Error('La requête a échoué.');
          }
          const data = await response.json();
          article.value = await get_article_data(article.value.token);
          showPopover(event);
          return data.message==="sucess" ? 0 : -1;
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
  console.log(article.value);
  
});



</script>

<style scoped lang="scss">


</style>
  