<template>
    <li>
        <div class="checkbox">
            <label>
                <input type="checkbox"
                       :id="task.id"
                       :value="task.id"
                       @click="toggleCheck(task.id, index)"
                       :checked="task.status === 1 || task.status === 2">
                ID#{{ task.id }}
                {{ task.title }}
            </label>
            <span v-if="task.status == 0">
                IN PROGRESS
            </span>
            <span v-else-if="task.status == 1">
                DONE
            </span>
            <span v-else>
                COMPLETED
            </span>
            PARENT ID#{{ task.parent_id }}
        </div>

        <div class="dep-label" v-if="task.descendants.length > 0" @click="collapse">
            <span class="label label-default">
                &#9660; {{ task.descendants.length }} Dependencies
                <span class="label label-primary">
                    {{ getTasksByStatus(task.descendants, status.done).length }} Done
                </span>
                <span class="label label-success">
                    {{ getTasksByStatus(task.descendants, status.completed).length }} Completed
                </span>
            </span>
        </div>

        <ul v-show="open" v-if="isParent">
            <task v-for="(task, index) in task.descendants"
                  :task="task"
                  :key="task.id"
                  :index="index"
                  :toggleCheck="toggleCheck"
                  :getTasksByStatus="getTasksByStatus"
                  :status="status">
            </task>
        </ul>
    </li>
</template>

<script>
    export default {
        props: {
            task: Object,
            index: Number,
            toggleCheck: Function,
            getTasksByStatus: Function,
            status: Object
        },

        data: function () {
            return {
                open: false
            };
        },

        computed: {
            isParent: function () {
                return this.task.descendants && this.task.descendants.length;
            }
        },

        methods: {
            collapse: function () {
                if (this.isParent) {
                    this.open = !this.open;
                }
            },
        }
    }
</script>
