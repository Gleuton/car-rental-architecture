import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import LoginComponent from './components/Login.vue';
import HomeComponent from './components/Home.vue';
import BrandComponent from './components/Brand.vue';
import TableBrandsComponent from "./components/TableBrands.vue";
import ModalComponent from "./components/Modal.vue";

app.component('login-component', LoginComponent);
app.component('home-component', HomeComponent);
app.component('brand-component', BrandComponent);
app.component('table-brands-component', TableBrandsComponent);
app.component('modal-component', ModalComponent);

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });


app.mount('#app');
