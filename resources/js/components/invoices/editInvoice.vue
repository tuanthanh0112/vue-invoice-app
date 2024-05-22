<template>
    <div class="container">
        <div class="invoices">
        
            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Edit Invoice</h2>
                </div>
                <div>
                    
                </div>
            </div>
    
            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="form.customer_id">
                            <option disabled>Select customer</option>
                            <option :value="customer.id" v-for="customer in allcustomers" :key="customer.id">{{
                                customer.firstname }}</option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p> 
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date"> <!---->
                        <p class="my-1">Due Date</p> 
                        <input id="due_date" type="date" class="input" v-model="form.due_date">
                    </div>
                    <div>
                        <p class="my-1">Numero</p> 
                        <input type="text" class="input" v-model="form.number"> 
                        <p class="my-1">Reference(Optional)</p> 
                        <input type="text" class="input" v-model="form.reference">
                    </div>
                </div>
                <br><br>
                <div class="table">
    
                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>
        
                    <!-- item 1 -->
                    <div class="table--items2" v-for="(item, i) in form.invoice_items" :key="item.id">
                        <p v-if="item.product"># {{ item.product.item_code }} {{ item.product.descripction }}</p>
                        <p v-else>
                            #{{ item.item_code }} {{ item.descripction }}
                        </p>
                        <p>
                            <input type="text" class="input" v-model="item.unit_price">
                        </p>
                        <p>
                            <input type="text" class="input" v-model="item.quantity">
                        </p>
                        <p>
                            $ {{ item.quantity * item.unit_price }}
                        </p>
                        <p style="color: red; font-size: 24px;cursor: pointer;" @click="deleteInvoice(item.id, i)">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal" @click="openModal()">Add New Line</button>
                    </div>
                </div>
    
                <div class="table__footer">
                    <div class="document-footer" >
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions"></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>$ {{ subTotal() }}</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input" v-model="form.discount">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ {{Total()}}</span>
                        </div>
                    </div>
                </div>
    
               
            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>
                    
                </div>
                <div>
                    <a class="btn btn-secondary">
                        Save
                    </a>
                </div>
            </div>
            
        </div>

        <!--==================== add modal items ====================-->
    <div class="modal main__modal " :class="{ show: showModal }">
        <div class="modal__content">
            <span class="modal__close btn__close--modal" @click="closeModal()">×</span>
            <h3 class="modal__title">Add Item</h3>
            <hr><br>
            <div class="modal__items">
                <ul style="list-style: none;">
                    <li v-for="(item, i) in listProducts" :key="item.id"
                        style="display: grid; grid-template-columns: 30px 350px 15px; align-items: center; padding-bottom:5px ;">
                        <p>{{ i + 1 }}</p>
                        <a href="#">{{ item.item_code }} {{ item.descripction }}</a>
                        <button @click="addCart(item)"
                            style="border: 1px solid #e0e0e0; width: 35px; cursor: pointer;">+</button>
                    </li>
                </ul>
            </div>
            <br><hr>
            <div class="model__footer">
                <button class="btn btn-light mr-2 btn__close--modal" @click="closeModal()">
                    Cancel
                </button>
                <button class="btn btn-light btn__close--modal ">Save</button>
            </div>
        </div>
    </div>
    
    <br><br><br>
    </div>
</template>

<script setup>

    import axios from 'axios';
import {onMounted, ref} from 'vue';

    let form = ref({id: ''})

    let allcustomers = ref()
    let customer_id = ref()
    const showModal = ref(false);
    const hideModal = ref(true);
    let listProducts = ref([]);
    let listCart= ref([])

    const props = defineProps({
        id:{
            type:String,
            default: ''
        }
    });

    

    onMounted( async () => {
        getInvoice()
        getAllCustomers()
        getProducts()
        getProducts()
        Total()
    })

    const subTotal = () => {
        let total = 0;
        if(form.value.invoice_items){
            form.value.invoice_items.map((data) => {
                total = total + (data.quantity * data.unit_price)
            })
        }

        return total;
    }

    const Total = () => {
        if(form.value.invoice_items) {
            return subTotal() - form.value.discount
        }
    }

    const deleteInvoice = (id, i) => {
        form.value.invoice_items.splice(i,1)
        if(id != undefined){
            axios.get('/api/delete_invoice_items' + id)
        }
    }
    const getProducts = async () => {
        let response = await axios.get('/api/products')
        listProducts.value = response.data.products
    }

    const addCart = (item) => {
        const itemcart = {
            product_id: item.id,
            item_code: item.item_code,
            descripction: item.descripction,
            unit_price: item.unit_price,
            quantity: item.quantity
        }
        form.value.invoice_items.push(itemcart)

        closeModal()
    }

    const openModal = () => {
        showModal.value = !showModal.value
    }

    const closeModal = () => {
        showModal.value = !hideModal.value
    }

    const getAllCustomers = async () => {
        let response = await axios.get('/api/customers')
        allcustomers.value = response.data.customers
    }

    const getInvoice = async () => {
        let response = await axios.get(`/api/edit_invoice/${props.id}`)
        form.value = response.data.invoice
        console.log('test_edit', response.data.invoice);
    }

    const onEdit = (id) => {
        if(form.value.invoice_items.lenght >= 1) {
            let subtotal = 0;
            subtotal = subTotal();

            let total = 0;
            total = Total();

            const form_data = new FormData();
            form_data.append('invoice_item', JSON.stringify(form.value.invoice_items))
            form_data.append('customer_id', customer_id.value)
            form_data.append('date', form.value.date)
            form_data.append('due_date', form.value.due_date)
            form_data.append('number', form.value.number)
            form_data.append('reference', form.value.reference)
            form_data.append('discount', form.value.discount)
            form_data.append('subtotal', subtotal)
            form_data.append('total', total)
            form_data.append('terms_and_conditions', form.value.terms_and_conditions)

            axios.post(`/api/update_invoice/${form.value.id}`, form_data)

            form.value.invoice_items = []

            router.push('/')
        }
    }
</script>