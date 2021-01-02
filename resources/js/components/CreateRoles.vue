<template>
     <div class="row">
            <div class="col-12">
               
                <div  id="addRole" class="card">
                    <div class="card-body">
                       <form v-on:submit.prevent="createRole()" enctype="multipart/form-data" method="post">
                            <div class="form-group">
                                <label for="name">Role Name</label>
                                <input  type="text" placeholder="type role name here..." class="form-control" name="name" id="name" v-model="role.name" /> 
                                
                                <span class="text-danger" role="alert">
                                    <strong>{{ errors.get('name') }}</strong>
                                </span> 

                                <button type="submit" class="btn btn-sm btn-primary" style="margin:10px; padding:5 15 5 15; background-color:#4267b2">Save Role</button>                   
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
            editingrole: {type:Object},
        
        },

        data() {
            return {
                errors : new Errors(),
                loading : false,
                role : {
                    id : '',
                    name : ''    
                },
                role_id : '',
                edit : false
            }
        },
        watch: {
            editingrle: function (newrole) {
                this.role = {...newrole};
                // this.showForm = true;
            }
        },
        methods: {  
            
            createRole() {
 
                if (this.role && this.role.id) {
                  
                    axios({
                        method: 'put',
                        url: '/api/roles',
                        data: {
                            role_id: this.role.id,
                            name: this.role.name,
                            user_id: this.$userId
                        }
                    })
                    .then(res => {
                        this.errors = new Errors();                      
                        this.role.name = '';
                        this.$emit("role-edited", this.role);
                        
                    })
                    .catch(error => {
                       
                        this.errors.record(error.response.data);
                    });
                }
                else {
                    
                    let data = new FormData()
                    data.append('name', this.role.name );
                    data.append('user_id', this.$userId);
                    
                    axios({
                        headers: { 'Content-Type': 'multipart/form-data' },
                        method: 'post',
                        url: '/api/roles',
                        data: data
                    })
                    .then(res => {
                        this.errors = new Errors();
                        this.$emit("role-created");
                        this.role = {};
                    })
                   .catch(error => {
                        this.errors.record(error.response.data);
                    });
                }
            }
        }
    }
</script>