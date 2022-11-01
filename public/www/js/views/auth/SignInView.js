class SignInView extends Modal {
    constructor() {
        super();

       this.$view = $(`
            <div id="sign-in-view" class="modal auth-view">
                <form>
                    <div class="top"></div>
                    <div class="bottom">
                        <input data-param="email" data-controller="main" data-action="signIn" placeholder="Email" name="email" type="text">
                        <input data-param="password" data-controller="main" data-action="signIn" placeholder="Password" name="password" type="password">
                        <a class="button dark" data-controller="main" data-action="signIn">Sign In</a>
                        <a class="forgot-pw" data-controller="main" data-action="forgotPassword" data-new-state="true">Forgot Password?</a>
                    </div>
                </form>
            </div>
        `);
    }
}