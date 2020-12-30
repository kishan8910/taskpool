<template>
     <div class="row">
            <div class="col-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h2>Create Task</h2>
                    </div>
                    <div class="card-body">
                       <form v-on:submit.prevent="createTask" enctype="multipart/form-data" method="post">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input  type="text" class="form-control" name="title" id="title" v-model="task.title" /> 
                            
                            
                                <label for="content">Content</label>
                                <textarea v-model="task.content" class="form-control" id="content" name="content" rows="4" ></textarea>
                                  
                                <button type="submit" class="btn btn-sm btn-primary
                   pull-right" style="margin:10px; padding:5 15 5 15; background-color:#4267b2">Save Task</button>       
                                
                                 <div style="position:relative;display:inline-block">
                                    <div style="border:1px solid #ddd; background-color:#efefef; padding:5 17 5 12;margin-bottom:10px;">
                                        <input style="position:absolute;left:0;top:0; opacity:0;" type="file" id="image" name="image" @change="onFileChange"/>
                                        <b>Choose File</b>   
                                           
                                    </div>
                                </div>
                                    <!-- <button @click="removeImage" v-if="image" class="btn btn-sm ml-2 btn-outline-danger">Remove Image</button>  -->
                                
                                <div v-if="image" > <img :src="image" style="width:200px" /></div> 
                                                  
                            </div>
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
                    image : '',
                    // assignees : []
                },
                task_id : '',
                edit : false,
                image : ''
            }
        },
        watch: {
            editingtask: function (newtask) {
                this.task = {...newtask};
                this.image = newtask.image;
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
                            task_id : this.task.id,
                            title : this.task.title,
                            content : this.task.content,
                            created_by : this.$userId,
                            image : this.image
                        }
                    })
                    .then(res => {
                        // check if the request is successful
                        removeImage();
                        this.$emit("task-edited", this.task);
                        this.task.id = null;
                        
                    })

                    .catch(error =>{
                        console.log(error)
                    });
                }
                else {

                    let data = new FormData;
                    data.append('image', document.getElementById('image').files[0]);
                    data.append('task_id', this.task.id);
                    data.append('title', this.task.title);
                    data.append('content', this.task.content);
                    data.append('created_by', this.$userId);

                    axios({
                        headers: { 'Content-Type': 'multipart/form-data' },
                        method: 'post',
                        url: '/api/tasks',
                        data: data
                    })
                    .then(res => {
                        // check if the request is successful
                        this.$emit("task-created");
                        this.image = '';
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
