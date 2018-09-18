<template>
    <div class="box-footer clearfix">
        <ul class="pagination pagination-sm no-margin pull-right">
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
    </div>
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
                // console.log(data);
                this.pages = data.pages;
                this.active = data.active;
                this.next = data.next;
                this.prev = data.prev;

            });

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
            nextPage : function( page ){
                let that = this;

                that.fetchToken().then(function(){
                    that.$http.get('api/user/listByPage/'+page, {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Authorization': that.token_type + ': ' + that.token
                        }
                    }).then(response => response.json())
                    .then(json => {

                        var pagination = json.pagination;
                        that.active = pagination.active;
                        that.pages = pagination.pages;
                        that.next = pagination.next;
                        that.prev = pagination.prev;

                        bus.$emit('user-pagination', json.data);

                    });
                });

            },
            prevPage : function( page ){
                let that = this;

                that.fetchToken().then(function(){
                    that.$http.get('api/user/listByPage/'+page, {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Authorization': that.token_type + ': ' + that.token
                        }
                    }).then(response => response.json())
                    .then(json => {

                        var pagination = json.pagination;
                        that.active = pagination.active;
                        that.pages = pagination.pages;
                        that.next = pagination.next;
                        that.prev = pagination.prev;

                        bus.$emit('user-pagination', json.data);

                    });
                });

            },
            clickPage : function( page ){
                let that = this;
                that.fetchToken().then(function(){
                    that.$http.get('api/user/listByPage/'+page, {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Authorization': that.token_type + ': ' + that.token
                        }
                    }).then(response => response.json())
                    .then(json => {

                        var pagination = json.pagination;
                        that.active = pagination.active;
                        that.pages = pagination.pages;
                        that.next = pagination.next;
                        that.prev = pagination.prev;

                        bus.$emit('user-pagination', json.data);

                    });
                });

            }
        }
    }
</script>
