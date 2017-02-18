<template>
    <div class="modal fade" :id="'edit-task-modal-' + task.id" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Task</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible fade in" role="alert" v-if="editTaskError">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ editTaskError }}
                    </div>
                    <div class="form-group">
                        <label for="title" class="control-label">Task Title:</label>
                        <input type="text" class="form-control" v-model.trim="editedTask.title">
                    </div>
                    <div class="form-group">
                        <label for="parent-id" class="control-label">Parent Task ID:</label>
                        <select class="form-control" v-model="editedTask.parentId">
                            <option value="">Optional</option>
                            <option v-for="task in tasks">{{ task.id }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="editTask(task.id, editedTask)">Edit</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            task: Object,
            fetchTasks: Function,
            tasks: Array
        },

        data: function () {
            return {
                editedTask: {
                    'title': this.task.title,
                    'parentId': this.task.parent_id
                },
                editTaskError: ''
            };
        },

        methods: {
            editTask: function(taskId, editedTask) {
                let self = this;

                $.ajax({
                    url: `api/tasks/${taskId}`,
                    method: 'PUT',
                    data: editedTask,
                })
                 .done(function(response) {
                    self.fetchTasks();
                    $(`#edit-task-modal-${self.task.id}`).modal('hide');
                })
                 .fail(function(response) {
                    let error = JSON.parse(response.responseText);
                    self.editTaskError = error.message;
                });
            }
        }
    }
</script>
