class View {
    constructor() {
        this._$view = null;
    }

    set $view($view) {
        $('#app').empty().append($view);

        this._$view = $view;
    }

    get $view() {
        return this._$view;
    }

    dismiss() {
        if(this._$view) {
            this._$view.remove();
        }
    }

    reset() {
        this._$view.find('input[type=text],input[type=password]').val('');
    }
}