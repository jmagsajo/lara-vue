<template lang="html">

    <div class="row">

        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title clearfix" v-bind:style="styleObject">
                    <div class="btn-group pull-right">
                        <a class="btn btn-success" id="user-create" v-on:click="openModal()">Create User</a>
                    </div>
                </div>

                <div class="box">
                    <user-index></user-index>
                    
                    <pagination></pagination>
                    
                </div>

            </div>
        </div>

        <user-create></user-create>
        <user-update></user-update>

    </div>

</template>

<script>
    import {bus} from '../../app';
    import index from './Index.vue';
    import create from './Create.vue';
    import update from './Update.vue';
    import pagination from './Pagination.vue';

    export default {
        data: function(){
            return {
                styleObject: {
                }
            }
        },
        methods: {
            openModal : function(){
                $('#userCreateModal').modal();
            }
        },
        components : {
            'user-index' : index,
            'user-create' : create,
            'user-update' : update,
            'pagination' : pagination,
        }
    }
</script>
