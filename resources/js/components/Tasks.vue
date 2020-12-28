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
        <div class="card mb-2"  v-for="task,key in tasks" v-bind:key="task.id">
            <div class="card-body" >
                <h4 class="card-title">{{ task.title }} </h4>
                <p class="card-subtitle mb-2 text-muted">Posted by: {{task.user.name}}</p>
                <p class="card-text">{{ task.content }}</p>
                <a href="#" @click="deleteTask(task.id)" class="card-link">Comments</a>
                <a href="#" @click="deleteTask(task.id)" class="card-link">Assign myself</a>
                <a href="#" @click="deleteTask(task.id)" class="card-link">Assignees</a>
                <a href="#" @click="editTask(task)" class="card-link">Edit</a>
                <a href="#" @click="deleteTask(task.id)" class="card-link">Delete</a>
            </div>
            <div class="m-2 row">
               
                    <div class="input-group">
                        <textarea v-model="comment[key]" placeholder="Add a comment" class="form-control mr-1" v-bind:id="'comment'+task.id" v-bind:name="'comment'+task.id" rows="1" ></textarea>
                        
                        <div class="input-group-append">
                            <button @click="createComment(task.id,key)"  class="btn btn-info mt-2" >Save</button>                        
                        </div>
                    </div>
                
            </div>
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
                comments : [],
                comment : {
                    id : '',
                    content : '',
                    user_id : '',
                    task_id : ''
                },
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
        
        // watch: {
        //     comment: function () {
        //         this.task_id = this.task.id
        //     }
        // },

        methods: {
            
            createComment(task_id,key) {
               
                if (this.comment && this.comment.id) {
                    axios({
                        method: 'put',
                        url: '/api/comments',
                        data: {
                            task_id : this.comment.id,
                            content : this.comment.content,
                            user_id : this.$userId,
                            task_id : task_id
                        }
                    })
                    .then(res => {
                        // check if the request is successful
                        this.$emit("comment-edited", this.comment);
                        this.comment.id = null;

                    })

                    .catch(error =>{
                        console.log(error)
                    });
                }
                else {
                    axios({
                        method: 'post',
                        url: '/api/comments',
                        data: {
                            task_id : this.comment.id,
                            content : this.comment[key],
                            user_id : this.$userId,
                            task_id : task_id
                        }
                    })
                    .then(res => {
                        // check if the request is successful
                        this.$emit("comment-created");
                        this.comment = {}
                    })
                    .catch(function (error){
                        console.log(error)
                    });
                }
            },
            editTask(task) {
                this.$emit('event1',task);
            },

            fetchPage(url) {
                this.$emit('fetchPageEvent', url);
            },
            
        }
    }

</script>
