<template>
    <div class="toolbar">
        <button type="button" class="btn btn-danger btn-block" @click.prevent="$emit('reset')">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>

        <button type="button" class="btn btn-warning btn-block" @click.prevent="$emit('undo')" :disabled="historyLength === 0">
            <i class="fa fa-undo" aria-hidden="true"></i> {{ historyLength > 0 ? '(' + historyLength + ')' : '' }}
        </button>

        <button type="button" class="btn btn-warning btn-block" @click.prevent="$emit('redo')" :disabled="redoLength === 0">
            <i class="fa fa-repeat" aria-hidden="true"></i> {{ redoLength > 0 ? '(' + redoLength + ')' : '' }}
        </button>

        <hr />

        <button type="button" class="btn btn-primary btn-block but_action" @click.prevent="$emit('line')" v-if="action === 1">
            <i class="fa fa-pencil" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn btn-primary btn-block" @click.prevent="$emit('line')" v-else>
            <i class="fa fa-pencil" aria-hidden="true"></i>
        </button>

        <button type="button" class="btn btn-primary btn-block but_action" @click.prevent="$emit('point')" v-if="action === 2">
            <i class="fa fa-circle" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn btn-primary btn-block" @click.prevent="$emit('point')" v-else>
            <i class="fa fa-circle" aria-hidden="true"></i>
        </button>

        <button type="button" class="btn btn-primary btn-block but_action" @click.prevent="$emit('number')" v-if="action === 3">
            <i class="fa fa-pencil" aria-hidden="true"></i>
             +
            <i class="fa fa-circle" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn btn-primary btn-block" @click.prevent="$emit('number')" v-else>
            <i class="fa fa-pencil" aria-hidden="true"></i>
             +
            <i class="fa fa-circle" aria-hidden="true"></i>
        </button>

        <button type="button" class="btn btn-primary btn-block but_action" @click.prevent="$emit('rectangle')" v-if="action === 4">
            <i class="fa fa-square-o" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn btn-primary btn-block" @click.prevent="$emit('rectangle')" v-else>
            <i class="fa fa-square-o" aria-hidden="true"></i>
        </button>

        <button type="button" class="btn btn-success btn-block but_action" @click.prevent="$emit('combined')" v-if="action === 7">
            <i class="fa fa-square-o" aria-hidden="true"></i> + <i class="fa fa-pencil" aria-hidden="true"></i> + <i class="fa fa-circle" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn btn-success btn-block" @click.prevent="$emit('combined')" v-else>
            <i class="fa fa-square-o" aria-hidden="true"></i> + <i class="fa fa-pencil" aria-hidden="true"></i> + <i class="fa fa-circle" aria-hidden="true"></i>
        </button>

        <hr />

        <button type="button" class="btn btn-warning btn-block but_action" @click.prevent="$emit('eraser')" v-if="action === 5">
            <i class="fa fa-eraser" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn btn-warning btn-block" @click.prevent="$emit('eraser')" v-else>
            <i class="fa fa-eraser" aria-hidden="true"></i>
        </button>

        <button type="button" class="btn btn-danger btn-block but_action" @click.prevent="$emit('erase_segment')" v-if="action === 6">
            <i class="fa fa-scissors" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn btn-danger btn-block" @click.prevent="$emit('erase_segment')" v-else>
            <i class="fa fa-scissors" aria-hidden="true"></i>
        </button>

        <hr />

        <button type="button" class="btn btn-info btn-block but_action" @click.prevent="$emit('move')" v-if="action === 8">
            <i class="fa fa-arrows" aria-hidden="true"></i>
        </button>
        <button type="button" class="btn btn-info btn-block" @click.prevent="$emit('move')" v-else>
            <i class="fa fa-arrows" aria-hidden="true"></i>
        </button>
    </div>
</template>

<script>
export default {
    name: "ToolbarComponent",
    props: {
        action: {
            type: Number,
            default: 1
        },
        historyLength: {
            type: Number,
            default: 0
        },
        redoLength: {
            type: Number,
            default: 0
        }
    }
}
</script>

<style scoped>
    .but_action{
        background-color: #a5a6a7;
    }
</style>
