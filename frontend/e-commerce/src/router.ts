import { createRouter, createWebHistory } from 'vue-router'
import Home from './components/Home.vue';
import Article from './components/Article.vue';
import Registration from './components/Registration.vue';


const routes = [
  {path: '/',name: 'Home',component: Home},
  {path: '/article/:token',name: 'Article',component: Article, props: true},
  {path: '/registration',name: 'Registration',component: Registration},

]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
