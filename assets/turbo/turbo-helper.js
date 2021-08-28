import * as Turbo from "@hotwired/turbo";

const TurboHelper = class  {

    constructor() {
        document.addEventListener('turbo:before-fetch-request', (event) => {
            this.beforeFetchRequest(event);
        });

        document.addEventListener('turbo:before-fetch-response', (event) => {
            this.beforeFetchResponse(event);
        });
    }

    beforeFetchResponse(event) {
        const fetchResponse = event.detail.fetchResponse;
        const redirectLocation = fetchResponse.response.headers.get('Turbo-Location');

        if (!redirectLocation) {
            return;
        }

        Turbo.visit(redirectLocation);
    }

    beforeFetchRequest(event) {
        const frameId = event.detail.fetchOptions.headers['Turbo-Frame'];

        if (!frameId) {
            return;
        }

        const frame = document.getElementById(frameId);

        if (!frame || !frame.dataset.turboFormRedirect) {
            return;
        }

        event.detail.fetchOptions.headers['Turbo-Frame-Redirect'] = 1;
    }
}

export default new TurboHelper();