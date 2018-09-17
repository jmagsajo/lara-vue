<template lang="html">

    <div class="row">

        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title clearfix" v-bind:style="styleObject">
                    <h5>Users</h5>
                    <div class="btn-group pull-right">
                        <a class="btn btn-success" id="user-create">Create User</a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">

                        <user-index></user-index>

                    </div>
                    
                </div>
            </div>
        </div>

       

    </div>

</template>

<script>
    import {bus} from '../../app';
    import index from './Index.vue';

    export default {
        data: function(){
            return {
                styleObject: {
                    "background-color": "#2f4050",
                    "color":"#ffffff"
                }
            }
        },
        components : {
            'user-index' : index
        }
    }
</script>
