<template>

</template>

<script setup>
import { onMounted,ref,computed} from 'vue';
import { formatPrice } from '../scripts/commun';
import Config from "../scripts/config";
import { getCsrfToken,AskCsrfToken,getUserToken,getProfil } from "../scripts/token";
import { Modal } from 'bootstrap';
import adress_form from "./subcomponents/Adress_form.vue"


</script>

<style scoped>


</style>