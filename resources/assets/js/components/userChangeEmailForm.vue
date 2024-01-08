<template>
    <form id="changeEmail" method="POST">
        <label class="block push-bottom">
            <div>New Email</div>
            <input type="text" class="textbox" name="email" />
        </label>
        <label class="block push-bottom">
            <div>Password</div>
            <input type="password" class="textbox" name="password" />
        </label>
    </form>
</template>

<script>
    export default {
        mounted() {
//            console.log('Component ready.');
        }
    };
</script>
