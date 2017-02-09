<template>
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
            <tr v-for="(task, index) in tasks">
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
</template>

<script>
    export default {
        data: function() {
            return {
                tasks: [],
            };
        },

        created: function() {
            this.fetchTasks();
        },

        methods: {
            fetchTasks: function() {
                let self = this;
                $.get('api/tasks').then(function (response) {
                    self.tasks = response;
                });
            },

            toggleCheck: function(taskId, index) {
                let task = this.tasks[index];
                let self = this;

                $.post(`api/tasks/${taskId}`).then(function (response) {
                    self.$set(task, 'status', response.status);
                });
            }
        },

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
