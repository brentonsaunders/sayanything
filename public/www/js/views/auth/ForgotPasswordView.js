class ForgotPasswordView extends Modal {
    constructor() {
        super();

       this.$view = $(`
            <div id="forgot-pw-view" class="modal auth-view">
                <form>
                    <div class="top"></div>
                    <div class="bottom">
                        <p>Enter your email, and we'll send you a link to reset your password</p>
                        <input data-param="email" data-controller="main" data-action="forgotPassword" placeholder="Email" name="email" type="text">
                        <a class="button dark" data-controller="main" data-action="forgotPassword">Send</a>
                    </div>
                </form>
            </div>
        `);
    }
}