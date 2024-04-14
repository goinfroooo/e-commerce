<template>

    <div class="card payment_card p-2">
        <div class="card-body">
            <h4 class="card-title">Total</h4>
            <div class="my-2 rounded-3">({{ qte_tot }} articles) : <span class="fw-bold">{{ prix_total }} €</span></div>
            <button class="btn btn-warning my-2" @click="commander">Passer la commande</button>
        </div>
    </div>
</template>

<script setup lang="ts">

    import { defineProps,computed } from 'vue';
    import { getCsrfToken,getUserToken,AskCsrfToken} from "../scripts/token";
    import Config from "../scripts/config";
    import { useRouter } from 'vue-router';
    import {formatPrice,get_article_data} from "../scripts/commun";
    import {Popover} from "bootstrap";
    import { CommandeStore } from '../../store/commande';
    
    const router = useRouter();
    const props = defineProps({carts: Array });

    const prix_total = computed(()=>{
        let prix_tot=0;
        
        for (let cart of props.carts){
            if (!cart.standby) {
                prix_tot+=cart.prix*cart.qte;
            }
        }
        return prix_tot/100;
    })
    const qte_tot= computed(()=>{
        let qte=0;
        for (let cart of props.carts){
            if (!cart.standby) {
                qte+=cart.qte;
            }
        }
        return qte;
    })
    
    const commander = () => {
        CommandeStore.carts=props.carts;
        router.push({ name: 'Commande'});
    }
</script>
    
<style scoped lang="scss">




</style>
        