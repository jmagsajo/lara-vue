<template lang="html">

    <div class="modal fade" id="userCreateModal">
        <div class="modal-dialog modal-lg">
             <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Create User</h4>
                </div>

                <form v-on:submit.prevent="submit" class="form-horizontal">
                    <div class="modal-body">

                        <div class="alert alert-danger create-user-validation">
                            <ul>
                                <li v-for="validation in validations">
                                    {{ validation }}
                                </li>
                            </ul>
                        </div>
                        
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">username<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control username" v-model="user.username" id="username" placeholder="username">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">email<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control email" v-model="user.email" id="email" placeholder="email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">password<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control password" v-model="user.password" id="password" placeholder="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="col-sm-2 control-label">password confirmation<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control password_confirmation" v-model="user.password_confirmation" id="password_confirmation" placeholder="password confirmation">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="first_name" class="col-sm-2 control-label">first name<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                 <input type="text" class="form-control first_name" v-model="user.first_name" id="first_name" placeholder="first name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="col-sm-2 control-label">last name<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                  <input type="text" class="form-control last_name" v-model="user.last_name" id="last_name" placeholder="last name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="col-sm-2 control-label">address<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea v-model="user.address" name="address" class="form-control address" id="address" rows="3" placeholder="address"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="postcode" class="col-sm-2 control-label">postcode<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                  <input type="text" class="form-control postcode" v-model="user.postcode" id="postcode" placeholder="postcode">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone_number" class="col-sm-2 control-label">phone_number<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                  <input type="text" class="form-control phone_number" v-model="user.phone_number" id="phone_number" placeholder="phone_number">
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
                token:'',
                token_type: '',
                user:{
                    username : '',
                    email : '',
                    first_name : '',
                    last_name : '',
                    address : '',
                    postcode : '',
                    phone_number : '',
                    password : '',
                    password_confirmation : '',
                },
                validations:[]
            }
        },
        methods:{
            fetchToken : function(){
                let that = this;
                return this.$http.get('/api/getToken').then(response => response.json())
                .then(json => {
                    that.token = json.access_token;
                    that.token_type = json.token_type;
                });
            },
            submit: function(){
                let that = this;

                this.fetchToken().then(function(){
                    this.$http.post('api/user/create', that.user, {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Authorization': that.token_type + ': ' + that.token
                        }
                    }).then(response => response.json())
                    .then(json => {
                        var checker = json.validation;

                        if( checker == undefined ){

                            bus.$emit('user-created', json);

                            $('#userCreateModal').modal('hide');

                            that.user.username = '';
                            that.user.email = '';
                            that.user.first_name = '';
                            that.user.last_name = '';
                            that.user.address = '';
                            that.user.postcode = '';
                            that.user.phone_number = '';
                            that.user.password_confirmation = '';
                            that.user.password = '';

                        }else{

                            that.validations = checker;
                            $('.create-user-validation').show();
                            setTimeout(function(){
                                $('.create-user-validation').fadeOut();
                            },5000);

                        }
                    });
                });

            }
        }
    }
</script>

<style>
    .create-user-validation{
        display:none;
    }
</style>
