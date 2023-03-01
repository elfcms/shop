import { createApp } from 'vue'

import TestComponent from './components/Test.vue'
import Ttt from './components/Ttt.vue'

const app = createApp()
app.component('component-products', TestComponent)
app.component('component-ttt', Ttt)
app.mount('#app');
