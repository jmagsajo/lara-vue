<template lang="html">

    <div class="modal fade" id="userUpdateModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Update User</h4>
                </div>

                <form v-on:submit.prevent="submit( user.id )" class="form-horizontal">
                    <div class="modal-body">

                        <div class="alert alert-danger update-user-validation">
                            <ul>
                                <li v-for="validation in validations">
                                    {{ validation }}
                                </li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label for="email_update" class="col-sm-2 control-label">email<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control email" v-model="user.email" id="email_update" placeholder="email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="first_name_update" class="col-sm-2 control-label">first name<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                 <input type="text" class="form-control first_name" v-model="user.first_name" id="first_name_update" placeholder="first name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last_name_update" class="col-sm-2 control-label">last name<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                  <input type="text" class="form-control last_name" v-model="user.last_name" id="last_name_update" placeholder="last name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address_update" class="col-sm-2 control-label">address<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea v-model="user.address" name="address" class="form-control address" id="address_update" rows="3" placeholder="address"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="postcode_update" class="col-sm-2 control-label">postcode<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                  <input type="text" class="form-control postcode" v-model="user.postcode" id="postcode_update" placeholder="postcode">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone_number_update" class="col-sm-2 control-label">phone_number<span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                  <input type="text" class="form-control phone_number" v-model="user.phone_number" id="phone_number_update" placeholder="phone_number">
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
                    id : '',
                    email : '',
                    first_name : '',
                    last_name : '',
                    address : '',
                    postcode : '',
                    phone_number : '',
                },
                validations:[]
            }
        },
        created: function(){

            bus.$on('update-user', (user) => {
                this.fetchUser( user )
            });

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
            fetchUser : function( data ){

                this.user.id = data.id;
                this.user.email = data.email;
                this.user.first_name = data.first_name;
                this.user.last_name = data.last_name;
                this.user.address = data.address;
                this.user.postcode = data.postcode;
                this.user.phone_number = data.phone_number;

            },
            submit: function( id ){
                let that = this;

                that.fetchToken().then(function(){
                    that.$http.post('api/user/update/'+id, that.user, {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Authorization': that.token_type + ': ' + that.token
                        }
                    }).then(response => response.json())
                    .then(json => {

                        var checker = json.validation;

                        if( checker == undefined ){

                            bus.$emit('user-updated', json.data);
                            $('#userUpdateModal').modal('hide');

                        }else{

                            that.validations = checker;
                            $('.update-user-validation').show();
                            setTimeout(function(){
                                $('.update-user-validation').fadeOut();
                            },5000);
                        }

                    });
                });

            }
        }
    }
</script>

<style>
    .update-user-validation{
        display:none;
    }
</style>