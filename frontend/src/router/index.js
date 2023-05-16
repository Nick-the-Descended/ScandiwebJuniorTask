import {createRouter, createWebHistory} from 'vue-router'
import ProductList from '../components/ProductGrid.vue';
import AddProduct from '../components/AddProduct.vue';

const routes = [
  {
    path: '/',
    name: 'ProductGrid',
    component: ProductList,
  },
  {
    path: '/addProduct',
    name: 'AddProduct',
    component: AddProduct,
  },
];


const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
