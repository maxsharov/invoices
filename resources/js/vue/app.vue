<template>
    <div class="container">
        <h1 class="text-center display-4">Invoices</h1>
        <!-- login form -->
        <div class="row mt-4" v-if="!invoices.length">
            <div class="col-6 offset-3">
                <form action="#" @submit.prevent="handleLogin">
                    <h3>Admin sign in</h3>
                    <div class="form-row">
                        <input
                            type="text"
                            name="email"
                            class="form-control"
                            v-model="formData.email"
                            placeholder="Email Address"
                        />
                    </div>
                    <div class="form-row">
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            v-model="formData.password"
                            placeholder="Password"
                        />
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn btn-primary">
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- add invoice form -->
        <add-item-form v-if="invoices.length" />
        <!-- invoices list -->
        <div class="row mt-4" v-if="invoices.length">
            <div class="col-6 offset-3">
                <h3>All invoices</h3>
                <div class="list-group invoices-list">
                    <div
                        class="invoice list-group-item"
                        v-for="(invoice, index) in invoices"
                        :key="index"
                    >
                        <div class="d-flex justify-content-between">
                            <strong v-text="invoice.name"></strong>
                            <strong v-text="'$' + invoice.amount"></strong>
                        </div>
                        <div>
                            <span>Payment status: </span>
                            <strong
                                v-text="
                                    invoice.is_payed ? 'payed' : 'not payed'
                                "
                            ></strong>
                        </div>
                        <div>
                            <button
                                class="btn btn-secondary"
                                @click="sendEmail(invoice.id)"
                                v-if="!invoice.is_payed"
                            >
                                send email
                            </button>
                        </div>
                    </div>
                </div>

                <button @click="getInvoices()">update</button>
            </div>
        </div>
    </div>
</template>

<script>
import addItemForm from "./addItemForm";

export default {
    components: {
        addItemForm
    },
    data() {
        return {
            invoices: [],
            formData: {
                email: "",
                password: ""
            }
        };
    },
    methods: {
        handleLogin() {
            // send axios request to the login route
            axios.get("/sanctum/csrf-cookie").then(response => {
                axios.post("login", this.formData).then(response => {
                    // console.log(response);
                    this.getInvoices();
                });
            });
        },
        getInvoices() {
            axios.get("/api/items").then(response => {
                console.log(response);
                this.invoices = response.data;
            });
        },
        sendEmail(id) {
            axios.get("/api/item/sendEmail/" + id).then(response => {
                console.log(response);
            });
        }
    }
};
</script>

<style>
.form-row {
    margin-bottom: 8px;
}
</style>
