import Entry from "./Entry.vue"
import {createApp} from "vue";
import router from "./plugins/router";
import {createPinia} from "pinia";

const pinia = createPinia()

// @ts-ignore
const app = createApp(Entry)
app.use(router)
app.use(pinia)
app.mount("#entry")
