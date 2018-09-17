<template lang="html">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Program name</th>
            <th>Slug</th>
            <th>Description</th>
            <th>Thumbnail url</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="program-index">
            <tr v-for="program in programs">
                <td width="100">{{ program.program_name }}</td>
                <td>{{ program.slug }}</td>
                <td width="500">{{ program.description }}</td>
                <td>
                    <img v-bind:style="styleObject" v-bind:src="program.thumbnail_url" v-bind:alt="program.thumbnail_url">
                </td>
                <td>
                    <button type="button" class="btn btn-primary" v-on:click="updateProgram( program )" >
                        <span class="fa fa-pencil-square"></span>
                    </button>
                    <button type="button" class="btn btn-danger" v-on:click="deleteProgram( program )">
                        <span class="fa fa-trash"></span>
                    </button>
                </td>

            </tr>
        </tbody>
    </table>
</template>

<script>
    import {bus} from '../../app';
    export default {
        data: function(){
            return {
                programs:[],
                token:'',
                styleObject: {
                    width:'50px'
                }
            }
        },
        methods: {
            fetchToken : function(){
                let that = this;
                return $.get(
                    '/api/getToken',
                    function(data){
                        that.token = data.access_token;
                    }
                );
            },
            fetchUsers: function(){
                let that = this;
                that.fetchToken().then(function(){
                    that.http.get('api/users',{
                        headers: {
                            Authorization: 'Basic ' + that.token
                        }
                    }).then(function(response) {
                        console.log(response);
                    });
                });
            },
            deleteProgram : function( program ){

                var r = confirm('are you sure you want to delete this program?');
                if( r == true ) {
                    this.$emit( 'delete-program', program );
                }
            },
            updateProgram : function( program ){
                bus.$emit( 'update-program', program );
                $('#programUpdateModal').modal('show');
            }
        },
        created: function () {

            this.fetchPrograms();

            //CREATE
            bus.$on('program-created',( data ) =>{
                this.programs.push( data.body.program );
            });

            //DELETE
            this.$on('delete-program', (program) => {

                this.$http.get('program/delete/'+ program.id ).then( function(response) {

                    var index = this.programs.indexOf(program);
                    this.programs.splice(index, 1);

                });

            });

            //PAGINATION
            bus.$on('program-pagination',( data ) =>{
                this.programs = data;
            });

            //UPDATE
            bus.$on('program-updated',( data ) =>{
                var program = data.body.program;
                var index = this.programs.findIndex((obj => obj.id == program.id));
                this.programs[index].program_name = program.program_name;
                this.programs[index].slug = program.slug;
                this.programs[index].description = program.description;
                this.programs[index].thumbnail_url = program.thumbnail_url;
            });
        }
    }
</script>
