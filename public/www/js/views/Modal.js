class Modal extends View {
    constructor() {
        super();
    }

    set $view($view) {
        $('#app').append($view);

        this._$view = $view;
    }

    get $view() {
        return this._$view;
    }
}