class MainView {
    constructor(activeTab = 'feed') {
        $('#app').html(`
            <div class="view">
                <header>
                    <div id="leading"></div>
                    <div id="title"></div>
                    <div id="actions">
                        <a data-new-state="true" data-controller="main" data-action="profile" id="profile"></a>
                    </div>
                </header>
                <main></main>
                <footer>
                    <nav id="main-nav">
                        <ul>
                            <li data-controller="main" data-action="feed" class="feed">
                                <a class="label">Feed</a>
                            </li>
                            <li data-controller="main" data-action="mentors" class="mentors">
                                <a class="label">Mentors</a>
                            </li>
                            <li data-controller="main" data-action="gems" class="gems">
                                <a class="label">Gems</a>
                            </li>
                            <li data-controller="main" data-action="questions" class="questions">
                                <a class="label">Questions</a>
                            </li>
                        </ul>
                    </nav>
                </footer>
            </div>
        `);

        $(`.view #main-nav li.${activeTab}`).addClass('active');
    }

    dismiss() {

    }
}