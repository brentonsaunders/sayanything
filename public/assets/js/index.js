$(async function() {
    await include('//assets.brothasmentor.com/js/jquery-3.6.1.min.js');
    await include('//assets.brothasmentor.com/js/views/View.js');
    await include('//assets.brothasmentor.com/js/views/Modal.js');
    await include('//assets.brothasmentor.com/js/views/MainView.js');
    await include('//assets.brothasmentor.com/js/views/auth/SplashView.js');
    await include('//assets.brothasmentor.com/js/views/auth/SignInView.js');
    await include('//assets.brothasmentor.com/js/views/auth/SignUpView.js');
    await include('//assets.brothasmentor.com/js/views/auth/ForgotPasswordView.js');
    await include('//assets.brothasmentor.com/js/controllers/MainController.js');
    await include('//assets.brothasmentor.com/js/Router.js');
    await include('//assets.brothasmentor.com/js/App.js');

    const app = new App();
});

function include(filename) {
    return new Promise(function(resolve, reject) {
        const script = document.createElement('script');

        script.src = filename;

        document.head.appendChild(script);

        script.onload = function() {
            resolve();
        }
    });
}