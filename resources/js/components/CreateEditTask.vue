<template>
     <div class="row">
            <div class="col-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h2>Create Task</h2>
                    </div>
                    <div class="card-body">
                       <form @submit.prevent="createTask">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input  type="text" class="form-control" name="title" id="title" v-model="task.title" /> 
                            
                            </div>
                            
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea v-model="task.content" class="form-control" id="content" name="content" rows="4" ></textarea>
                            
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Save Task</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {

        props: {
            editingtask: {type:Object},
        
        },

        data() {
            return {
                loading : false,
                task : {
                    id : '',
                    title : '',
                    content : '',
                    views : '',
                    // assignees : []
                },
                task_id : '',
                edit : false
            }
        },
        watch: {
            editingtask: function (newtask) {
                this.task = {...newtask}
            }
        },
        methods: {
            createTask() {
                if (this.task && this.task.id) {
                    axios({
                        method: 'put',
                        url: '/api/tasks',
                        data: {
                            task_id : this.task.id,
                            title : this.task.title,
                            content : this.task.content
                        }
                    })
                    .then(res => {
                        // check if the request is successful
                        this.$emit("task-edited", this.task);
                        this.task.id = null;

                    })

                    .catch(error =>{
                        console.log(error)
                    });
                }
                else {
                    axios({
                        method: 'post',
                        url: '/api/tasks',
                        data: {
                            task_id : this.task.id,
                            title : this.task.title,
                            content : this.task.content
                        }
                    })
                    .then(res => {
                        // check if the request is successful
                        this.$emit("task-created");
                        this.task = {}
                    })
                    .catch(function (error){
                        console.log(error)
                    });
                }
            }
        }
    }
</script>
