<template>
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ modalText }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" @click="confirmModal" class="btn btn-primary" data-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {eventEmitter} from "../../custom";

    export default {
        props: ['modalText'],
        methods: {
            confirmModal () {
                eventEmitter.$emit('confirmModal');
            }
        }
    }
</script>

<style scoped>

</style>