import Entry from "./Entry.vue"
import {createApp} from "vue";
import router from "./plugins/router";

const app = createApp(Entry)
app.use(router)
app.mount("#entry")
