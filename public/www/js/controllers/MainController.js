class MainController {
    constructor() {
        
    }

    index(params) {
        this.view = new SplashView();
    }

    signIn(params) {
        if(params.size === 0) {
            this.view = new SignInView();
        } else {
            
            this.view = new SignInView(params);
        }
    }

    signUp(params) {
        if(params.size === 0) {
            this.view = new SignUpView();
        } else if(params.size < 5) {
            this.view = new SignUpView(params);
        } else {

        }
    }

    forgotPassword(params) {
        if(params.size === 0) {
            this.view = new ForgotPasswordView();

            return true;
        }
    }

    feed(params) {
        this.view = new MainView('feed');
    }

    mentors(params) {
        this.view = new MainView('mentors');
    }

    gems(params) {
        this.view = new MainView('gems');
    }

    questions(params) {
        this.view = new MainView('questions');
    }

    profile(params) {
        this.view = new View();
    }

    test(params) {
        this.view = new View();
    }

    back() {
        this.view.dismiss();
    }

    top() {
        this.view.reset();
    }
}