<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10"> <!-- Utilisez la colonne Bootstrap pour les éléments du panier -->
                <div class="card m-2 mb-0">
                    <div class="card-body">
                        <h5 class="card-title">Panier</h5>
                        <div class="container">
                            <div class="border-bottom border-3 border-light border-top-0 border-start-0 border-end-0 my-1 py-1 d-flex row">
                                <div class="me-3 col-1 d-flex align-items-center justify-content-center"></div>
                                <div class="me-3 col-4 d-flex align-items-center justify-content-center"></div>
                                <div class="me-3 col-2 d-flex align-items-center justify-content-center"></div>
                                <div class="me-3 col-2 d-flex align-items-center justify-content-center">Prix</div>
                                <div class="me-3 col-1 d-flex align-items-center justify-content-center">Quantité </div>
                                <div class="me-3 col-1 d-flex align-items-center justify-content-center">Supprimer </div>

                            </div>
                            <div class="border-bottom border-3 border-light border-top-0 border-start-0 border-end-0 my-1 py-1 d-flex row"  v-for="(cart,index) in carts" :key="index">
                                <div class="me-3 col-1 d-flex align-items-center justify-content-center"><input type="checkbox" :id="'checkbox_'+cart.article_token" class="checkbox_panier" checked="true" @change="put_in_standby(index)"></div>
                                <div class="me-3 col-4 d-flex align-items-center justify-content-center"><router-link :to="'/article/' + cart.article_token"><img :src="Config.backendConfig.apiUrl+cart.img_path" class="card-img-top w" :alt="cart.short_desc"></router-link></div>
                                <div class="me-3 col-2 d-flex align-items-center justify-content-center"><router-link class="nav-link" :to="'/article/' + cart.article_token">{{ cart.nom }}</router-link></div>
                                <div class="me-3 col-2 d-flex align-items-center justify-content-center">{{ formatPrice(cart.prix) }}</div>
                                <div class="me-3 col-1 d-flex align-items-center justify-content-center"><input type="number" v-model=" cart.qte" class="form-control w-100 test" min="1" step="1"  @change="update_qte(cart.article_token)"> </div>
                                <div class="me-3 col-1 d-flex align-items-center justify-content-center"><button class="btn" @click="remove_article_from_cart(cart.article_token)"><i class="fa-solid fa-trash"></i></button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2"> <!-- Utilisez la colonne Bootstrap pour le menu -->
                <!-- Composant de paiement -->
                <div class="position-fixed mt-2" style="width: 20%;">
                    <Paiement :carts="carts" ></Paiement>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
    import { onMounted,ref,computed} from 'vue';
    import {formatPrice, get_cart} from '../scripts/commun';
    import Config from "../scripts/config";
    import { AskCsrfToken,getCookie } from "../scripts/token";
    import "https://kit.fontawesome.com/3467c927e1.js";
    import Paiement from "./subcomponents/panier_side_menu.vue";
    
    const carts = ref([]);


const update_qte = async (article_token,$event) => {

  
try {

  const user_token = getCookie("USER-TOKEN");

  if (!user_token) {
    alert ("veuillez vous connectez ou creer un compte");
    return -1;
  }
  
  const route = "/cart/update_qte";
  let options = {
      method: 'POST',
      headers: {
          "X-CSRF-TOKEN": getCookie("X-CSRF-TOKEN"),
          "Content-Type": "application/json",
      },
      body: JSON.stringify({
        "article_token": article_token,
        "user_token": user_token,
        "qte": event.target.value
        
      }),
  }
  console.log(options);

  
      const response = await fetch(Config.backendConfig.apiUrl + route, options);
      if (!response.ok) {
          throw new Error('La requête a échoué.');
      }
      const data = await response.json();
      console.log ("wesh");
      console.log(data);
      return data.message==="sucess" ? 0 : -1;
  } catch (error) {
      console.error("Erreur lors de l'envoi du formulaire:", error);
      alert("erreur : veuillez contacter l'administrateur du site")
  }

}

const remove_article_from_cart = async (article_token) => {
        
    try {
        const user_token = getCookie("USER-TOKEN");

    if (!user_token) {
        alert ("veuillez vous connectez ou creer un compte");
        return -1;
    }

    const route = "/cart/remove_article";
    let options = {
        method: 'POST',
        headers: {
            "X-CSRF-TOKEN": getCookie("X-CSRF-TOKEN"),
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            "user_token": user_token,
            "article_token":article_token,
        }),
    }
    console.log(options);


        const response = await fetch(Config.backendConfig.apiUrl + route, options);
        if (!response.ok) {
            throw new Error('La requête a échoué.');
        }
        const data = await response.json();
        console.log(data);
        carts.value = await get_cart();
        return 0;
    } catch (error) {
        console.error("Erreur lors de l'envoi du formulaire:", error);
        alert("erreur : veuillez contacter l'administrateur du site")
    }

}

const put_in_standby = (index,$event) => {

    carts.value[index].standby=!event.target.checked;
}


onMounted( async () => {
    AskCsrfToken();
    carts.value = await get_cart();
    for (let cart of carts.value) {
        cart["standby"]=false;
    }

});


</script>

<style scoped>

    .test {
        min-width: 70px;
    }
</style>