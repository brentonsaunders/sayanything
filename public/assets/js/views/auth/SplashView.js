class SplashView extends View {
    constructor() {
        super();

        this.$view = $(`
            <div id="splash-view" class="view auth-view">
                <form>
                    <div class="top"></div>
                    <div class="bottom">
                        <a class="button light" data-new-state="true" data-controller="main" data-action="signIn">Sign In</a>
                        <a class="button dark" data-new-state="true" data-controller="main" data-action="signUp">Sign Up</a>
                    </div>
                </form>
            </div>
        `);
    }
}