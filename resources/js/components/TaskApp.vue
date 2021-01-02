<template>
    <div class="row justify-content-center">
        <div class="col-md-3 wrapper">
            <sidemenu v-on:filterParam="filterTasks" />
        </div>
        <div class="col-md-9">
            <div>
                
                <createedittask :editingtask="editingtask" v-on:task-edited="editTask" v-on:task-created="createTask" />
                <tasks :tasks="tasks" :pagination="pagination" :loading="loading" v-on:editEvent="edit" v-on:paginationEvent="makePagination" v-on:fetchPageEvent="fetchTasks" v-on:comment-created="createComment" v-on:task-deleted="deleteTask" v-on:comment-deleted="deleteComment" v-on:assignee-added="fetchTasks" />
            
            </div>
        </div>
    </div>
</template>
<script>


export default {
    name : 'TaskApp',
     data() {
            return {
                loading : false,
                tasks : [],
                editingtask : null,
                pagination : {},
            }
        },
        created() {
            this.fetchTasks();
        },

        methods: {
            filterTasks(filterParam) {
                console.log(this.tasks);
                console.log(filterParam);
            },
            deleteTask() {
                this.fetchTasks();
            },
            deleteComment() {
                this.fetchTasks();
            },
            createTask() {
                this.fetchTasks();
            },
            createComment() {
                this.fetchTasks();
            },
            editTask(task) {
               
                // const index = this.tasks.findIndex(t => t.id === task.id);
                
                // if(index !== -1){
                //     this.tasks[index].title = task.title;
                //     this.tasks[index].content = task.content;
                // }

                this.fetchTasks();
            },

            edit(task) {
                this.editingtask = task;
            },

            fetchTasks(page_url) {
                page_url = page_url || 'api/tasks';
                this.loading = true;
                axios.get(page_url)
                .then((response) => {
                    this.tasks = response.data.data
                    this.makePagination(response.data.meta,response.data.links);
                    this.loading = false;
                    this.editingtask = null;    
                })
                .catch(function (error){
                    console.log(error)
                })
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                }
                this.pagination = pagination
            },
        }
}
</script>
