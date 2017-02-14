<template>
    <div class="wrapper">
        <h1>Tasks</h1>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" id="btn-new-task" data-toggle="modal" data-target="#newTaskModal">
          <i class="fa fa-plus-circle" aria-hidden="true"></i>
          New Task
        </button>

        <!-- Modal -->
        <div class="modal fade" id="newTaskModal" tabindex="-1" role="dialog" aria-labelledby="newTaskModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">New Task</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible fade in" role="alert" v-if="newTaskError">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ newTaskError }}
                        </div>
                        <div class="form-group">
                            <label for="title" class="control-label">Task Title:</label>
                            <input type="text" class="form-control" v-model.trim="newTask.title">
                        </div>
                        <div class="form-group">
                            <label for="parent-id" class="control-label">Parent Task ID:</label>
                            <select class="form-control" v-model="newTask.parentId">
                                <option value="">Optional</option>
                                <option v-for="task in tasks">{{ task.id }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="createNewTask">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="filters">
            Filter Tasks by:
            <input type="radio" v-model="visibility" value="all" selected> All
            <input type="radio" v-model="visibility" value="inProgress"> In Progress
            <input type="radio" v-model="visibility" value="done"> Done
            <input type="radio" v-model="visibility" value="completed"> Completed
        </div>

        <ul v-cloak class="task-list">
            <task v-for="(task, index) in filteredTasks"
                  :task="task"
                  :key="task.id"
                  :index="index"
                  :toggleCheck="toggleCheck"
                  :getTasksByStatus="getTasksByStatus"
                  :status="status">
            </task>
        </ul>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                tasks: [],
                visibility: 'all',
                newTask: {
                    'title': '',
                    'parentId': ''
                },
                newTaskError: '',
                status: {
                    'inProgress': 0,
                    'done': 1,
                    'completed': 2
                }
            };
        },

        computed: {
            filteredTasks: function() {
                switch (this.visibility) {
                    case 'inProgress':
                        return this.getTasksByStatus(this.tasks, this.status.inProgress);
                    case 'done':
                        return this.getTasksByStatus(this.tasks, this.status.done);
                    case 'completed':
                        return this.getTasksByStatus(this.tasks, this.status.completed);
                    default:
                        return this.tasks;
                }
            }
        },

        created: function() {
            this.fetchTasks();
        },

        methods: {
            fetchTasks: function() {
                let self = this;

                $.get('api/tasks')
                 .then(function(response) {
                    self.tasks = response;
                });
            },

            createNewTask: function() {
                let task = this.newTask;
                let self = this;

                $.post('api/tasks', task)
                 .then(function(response) {
                    // self.tasks.push(response);
                    self.fetchTasks();

                    self.newTask = {
                        'title': '',
                        'parentId': ''
                    };

                    $('#newTaskModal').modal('hide')
                })
                 .fail(function(response) {
                    let error = JSON.parse(response.responseText);
                    self.newTaskError = error.message;
                 });
            },

            getTasksByStatus: function(tasks, statusCode) {
                return tasks.filter(task => task.status == statusCode);
            },

            toggleCheck: function(taskId, index) {
                let task = this.tasks[index];
                let self = this;

                $.post(`api/tasks/${taskId}`)
                 .then(function(response) {
                    self.$set(task, 'status', response.status);
                    self.fetchTasks();
                })
                 .fail(function(response) {
                    console.log(response);
                });
            }
        },

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
