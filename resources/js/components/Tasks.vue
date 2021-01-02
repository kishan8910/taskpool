<template>
    <div>
        <div v-if="loading">loading ...</div>
        <div class="card mb-1"  v-for="task,key in tasks" v-bind:key="task.id">
            <div class="card-body" >
                <h4 class="card-title">{{ task.title }} </h4>
                <p class="card-subtitle mb-2 text-muted">Posted by: {{task.user.name}}</p>
                <p class="card-text">
                    <img :src=getImgUrl(task.image) width="200"/><br>
                    {{ task.content }}
                </p>
                <a style="cursor: pointer;" @click="showCommentsMethod(task)" class="card-link">Comments ({{ task.comments.length }})</a>
                <a style="cursor: pointer;" @click="assignUser(task)" class="card-link">Assign me</a>
                
                <a style="cursor: pointer;" @click="showAssignees(task)" class="card-link">Assignees ({{ task.assignees.length }})</a>
                <b-modal ok-only ok-variant="primary" ok-title="Ok" v-bind:id="'assignees'+task.id" v-bind:title="'Assignees for '+task.title">
                    <li v-for="assignee in task.assignees" class="my-4">{{ assignee.name }}</li>
                    <span v-if="task.assignees.length == 0">No assignees</span>
                </b-modal>


                <a    v-if="authorizeUser(task)" style="cursor: pointer;" @click="editTask(task)" class="card-link">Edit</a>
                <a v-if="authorizeUser(task)" style="cursor: pointer;" @click="deleteTask(task.id)" class="card-link">Delete</a>

            </div>
            
            <div class="" v-if="showComments === task.id" id="prev_comment">
                <div class="col-sm-12" v-for="comment in task.comments" v-bind:key="comment.id">
                    <div v-if="commentEditFormVisible !== comment.id" class="panel panel-default border mb-2 p-2 bd-highlight">
                        <div class="panel-heading">
                            <h6>{{ comment.content }}</h6>
                        </div>
                        <div class="panel-body text-muted">
                            <p>Commented by: {{ comment.user.name }}</p>
                        </div>
                        <a style="cursor: pointer;" v-if="authorizeUser(comment.user.id)" @click="editComment(comment)" class="card-link">Edit</a>
                        <a style="cursor: pointer;" v-if="authorizeUser(comment.user.id)" @click="deleteComment(comment.id)" class="card-link">Delete</a>
                    </div>
                    <div class="m-2 row" v-if="commentEditFormVisible === comment.id">
                        <div class="input-group">
                            <textarea v-model="comment.content" placeholder="Add a comment" class="form-control mr-1" v-bind:id="'comment'+task.id" v-bind:name="'comment'+task.id" rows="1" ></textarea>
                            <div class="input-group-append">
                                <button class="btn btn-info mt-2" @click.prevent="createComment(task.id,key)">Update</button>
                            </div>
                        </div>
                    </div>
                    
                </div>
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
        <nav aria-label="Page navigation example" class="mt-2">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" @click="fetchPage(pagination.prev_page_url)" href="#">Previous</a></li>
                <li class="page-item text-dark"><a class="page-link disabled"  href="#">Page {{ pagination.current_page}} / {{pagination.last_page }}</a></li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" @click="fetchPage(pagination.next_page_url)" href="#">Next</a></li>
            </ul>
        </nav>
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
                commentEditFormVisible: null,
                showComments : null,
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
                    user_id : '',
                    image : ''
                },
                task_id : '',
                edit : false,
                url : '',
            }
        },

        created() {
            Vue.use(VueToast);
            Echo.private('comment-channel')
                    .listen('CommentEvent', (e) => {
                    let instance = Vue.$toast;
                    instance.success('Comment has been added to the task <strong>'+e.task.title+'</strong> ', {
                        position: 'top-right'
                    });

                    this.$emit("comment-created");
                    instance.dismiss();
                });

        },

        methods: {

            assignUser(taskObj) {
                axios({
                    method: 'post',
                    url: '/api/assignees',
                    data: {
                        user_id : this.$userId,
                        task_id : taskObj.id
                    }
                })
                .then(res => {
                    this.$emit("assignee-added");
                    let instance = Vue.$toast;
                    instance.success('You added to task <strong>'+taskObj.title+'</strong> ', {
                        position: 'top-right'
                    });
                    instance.dismiss();
                })
                .catch(error =>{
                    
                });
            },

            showAssignees(taskObj) {
                this.$bvModal.show('assignees'+taskObj.id);
            },

            authorizeUser(taskObj) {
                
                if(taskObj.user.id == this.$userId || this.$userRole == 1) {
                    return true;
                }
                else {
                    return false;
                }
            },

            getImgUrl(image) {
                if(image) {
                   return 'img/'+image;
                }
                else {
                    return '';
                }
            },
            showCommentsMethod(task) {
                this.showComments = task.id;
            },
            editComment(comment) {
                this.commentEditFormVisible = comment.id;
                this.comment = comment;
            },

            createComment(task_id,key) {
                
                if (this.comment && this.comment.id) {
                    axios({
                        method: 'put',
                        url: '/api/comments',
                        data: {
                            comment_id : this.comment.id,
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
                        this.showComments = task_id;
                        this.$emit("comment-created");
                        this.comment = {}
                    })
                    .catch(function (error){
                        console.log(error)
                    });
                }
                
            },
            editTask(task) {
                this.$emit('editEvent',task);
            },

            fetchPage(url) {
                this.$emit('fetchPageEvent', url);
            },

            deleteTask(id) {
                axios({
                        method: 'delete',
                        url: '/api/tasks/'+id,
                    })
                    .then(res => {
                        this.$emit("task-deleted");
                    })
                    .catch(error =>{
                        console.log(error)
                });
            },

            deleteComment(id) {
                axios({
                        method: 'delete',
                        url: '/api/comments/'+id,
                    })
                    .then(res => {
                        this.$emit("comment-deleted");
                    })
                    .catch(error =>{
                        console.log(error)
                });
            }
            
        }
    }

</script>
