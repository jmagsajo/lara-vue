<template lang="html">

    <div class="modal fade" id="programCreateModal">
        <div class="modal-dialog modal-lg">
             <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Create Program</h4>
                </div>

                <form v-on:submit.prevent="submit" class="form-horizontal">
                    <div class="modal-body">

                        <div class="alert alert-danger create-program-validation">
                            <ul>
                                <li v-for="validation in validations">
                                    {{ validation }}
                                </li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="program_name" class="col-sm-2 control-label">Program name<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control program_name" v-model="program.program_name" id="program_name" placeholder="Program name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <textarea v-model="program.description" name="description" class="form-control description" id="description" rows="3" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="thumbnail_url" class="col-sm-2 control-label">Thumbnail url<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" v-model="program.thumbnail_url" class="form-control thumbnail_url" id="thumbnail_url" placeholder="Thumbnail url">
                            </div>
                        </div>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" >Create</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


</template>

<script>
    import {bus} from '../../app';
    export default {
        data: function(){
            return {
                program:{
                    program_name : '',
                    description : '',
                    thumbnail_url : '',
                },
                validations:[]
            }
        },
        methods:{
            submit: function(){

                this.$http.post('program/create', this.program).then(function( response ){
                    var checker = response.body.validator;

                    if( $.trim( checker ) == "" ){

                        bus.$emit('program-created', response);

                        $('#programCreateModal').modal('hide');

                        this.program.program_name = '';
                        this.program.description = '';
                        this.program.thumbnail_url = '';

                    }else{

                        this.validations = checker;
                        $('.create-program-validation').show();
                        setTimeout(function(){
                            $('.create-program-validation').fadeOut();
                        },5000);

                    }

                });

            }
        }
    }
</script>

<style>
    .create-program-validation{
        display:none;
    }
</style>
