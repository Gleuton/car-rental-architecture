import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import LoginComponent from './components/Login.vue';
import HomeComponent from './components/Home.vue';
import BrandComponent from './components/Brand.vue';
import TableBrands from "./components/TableBrands.vue";

app.component('login-component', LoginComponent);
app.component('home-component', HomeComponent);
app.component('brand-component', BrandComponent);
app.component('table-brands-component', TableBrands);

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });


app.mount('#app');
