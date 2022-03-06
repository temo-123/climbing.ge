<template>
    <tbody  v-for="info in this.data.data" :key="info.id">
        <tr >
            <td style='text-align: center;'>
                <input type="checkbox">
            </td>
            <td>|</td>
            <td>{{info.id}}</td>
            <td>|</td>
            <td>{{info.url_title}}</td>
        </tr>
    </tbody>
</template>

<script>
    export default {
        props: [
            'data',
        ],

        data(){
            return {
                radio_bat_size: 0,
            }
        },

        mounted() {
            this.radio_bat_size = (41 * this.tab.id) - 41
        },
    }
</script>

<style scoped>
    .tabs {
        display: flex;
        flex-wrap: wrap;
    }
    .tabs label {
        order: 1;
        display: block;
        padding: 1rem 2rem;
        margin-right: 0.2rem;
        cursor: pointer;
        background: #ccced0;
        font-weight: bold;
        transition: background ease 0.2s;
    }
    /* .tab{
        margin-left: -50%;
    } */
    .tabs .tab {
        order: 99;
        flex-grow: 1;
        width: 100%;
        display: none;
        padding: 1rem;
        background: #fff;
        /* border: 1px solid #ccc!important; */
    }
    .tabs input[type="radio"] {
        display: none;
    }
    .tabs input[type="radio"]:checked + label {
        background: #fff;
        border: 1px solid #ccc !important;
    }
    .tabs input[type="radio"]:checked + label + .tab {
        display: block;
    }

    @media (max-width: 45em) {
        .tabs .tab,
        .tabs label {
            order: initial;
        }
        .tabs label {
            width: 100%;
            margin-right: 0;
            margin-top: 0.2rem;
        }
    }
</style>