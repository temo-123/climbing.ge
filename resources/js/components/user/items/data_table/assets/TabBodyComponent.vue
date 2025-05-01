<template>
    <tbody>
        <tr :class="danger_color"
            v-for="(datas, datas_key) in tab_data.data" :key="datas_key"
        >
            <td style="text-align: center">
                <input type="checkbox" />
            </td>

            <td
                v-for="(b, b_key) in tab_data.tab.body" :key="b_key"
                v-if="tab_data.tab.perm[b_key][0] == 'no' ||
                    $can(
                        tab_data.tab.perm[
                            b_key
                        ][1],
                        tab_data.tab.perm[
                            b_key
                        ][0]
                    )
                "
            >
                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <span v-if="b[0] == 'data'">
                    <tabDataItem 
                        :data_item_prop="b"
                        :data_prop="datas"
                    />
                </span>

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <span
                    v-else-if="b[0] == 'data_action_id'"
                    @click="send_action_to_tab_with_option(b[b.length-1], datas.id)"
                    class="cursor_pointer"
                >
                    <tabDataItem 
                        :data_item_prop="b"
                        :data_prop="datas"
                    />
                </span>

                <!-- {{ b.length-1 }} -->

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <span
                    v-else-if="b[0] == 'data_action'"
                    @click="send_action_to_tab(b[2])"
                    class="cursor_pointer"
                >
                    <tabDataItem 
                        :data_item_prop="b"
                        :data_prop="datas"
                    />
                </span>

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <button
                    v-else-if="b[0] == 'action_fun'"
                    type="button"
                    @click="send_action_to_tab(b[1])"
                    :class="b[2]"
                >
                    <span v-html="b[3]"></span>
                </button>

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <span v-else-if="b[0] == 'stars'">
                    <starsReiting
                        :reviews_count_prop="1"
                        :reviews_stars_prop="
                            datas[b[1][0]][b[1][1]]
                        "
                        :rewiew_count_text_prop="false"
                    />
                </span>

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <a
                    v-else-if="b[0] == 'action_url'"
                    :href="b[1]"
                    :class="b[2]"
                >
                    <span v-html="b[3]"></span>
                </a>

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <router-link
                    v-else-if="b[0] == 'action_router' && typeof b[4] == 'object'"
                    :class="b[2]"
                    :to="{
                        name: b[1],
                        params: { id: datas[b[4][0]][b[4][1]] },
                    }"
                >
                    <span v-html="b[3]"></span>
                </router-link>

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <router-link
                    v-else-if="b[0] == 'action_router'"
                    :class="b[2]"
                    :to="{
                        name: b[1],
                        params: { id: datas.id },
                    }"
                >
                    <span v-html="b[3]"></span>
                </router-link>

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <!-- <span
                    v-else-if="b[0] == 'data_action_id'"
                    @click="send_action_to_tab_with_option(b[2], datas['id'])"
                    class="cursor_pointer"
                >
                    <tabDataItem 
                        :data_item_prop="b"
                        :data_prop="datas"
                    />
                </span> -->

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', [['user', 'id'], ['product', 'id']]], -->
                <!-- <button
                    v-else-if="b[0] == 'action_fun_id' && typeof b[4] == 'object' && (typeof b[4][0] == 'object' && typeof b[4][1] == 'object')"
                    type="button"
                    @click="send_action_to_tab_with_option(b[1], [
                                                                datas[b[4][0][0]][b[4][0][1]],
                                                                datas[b[4][1][0]][b[4][1][1]]
                                                            ])"
                    :class="b[2]"
                >
                    <span v-html="b[3]"></span>
                </button> -->

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <button
                    v-else-if="b[0] == 'action_fun_id' && typeof b[4] == 'object'"
                    type="button"
                    @click="send_action_to_tab_with_option(b[1], datas[b[4][0]][b[4][1]])"
                    :class="b[2]"
                >
                    <span v-html="b[3]"></span>
                </button>

                <!-- ['action_fun_id', 'fun_name', 'button class', 'title or html object', ['user', 'id']], -->
                <button
                    v-else-if="b[0] == 'action_fun_id'"
                    type="button"
                    @click="send_action_to_tab_with_option(b[1], datas.id)"
                    :class="b[2]"
                >
                    <span v-html="b[3]"></span>
                </button>
            </td>
        </tr>
    </tbody>
</template>

<script>
import starsReiting from "../../../../global_components/StarReitingShowComponent.vue";
import tabDataItem from"./DataComponent.vue";

export default {
    components: {
        starsReiting,
        tabDataItem,
    },
    props: [
        "body_data_prop"
    ],
    data(){
        return{
            tab_data: this.body_data_prop,
            danger_color: "",
        }
    },
    watch: {
        body_data_prop: function(){
            this.tab_data = this.body_data_prop
        },
    },
    mounted() {
        this.tab_data = this.body_data_prop
    },
    methods: {
        send_action_to_tab_with_option(emit_fun, sending_id) {
            this.$emit('action_for_perent_component_with_option', [emit_fun, sending_id]);
        },
        send_action_to_tab(emit_fun) {
            this.$emit('action_for_perent_component', [emit_fun]);
        },
    }
}
</script>
