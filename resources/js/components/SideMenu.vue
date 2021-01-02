<template>

<div class="card" style="position:fixed; width:20%">
    <nav id="sidebar" class="nav flex-column p-2">
        <div class="sidebar-header">
            <h3>Task Pool</h3>
        </div>
        <ul class="list-unstyled components m-2">
            <li style="border:1px solid #ddd;" class="p-2 nav-item">Home</li>
            <li style="border:1px solid #ddd;" @click="taskFiltered('created')" class="p-2 nav-item">Tasks I Created</li>
            <!-- <li style="border:1px solid #ddd;" class="p-2 nav-item">Tasks Assigned To Me</li> -->
            <li style="border:1px solid #ddd;" class="p-2 nav-item">Roles</li>
        </ul>
    </nav>
</div>

</template>

<script>
export default {

    data() {
        return {
            filterParam : ''
        }
    },

    methods: {
        taskFiltered(filterParam) {
            this.$emit("filterParam", filterParam);
        }
    }
}
</script>
