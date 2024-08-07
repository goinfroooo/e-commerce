<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-lg-10 order-2 order-md-1"> <!-- Utilisez la colonne Bootstrap pour les éléments du panier -->
                <div class="card p-1 p-md-2 m-2 mb-0">
                    <div class="card-body m-0 p-0 p-md-2">
                        <h5 class="card-title">Panier</h5>
                        <div class="container">
                            <div class="border-bottom border-3 border-light border-top-0 border-start-0 border-end-0 my-1 py-1 d-flex row">
                                <div class="me-1 me-md-3 col-1 col-md-1 d-flex align-items-center justify-content-center"></div>
                                <div class="me-1 me-md-3 col-2 col-md-2 d-flex align-items-center justify-content-center"></div>
                                <div class="me-1 me-md-3 col-1 col-md-2 d-flex align-items-center justify-content-center"></div>
                                <div class="me-1 me-md-3 col-3 col-md-2 d-flex align-items-center justify-content-center">Prix</div>
                                <div class="me-1 me-md-3 col-3 col-md-1 d-flex align-items-center justify-content-center">Quantité </div>
                                <div class="me-1 me-md-3 col-1 col-md-1 d-none d-lg-flex align-items-center justify-content-center">Supprimer </div>

                            </div>
                            <div class="border-bottom border-3 border-light border-top-0 border-start-0 border-end-0 my-1 py-1 d-flex row"  v-for="(cart,index) in carts" :key="index">
                                <div class="me-1 me-md-3 col-1 col-md-1 d-flex align-items-center justify-content-center"><input type="checkbox" :id="'checkbox_'+cart.article_token" class="checkbox_panier" :checked="!cart.standby" @change="put_in_standby(index,cart.article_token,$event)"></div>
                                <div class="me-1 me-md-3 col-2 col-md-2 d-flex align-items-center justify-content-center"><router-link :to="'/article/' + cart.article_token"><img :src="Config.backendConfig.apiUrl+cart.img_path" class="card-img-top w" :alt="cart.short_desc"></router-link></div>
                                <div class="me-1 me-md-3 col-1 col-md-2 d-flex align-items-center justify-content-center"><router-link class="nav-link" :to="'/article/' + cart.article_token">{{ cart.nom }}</router-link></div>
                                <div class="me-1 me-md-3 col-3 col-md-2 d-flex align-items-center justify-content-center">{{ formatPrice(cart.prix) }}</div>
                                <div class="me-1 me-md-3 col-3 col-md-1 d-flex align-items-center justify-content-center"><input type="number" v-model=" cart.qte" class="form-control w-100 test" min="1" step="1"  @change="update_qte(cart.article_token,$event)"> </div>
                                <div class="me-1 me-md-3 col-1 col-md-1 d-flex align-items-center justify-content-center"><button class="btn" @click="remove_article_from_cart(cart.article_token)"><i class="fa-solid fa-trash"></i></button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-md-3 col-lg-2 order-1 order-md-2 "> 
                    <div class="paiement w-100 mt-2 mt-md-0 p-2" style="width: 20%;">
                    <Paiement :carts="carts"></Paiement>
                </div>                
            </div>
        </div>  
    </div>
</template>


<script setup lang="ts">
    import { onMounted,ref,Ref} from 'vue';
    import {formatPrice, get_cart} from '../scripts/commun';
    import Config from "../scripts/config";
    import {CartItem} from '../scripts/interface';
    import { AskCsrfToken,getCookie } from "../scripts/token";
    import "https://kit.fontawesome.com/3467c927e1.js";
    import Paiement from "./subcomponents/panier_side_menu.vue";

    
    const carts: Ref<CartItem[] | null> = ref<CartItem[] | null>(null);

const update_qte = async (article_token: string,event: Event) => {

  
try {

    const targetElement = event.target as HTMLInputElement;
        if (targetElement) {
            

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
                "qte": targetElement.value
                
            }),
        }
        //console.log(options);

        const response = await fetch(Config.backendConfig.apiUrl + route, options);
        if (!response.ok) {
            throw new Error('La requête a échoué.');
        }
        const data = await response.json();
        //console.log(data);
        return data.message==="sucess" ? 0 : -1;
        }
    } catch (error) {
        console.error("Erreur lors de l'envoi du formulaire:", error);
        alert("erreur : veuillez contacter l'administrateur du site")
    }

}

const remove_article_from_cart = async (article_token: string) => {
        
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

const put_in_standby = async (index: number,article_token: string,event: Event) => {


    try {
        if (carts.value === null) {
        return -1;
        }
        const targetElement = event.target as HTMLInputElement;
        if (targetElement) {
            const standby = !targetElement.checked;
            carts.value[index].standby=standby;

            const user_token = getCookie("USER-TOKEN");

            if (!user_token) {
            alert ("veuillez vous connectez ou creer un compte");
            return -1;
            }


            const route = "/cart/update_standby";
            let options = {
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": getCookie("X-CSRF-TOKEN"),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                "article_token": article_token,
                "user_token": user_token,
                "standby": standby,
                
                }),
            }
            //console.log(options);


            const response = await fetch(Config.backendConfig.apiUrl + route, options);
            if (!response.ok) {
                throw new Error('La requête a échoué.');
            }
            const data = await response.json();
            //console.log(data);
            return data.message==="sucess" ? 0 : -1;
                
            }

        
    } catch (error) {
        console.error("Erreur lors de l'envoi du formulaire:", error);
        alert("erreur : veuillez contacter l'administrateur du site")
    }
}


onMounted( async () => {
    AskCsrfToken();
    carts.value = await get_cart();
    

});


</script>

<style scoped>

    .test {
        min-width: 70px;
    }

    @media (min-width: 768px) {
        .paiefment {
            position: fixed;
            top: 2em;
            right: 1em;
            width: 20%;
            
            
        }
    }


</style>