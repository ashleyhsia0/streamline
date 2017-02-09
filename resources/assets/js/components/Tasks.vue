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
            <tr v-for="task in tasks">
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
                var self = this;
                $.get('api/tasks').then(function (response) {
                    self.tasks = response;
                });
            }
        },

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
