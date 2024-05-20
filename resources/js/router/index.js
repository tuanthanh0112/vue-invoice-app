import { createRouter, createWebHistory } from 'vue-router';

import invoicesIndex from '../components/invoices/index.vue';
import invoiceNew from '../components/invoices/new.vue'
import notFound from '../components/NotFound.vue';


const routes = [
    {
        path: '/',
        component: invoicesIndex
    },
    {
        path: '/invoice/new',
        component: invoiceNew
    },
    {
        path: '/:patMatch(.*)*',
        component: notFound
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
