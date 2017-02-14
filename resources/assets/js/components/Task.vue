<template>
    <li>
        <div class="item">
            <input type="checkbox"
                   :id="task.id"
                   :value="task.id"
                   @click="toggleCheck(task.id, index)"
                   :checked="task.status === 1 || task.status === 2">

            <div class="details"
                 :class="classObject">
                <span class="label label-info">
                    ID#{{ task.id }}
                </span>
                <p>
                    {{ task.title }}
                </p>

                <div class="relationships">
                    <!-- <span class="label label-primary">PARENT ID#{{ task.parent_id }}</span> -->

                    <div v-if="task.descendants.length > 0" @click="collapse">
                        <span v-if="open">
                            <i class="fa fa-caret-down" aria-hidden="true"></i>
                        </span>
                        <span v-else>
                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                        </span>
                        <span class="label label-default">
                            {{ task.descendants.length }} Dependencies
                        </span>
                        <span class="label label-primary">
                            {{ getTasksByStatus(task.descendants, status.done).length }} Done
                        </span>
                        <span class="label label-success">
                            {{ getTasksByStatus(task.descendants, status.completed).length }} Completed
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
                </div>
            </div>

            <div class="status">
                <span v-if="task.status == 0" class="in-progress">
                    In progress
                </span>
                <span v-else-if="task.status == 1" class="done">
                    Done
                </span>
                <span v-else class="completed">
                    Completed
                </span>
            </div>
        </div>
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
            },
            classObject: function () {
                return {
                    'details-danger': this.task.status == 0,
                    'details-warning': this.task.status == 1,
                    'details-success': this.task.status == 2
                }
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
