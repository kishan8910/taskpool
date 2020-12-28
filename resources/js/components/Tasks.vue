<template>
    <div>
        <h2>Tasks</h2>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" @click="fetchPage(pagination.prev_page_url)" href="#">Previous</a></li>
                <li class="page-item text-dark"><a class="page-link disabled"  href="#">Page {{ pagination.current_page}} / {{pagination.last_page }}</a></li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" @click="fetchPage(pagination.next_page_url)" href="#">Next</a></li>
            </ul>
        </nav>
        <div v-if="loading">loading ...</div>
        <div class="card card-body mb-2" v-for="task in tasks" v-bind:key="task.id" >
            <h3>{{ task.title }}</h3>
            <p>{{ task.content }}</p>
            <button @click="editTask(task)" class="btn btn-warning mb-2">Edit</button>
            <button @click="deleteTask(task.id)" class="btn btn-danger">Delete</button>
        </div>
    </div>
</template>


<script>

    export default{
        
        props: {
            loading: {type:Boolean, default:false},
            tasks: {type:Array, default:[]},
            pagination: {type:Object,default:{}}
        },
        data() {
            return {
               
                task : {
                    id : '',
                    title : '',
                    content : '',
                    views : '',
                    user_id : ''
                },
                task_id : '',
                edit : false,
                url : ''
            }
        },
        created() {
            // this.fetchTasks();
        },

        methods: {
            // fetchTasks(page_url) {
                
            //     page_url = page_url || 'api/tasks';
            //     this.loading = true;
            //     axios.get(page_url)
            //     .then((response) => {
            //         this.tasks = response.data.data
            //         this.makePagination(response.data.meta,response.data.links);
            //         this.loading = false;
            //     })
            //     .catch(function (error){
            //         console.log(error)
            //     })
            // },

            // makePagination(meta, links) {
                // let pagination = {
                //     current_page: meta.current_page,
                //     last_page: meta.last_page,
                //     next_page_url: links.next,
                //     prev_page_url: links.prev
                // }
                // this.pagination = pagination
                // this.$emit('paginationEvent',pagination);
            // },

            editTask(task) {
                this.$emit('event1',task);
            },
            fetchPage(url) {
                this.$emit('fetchPageEvent', url);
            },
            
        }
    }

</script>
