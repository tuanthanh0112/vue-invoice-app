<script setup>
    import {onMounted, ref} from "vue"
    import {useRouter} from 'vue-router'


    const router = useRouter()

    let invoices = ref([])
    let searchInvoices = ref([])

    onMounted( async () => {
        getInvoices()
    })

    const onShow = (id) => {
        router.push('/invoice/show/' + id)
    }

    const getInvoices = async () => {
        let response = await axios.get("api/get_all_invoice")
        console.log('', response)
        invoices.value = response.data.invoices
    }

    const search = async () => {
        let response = await axios.get('api/search_invoice?s='+searchInvoices.value)
        // console.log('response' , response.data.invoices)
        invoices.value = response.data.invoices
    }

    const newInvoice = async () => {
        let form = await axios.get("api/create_invoice")
        router.push('/invoice/new')
    }
    const deleteInvoice = (id) => {
        console.log('aksdhkasjdjaksd');
        axios.get('/api/delete_invoice/' + id)
        router.push('/')
    }
    const editInvoice = (id) => {
        router.push('/invoice/edit/' + id)
    }
</script>

<template>
    <div class="container">
        <div class="invoices">

        <div class="card__header">
            <div>
                <h2 class="invoice__title">Invoices</h2>
            </div>
            <div>
                <a class="btn btn-secondary" @click="newInvoice">
                    New Invoice
                </a>
            </div>
        </div>

        <div class="table card__content">
            <div class="table--filter">
                <span class="table--filter--collapseBtn ">
                    <i class="fas fa-ellipsis-h"></i>
                </span>
                <div class="table--filter--listWrapper">
                    <ul class="table--filter--list">
                        <li>
                            <p class="table--filter--link table--filter--link--active">
                                All
                            </p>
                        </li>
                        <li>
                            <p class="table--filter--link ">
                                Paid
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table--search">
                <div class="table--search--wrapper">
                    <select class="table--search--select" name="" id="">
                        <option value="">Filter</option>
                    </select>
                </div>
                <div class="relative">
                    <i class="table--search--input--icon fas fa-search "></i>
                    <input class="table--search--input" type="text" placeholder="Search invoice" v-model="searchInvoices" @keyup="search()">
                </div>
            </div>

            <div class="table--heading">
                <p>Stt</p>
                <p>ID</p>
                <p>Date</p>
                <p>Number</p>
                <p>Customer</p>
                <p>Due Date</p>
                <p>Total</p>
                <p>Action</p>
            </div>

            <!-- item 1 -->
            <div class="table--items"  v-for="(item, i ) in invoices" :key="item.id" v-if="invoices.length > 0">
                <p>{{ i+1 }}</p>
                <a href="#" class="table--items--transactionId" @click="onShow(item.id)">#{{ item.id }}</a>
                <p>{{ item.date}}</p>
                <p>{{ item.number}}</p>
                <p v-if="item.customer">
                    {{ item.customer.firstname}}
                </p>
                <p v-else></p>
                <p>{{ item.due_date }}</p>
                <p> $ {{ item.total}}</p>
                <p><a href="" style="text-decoration: none;  border: 1px solid ; background-color: rgb(#e0e0e0, #e0e0e0, #e0e0e0); border-radius: 5px; padding: 5px;" @click="deleteInvoice(item.id)">delete</a> <a href="" style="border: 1px solid ; background-color: rgb(red, green, blue); border-radius: 5px; padding: 5px; text-decoration: none;" @click="editInvoice(item.id)">edit</a></p>
            </div>
            <div class="table--items" v-else>
                <p>Invoice not found</p>
            </div>
        </div>

    </div>
    </div>
</template>
