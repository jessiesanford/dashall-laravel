<template>
    <form id="manage-filter-orders" method="POST">
        <select class="wid-100" multiple="multiple" name="filterStatusList[]">
            <option value="PDR">Pending Review</option>
            <option value="AWD_S1">Awaiting Details S1</option>
            <option value="AWD_S2">Awaiting Details S2</option>
            <option value="AWD_S3">Awaiting Details S3</option>
            <option value="AWD_S4">Awaiting Details S4</option>
            <option value="APP_S1">Approved S1</option>
            <option value="APP_S2">Approved S2</option>
            <option value="APP_S3">Approved S3</option>
            <option value="ARR">Arrived</option>
            <option value="COM">Complete</option>
            <option value="ARCH">Archived</option>
        </select>
    </form>
</template>

<script>
    export default {
        mounted() {}
    };
</script>
