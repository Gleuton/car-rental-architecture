import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import LoginComponent from '@modules/auth/pages/Login.vue';
import HomeComponent from '@modules/home/pages/Home.vue';
import BrandComponent from '@modules/brand/pages/Brand.vue';
import CarModels from "@modules/car-models/pages/CarModels.vue";
import ModalComponent from '@shared/components/Modal.vue';

app.component('modal-component', ModalComponent);
app.component('login-component', LoginComponent);
app.component('home-component', HomeComponent);
app.component('brand-component', BrandComponent);
app.component('car-model-component', CarModels);

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });


app.mount('#app');
