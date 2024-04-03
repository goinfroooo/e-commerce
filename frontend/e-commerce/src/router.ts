import { createRouter, createWebHistory } from 'vue-router'
import Home from './components/Home.vue';
import Article from './components/Article.vue';
import Registration from './components/Registration.vue';
import Profil from "./components/Profil.vue";
import Panier from "./components/Panier.vue";


const routes = [
  {path: '/',name: 'Home',component: Home},
  {path: '/article/:token',name: 'Article',component: Article, props: true},
  {path: '/registration',name: 'Registration',component: Registration},
  {path: '/profil',name: 'Profil',component: Profil},
  {path: '/panier',name: 'Panier',component: Panier},

]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
