import { createRouter, createWebHistory } from 'vue-router';

import invoicesIndex from '../components/invoices/index.vue';
import invoiceNew from '../components/invoices/new.vue'
import invoiceShow from '../components/invoices/showInvoices.vue';
import invoiceEdit from '../components/invoices/editInvoice.vue';
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
        path: '/invoice/show/:id',
        component: invoiceShow,
        props: true
    },
    {
        path: '/invoice/edit/:id',
        component: invoiceEdit,
        props: true
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
