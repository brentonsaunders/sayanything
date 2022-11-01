class Router {
    constructor() {
        this.controllerStack = [];
        this.currentState = 0;

        $(document).on('click', 'a[data-controller][data-action], button[data-controller][data-action]', e => {
            const $target = $(e.target);
            const controllerName = $target.data('controller');
            const action = $target.data('action');
            const newState = $target.data('new-state') === true;
            const params = this.getParams(controllerName, action);

            this.route(controllerName, action, params, newState);
        });

        addEventListener('popstate', e => {
            // Did the user attempt to go back?
            if(e.state.thisState < this.currentState) {
                // Navigate away from the app if the user goes all the way back
                if(this.controllerStack.length === 1) {
                    history.back();
                    
                    return;
                }

                const controller = this.controllerStack.pop();

                controller.back();
            }

            this.currentState = e.state.thisState;
        });
    }

    route(controllerName, action, params, newState) {
        const controller = this.getController(controllerName);

        if(!controller) {
            return;
        }

        if(newState || this.controllerStack.length === 0) {
            this.controllerStack.push(controller);

            ++this.currentState;

            history.pushState({thisState: this.currentState}, null);
        } else {
            this.controllerStack[this.controllerStack.length - 1] = controller;
        }

        controller[action](params);
    }

    getController(controllerName) {
        if(controllerName === 'main') {
            return new MainController();
        }

        return null;
    }

    getParams(controllerName, action) {
        const params = new Map();

        $(`[data-param][data-controller=${controllerName}][data-action=${action}]`).each(function() {
            const key = $(this).data('param');
            const val = $(this).val();
            
            params.set(key, val);
        });

        return params;
    }


}