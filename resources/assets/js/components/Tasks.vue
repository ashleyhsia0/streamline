<template>
    <div>
        <div>
            Filter Tasks by:
            <input type="radio" v-model="visibility" value="all" selected>All
            <input type="radio" v-model="visibility" value="inProgress">In Progress
            <input type="radio" v-model="visibility" value="done">Done
            <input type="radio" v-model="visibility" value="completed">Completed
        </div>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newTaskModal">
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
                            <input type="text" class="form-control" v-model="newTask.title">
                        </div>
                        <div class="form-group">
                            <label for="parent-id" class="control-label">Parent Task ID:</label>
                            <select class="form-control" v-model.trim="newTask.parentId">
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

        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th>Task ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Parent Task</th>
                    <th></th>
                </tr>
            </thead>

            <tbody v-for="(task, index) in filteredTasks">
                <tr>
                    <td>{{ task.id }}</td>
                    <td>
                        {{ task.title }}
                        <br>
                        <span v-if="task.descendants.length > 0">
                            <span class="label label-default">{{ task.descendants.length }} Dependencies</span>
                            <span class="label label-primary">{{ getTasksByStatus(task.descendants, status.done).length }} Done</span>
                            <span class="label label-success">{{ getTasksByStatus(task.descendants, status.completed).length }} Completed</span>
                        </span>
                    </td>
                    <td v-if="task.status == 0">
                        IN PROGRESS
                    </td>
                    <td v-else-if="task.status == 1">
                        DONE
                    </td>
                    <td v-else>
                        COMPLETED
                    </td>
                    <td>{{ task.parent_id }}</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" :id="task.id" :value="task.id" @click="toggleCheck(task.id, index)" :checked="task.status === 1 || task.status === 2">
                            </label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
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
                    self.tasks.push(response);

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
                });
            }
        },

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
