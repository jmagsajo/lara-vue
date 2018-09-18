<template lang="html">
    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>email</th>
                        <th>username</th>
                        <th>first name</th>
                        <th>last name</th>
                        <th>address</th>
                        <th>post code</th>
                        <th>phone number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="user-index">
                    <tr v-for="user in users">
                        <td>{{ user.email }}</td>
                        <td>{{ user.username }}</td>
                        <td>{{ user.first_name }}</td>
                        <td>{{ user.last_name }}</td>
                        <td>{{ user.address }}</td>
                        <td>{{ user.postcode }}</td>
                        <td>{{ user.phone_number }}</td>
                        <td>
                           <button type="button" class="btn btn-primary" v-on:click="updateUser( user )" >
                               <span class="fa fa-pencil-square"></span>
                           </button>
                           <button type="button" class="btn btn-danger" v-on:click="deleteUser( user )">
                               <span class="fa fa-trash"></span>
                           </button>
                        </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
</template>

<script>
    import {bus} from '../../app';
    export default {
        data: function(){
            return {
                users:[],
                token:'',
                token_type: '',
                styleObject: {
                    width:'50px'
                }
            }
        },
        methods: {
            fetchToken : function(){
                let that = this;
                return this.$http.get('/api/getToken').then(response => response.json())
                .then(json => {
                    that.token = json.access_token;
                    that.token_type = json.token_type;
                });
            },
            fetchUsers: function(){
                let that = this;
                this.fetchToken().then(function(){
                    this.$http.get('api/user/listByPage/1',{
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Authorization': that.token_type + ': ' + that.token
                        }
                    }).then(response => response.json())
                    .then(json => {
                        bus.$emit( 'pagination', json.pagination );
                        that.users = json.data;
                    });
                });
            },
            deleteUser : function( user ){

                var r = confirm('are you sure you want to delete this program?');
                if( r == true ) {
                    this.$emit( 'delete-user', user );
                }
            },
            updateUser : function( user ){
                bus.$emit( 'update-user', user );
                $('#userUpdateModal').modal('show');
            }
        },
        created: function () {
            let that = this;
            that.fetchUsers();

            //CREATE
            bus.$on('user-created',( data ) =>{
                that.users.push( data.data );
            });

            //DELETE
            that.$on('delete-user', (user) => {
                that.fetchToken().then(function(){
                    that.$http.get('api/user/delete/'+ user.id, {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Authorization': that.token_type + ': ' + that.token
                        }
                    }).then( response => {

                        var index = this.users.indexOf(user);
                        that.users.splice(index, 1);

                    });
                });

            });

            //PAGINATION
            bus.$on('user-pagination',( data ) =>{
                that.users = data;
            });

            //UPDATE
            bus.$on('user-updated',( data ) =>{
                var user = data;
                var index = this.users.findIndex((obj => obj.id == user.id));
                this.users[index].email = user.email;
                this.users[index].first_name = program.first_name;
                this.users[index].last_name = program.last_name;
                this.users[index].address = program.address;
                this.users[index].postcode = program.postcode;
                this.users[index].phone_number = program.phone_number;
            });
        }
    }
</script>
