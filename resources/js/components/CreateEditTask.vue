<template>
     <div class="row">
            <div class="col-12">
                <a v-if="!showForm" @click="showForm = true;" href="#addTask" class="btn btn-primary mb-2">+ Add Task</a>
               
                <div v-if="showForm" id="addTask" class="card">
                    
                    <div class="card-body">
                        <button @click="showForm=false;" class="close" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
                       <form v-on:submit.prevent="createTask()" enctype="multipart/form-data" method="post">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input  type="text" placeholder="title here..." class="form-control" name="title" id="title" v-model="task.title" /> 
                                <span class="text-danger" role="alert">
                                    <strong>{{ errors.get('title') }}</strong>
                                </span>
                                <br>
                                <label for="content">Content</label>
                                <textarea placeholder="describe the task here..." v-model="task.content" class="form-control" id="content" name="content" rows="2" ></textarea>
                                <span class="text-danger" role="alert">
                                    <strong>{{ errors.get('content') }}</strong>
                                </span>
                                <br>
                                <button type="submit" class="btn btn-sm btn-primary
                   pull-right" style="margin:10px; padding:5 15 5 15; background-color:#4267b2">Save Task</button>       
                                
                                 <div style="position:relative;display:inline-block">
                                    <div style="border:1px solid #ddd; background-color:#efefef; padding:5 17 5 12;margin-bottom:10px;">
                                        <input style="position:absolute;left:0;top:0; opacity:0;" type="file" id="image" name="image" @change="onFileChange"/>
                                        <b>Choose File</b>   
                                           
                                    </div>
                                </div>
                                
                                <div v-if="image" > <img :src="image" style="width:200px" /></div>             
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    
    class Errors {
        constructor() {
            this.errors = {};
        }

        get(field) {
            if( this.errors[field]) {
                return this.errors[field][0];
            }
        }

        record(errors) {
            this.errors = errors.errors;
        }
    }

    export default {

        props: {
            editingtask: {type:Object},
        
        },

        data() {
            return {
                errors : new Errors(),
                loading : false,
                task : {
                    id : '',
                    title : '',
                    content : '',
                    image : '',
                    // assignees : []
                },
                task_id : '',
                edit : false,
                image : '',
                showForm : false
            }
        },
        watch: {
            editingtask: function (newtask) {
                this.task = {...newtask};
                this.showForm = true;
            }
        },
        methods: {  
            
            removeImage() {
                this.image = '';
            },
            onFileChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                this.createImage(files[0]);
            },
            createImage(file) {
                var image = new Image;
                var reader = new FileReader;
                reader.onload = (e) => {
                    this.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            createTask() {
 
                if (this.task && this.task.id) {
                   
                    axios({
                        method: 'put',
                        url: '/api/tasks',
                        data: {
                            task_id: this.task.id,
                            title: this.task.title,
                            content: this.task.content,
                            created_by: this.$userId
                        }
                    })
                    .then(res => {
                        
                        this.errors = new Errors();
                        
                        this.task.content = '';
                        this.$emit("task-edited", this.task);
                        
                        // initialize();
                        let instance = Vue.$toast;
                        instance.success('Task <strong>'+this.task.title+'</strong> Updated!', {
                            position: 'top-right'
                        });
                        instance.dismiss();
                        
                    })
                    .catch(error => {
                       
                        this.errors.record(error.response.data);
                    });
                }
                else {
                    
                    let data = new FormData()
        
                    data.append('image', document.getElementById('image').files[0]);
                    // data.append('task_id', this.task.id);
                    data.append('title', this.task.title );
                    data.append('content', this.task.content);
                    data.append('created_by', this.$userId);
                    
                    axios({
                        headers: { 'Content-Type': 'multipart/form-data' },
                        method: 'post',
                        url: '/api/tasks',
                        data: data
                    })
                    .then(res => {
                        this.errors = new Errors();
                        this.$emit("task-created");
                        this.image = '';
                        this.task = {};

                        let instance = Vue.$toast;
                        instance.success('Task Created!', {
                            position: 'top-right'
                        });
                        instance.dismiss();
                    })
                   .catch(error => {
                        this.errors.record(error.response.data);
                    });
                }
            }
        }
    }
</script>
