require("./bootstrap");

import Vue from "vue";
import App from "./vue/app";
// import SecretComponent from "./vue/SecretComponent";

import { library } from "@fortawesome/fontawesome-svg-core";
import { faPlusSquare, faTrash } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faPlusSquare, faTrash);

Vue.component("font-awesome-icon", FontAwesomeIcon);
// Vue.component("secret-component", SecretComponent);

const app = new Vue({
    el: "#app",
    components: { App }
});
