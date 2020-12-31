<template>
    <div class="row mt-4">
        <div class="col-6 offset-3">
            <input type="text" v-model="item.name" placeholder="Name" />
            <input type="text" v-model="item.email" placeholder="Email" />
            <input type="text" v-model="item.amount" placeholder="Amount" />
            <font-awesome-icon
                title="Create Invoice"
                icon="plus-square"
                @click="addItem()"
                :class="[item.name ? 'active' : 'inactive', 'plus']"
            />
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            item: {
                name: "",
                email: ""
            }
        };
    },
    methods: {
        addItem() {
            if (this.item.name == "") {
                return;
            }

            axios
                .post("api/item/store", {
                    item: this.item
                })
                .then(response => {
                    if (response.status === 201) {
                        this.item.name = "";
                        this.item.email = "";
                        this.item.amount = "";
                    }
                })
                .catch(error => console.log(error));
        },
        someMethod() {
            this.$parent.getInvoices();
        }
    }
};
</script>

<style scoped>
input {
    background: #f7f7f7;
    border: 1px solid grey;

    outline: none;
    padding: 5px;
    margin: 10px 0;
    width: 100%;
}
.plus {
    font-size: 20px;
}
.active {
    color: #00ce25;
}
.inactive {
    color: #999999;
}
</style>
