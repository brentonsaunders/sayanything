class SignUpView extends Modal {
    constructor(params = null) {
        super();

        if(params === null) {
            this.$view = $(`
                <div id="sign-up-view" class="modal auth-view">
                    <form>
                        <div class="top"></div>
                        <div class="bottom">
                            <input data-param="email" data-controller="main" data-action="signUp" placeholder="Email" name="email" type="text">
                            <input data-param="password" data-controller="main" data-action="signUp" placeholder="Password" name="password" type="password">
                            <input data-param="password" data-controller="main" data-action="signUp" placeholder="Confirm Password" name="password2" type="password">
                            <a class="button dark" data-controller="main" data-action="signUp" data-new-state="true">Next</a>
                        </div>
                    </form>
                </div>
            `);
        } else if(params.has('email') && params.has('password')) {
            this.$view = $(`
                <div id="sign-up-view" class="modal auth-view">
                    <form>
                        <div class="top"></div>
                        <div class="bottom">
                        <input data-param="name" data-controller="main" data-action="signUp" placeholder="Your Name" name="name" type="text">
                            <input data-param="dob" data-controller="main" data-action="signUp" name="dob" type="text" placeholder="Birthdate" onfocus="(this.type='date')">
                            <select data-param="state" data-controller="main" data-action="signUp" name="state">
                                <option>State</option>
                            </select>
                            <a class="button dark" data-controller="main" data-action="signUp">Sign Up</a>
                        </div>
                    </form>
                </div>
            `);

            const $state = this.$view.find('select[name=state]');

            this.getStates().forEach(state => {
                $state.append(`<option value="${state.abbreviation}">${state.name}</option>`);
            });
        }
    }

    getStates() {
        return [{"name":"Alabama","abbreviation":"AL"},{"name":"Alaska","abbreviation":"AK"},{"name":"Arizona","abbreviation":"AZ"},{"name":"Arkansas","abbreviation":"AR"},{"name":"California","abbreviation":"CA"},{"name":"Colorado","abbreviation":"CO"},{"name":"Connecticut","abbreviation":"CT"},{"name":"Delaware","abbreviation":"DE"},{"name":"Florida","abbreviation":"FL"},{"name":"Georgia","abbreviation":"GA"},{"name":"Hawaii","abbreviation":"HI"},{"name":"Idaho","abbreviation":"ID"},{"name":"Illinois","abbreviation":"IL"},{"name":"Indiana","abbreviation":"IN"},{"name":"Iowa","abbreviation":"IA"},{"name":"Kansas","abbreviation":"KS"},{"name":"Kentucky","abbreviation":"KY"},{"name":"Louisiana","abbreviation":"LA"},{"name":"Maine","abbreviation":"ME"},{"name":"Maryland","abbreviation":"MD"},{"name":"Massachusetts","abbreviation":"MA"},{"name":"Michigan","abbreviation":"MI"},{"name":"Minnesota","abbreviation":"MN"},{"name":"Mississippi","abbreviation":"MS"},{"name":"Missouri","abbreviation":"MO"},{"name":"Montana","abbreviation":"MT"},{"name":"Nebraska","abbreviation":"NE"},{"name":"Nevada","abbreviation":"NV"},{"name":"New Hampshire","abbreviation":"NH"},{"name":"New Jersey","abbreviation":"NJ"},{"name":"New Mexico","abbreviation":"NM"},{"name":"New York","abbreviation":"NY"},{"name":"North Carolina","abbreviation":"NC"},{"name":"North Dakota","abbreviation":"ND"},{"name":"Ohio","abbreviation":"OH"},{"name":"Oklahoma","abbreviation":"OK"},{"name":"Oregon","abbreviation":"OR"},{"name":"Pennsylvania","abbreviation":"PA"},{"name":"Rhode Island","abbreviation":"RI"},{"name":"South Carolina","abbreviation":"SC"},{"name":"South Dakota","abbreviation":"SD"},{"name":"Tennessee","abbreviation":"TN"},{"name":"Texas","abbreviation":"TX"},{"name":"Utah","abbreviation":"UT"},{"name":"Vermont","abbreviation":"VT"},{"name":"Virginia","abbreviation":"VA"},{"name":"Washington","abbreviation":"WA"},{"name":"West Virginia","abbreviation":"WV"},{"name":"Wisconsin","abbreviation":"WI"},{"name":"Wyoming","abbreviation":"WY"}];
    }
}