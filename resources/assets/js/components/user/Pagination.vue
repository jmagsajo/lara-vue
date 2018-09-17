<template>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li v-if="prev">
                <a aria-label="Previous" v-on:click="prevPage( prev )">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li v-else class="disabled">
                <a aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li v-for="page in pages" :class="page == active ? 'active' : '' ">
                <a v-on:click="clickPage( page )">{{ page }}</a>
            </li>
            <li v-if="next">
                <a href="#" aria-label="Next" v-on:click="nextPage( next )">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li v-else class="disabled">
                <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    import {bus} from '../../app';
    export default {
        data : function(){
            return {
                pages : '',
                active : '',
                next : '',
                prev : '',
            }
        },
        created : function(){

            bus.$on('pagination', (data) =>{

                this.pages = data.pages;
                this.active = data.active;
                this.next = data.next;
                this.prev = data.prev;

            });

        },
        methods: {
            nextPage : function( page ){

                this.$http.get('program/getPrograms/'+page).then(function( response ){

                    var pagination = response.body.pagination;
                    this.active = pagination.active;
                    this.pages = pagination.pages;
                    this.next = pagination.next;
                    this.prev = pagination.prev;

                    bus.$emit('program-pagination', response.body.programs);

                });

            },
            prevPage : function( page ){

                this.$http.get('program/getPrograms/'+page).then(function( response ){

                    var pagination = response.body.pagination;
                    this.active = pagination.active;
                    this.pages = pagination.pages;
                    this.next = pagination.next;
                    this.prev = pagination.prev;

                    bus.$emit('program-pagination', response.body.programs);

                });

            },
            clickPage : function( page ){

                this.$http.get('program/getPrograms/'+page).then(function( response ){

                    var pagination = response.body.pagination;
                    this.active = pagination.active;
                    this.pages = pagination.pages;
                    this.next = pagination.next;
                    this.prev = pagination.prev;

                    bus.$emit('program-pagination', response.body.programs);

                });

            }
        }
    }
</script>
