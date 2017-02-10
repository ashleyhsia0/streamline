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

            <tbody>
                <tr v-for="(task, index) in filteredTasks">
                    <td>{{ task.id }}</td>
                    <td>{{ task.title }}</td>
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
                }
            };
        },

        computed: {
            filteredTasks: function() {
                switch (this.visibility) {
                    case 'inProgress':
                        return this.tasks.filter(task => task.status === 0);
                    case 'done':
                        return this.tasks.filter(task => task.status === 1);
                    case 'completed':
                        return this.tasks.filter(task => task.status === 2);
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
                });
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
