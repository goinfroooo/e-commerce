import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import './style.scss'
import Router from "./router.ts"




createApp(App).use(Router).mount('#app')
