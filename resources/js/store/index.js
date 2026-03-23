import { createStore } from "vuex";
import axios from "axios"

import * as auth_user from "./moduls/auth_user"
import dataTableTabs from "./moduls/dataTableTabs"

export default createStore({
  modules: {
    auth_user,
    dataTableTabs
  }
});

