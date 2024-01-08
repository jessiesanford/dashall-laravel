<template>
    <form method="post" id="login_form" class="sign_in align_center wid-50">
        <label class="block push-bottom">
            <input type="email" class="textbox textbox_lrg wid-100" name="email" autocorrect="off" autocapitalize="off" placeholder="Email Address">
        </label>
        <label class="block push-bottom">
            <input type="password" class="textbox textbox_lrg wid-100" name="password" placeholder="Password">
        </label>
        <button type="submit" id="login-btn" class="btn btn-lrg wid-100 push-top">Log In</button>
        <div class="login-error"></div>
        <hr />
        <p class="push-bottom"><a href="user?action=forgot_password"><i class="fa fa-key"></i>&nbsp; Forgot Password</a></p>
        <p>Don&rsquo;t have an account? <a href="/register">Create One!</a></p>
    </form>
</template>

<script>
    export default {
        mounted() {}
    };
</script>
