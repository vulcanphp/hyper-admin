(function () {
    /**
     * FireView class handles the loading and rendering of content
     * with support for routing and error handling.
     */
    class FireView {
        /**
         * Constructor method.
         *
         * Sets initial state for `isLoading` and `isError` properties.
         * Initializes `actions` object with empty arrays for event listeners.
         * Calls `addEventListener()` method to set up event listeners.
         *
         * @return {void}
         */
        constructor() {
            this.timeout = 30;
            this.isLoading = false;
            this.isError = false;
            this.actions = {
                beforeLoad: [],
                afterLoad: [],
                onError: [],
                beforeRender: [],
                onRender: [],
            };

            this.addEventListener();
        }

        /**
         * Registers a callback function for a specified action.
         *
         * @param {string} action - The action name to register the callback for.
         * @param {Function} callback - The function to be executed when the action occurs.
         */
        on(action, callback) {
            this.actions[action].push(callback);
        }

        /**
         * Executes all registered callbacks for a specified action.
         *
         * @param {string} action - The action name whose callbacks are to be executed.
         * @return {void}
         */
        doAction(action) {
            this.actions[action].forEach((callback) => callback());
        }

        /**
         * Sets up event listeners to handle clicks on links and submits of forms that have the `fire` attribute.
         * 
         * When a link with the `fire` attribute is clicked, it prevents the default event behavior and navigates to the link's path.
         * When a form with the `fire` attribute is submitted, it prevents the default event behavior and submits the form to the server.
         * 
         * Also, when the browser's back/forward buttons are clicked, it prevents the default event behavior and navigates to the previous/next path.
         * 
         * @return {void}
         */
        addEventListener() {
            /**
             * Listens for the popstate event and renders the content of the path stored in the event state.
             * This event is triggered when the user clicks the browser's back/forward buttons.
             * 
             * @param {PopStateEvent} event - The popstate event.
             * @return {void}
             */
            window.addEventListener('popstate', (event) => this.renderFireContent(this.currentPath(), false));

            /**
             * Listens for clicks on links and prevents the default behavior if the link has the fire attribute.
             * Instead, it navigates to the link's path using the route method.
             * 
             * @param {MouseEvent} event - The click event.
             * @return {void}
             */
            document.addEventListener('click', (event) => {
                const fireElement = event.target.closest('a[fire]');

                if (fireElement && fireElement.hostname === window.location.hostname) {
                    event.preventDefault();
                    this.route(fireElement.pathname + fireElement.search + fireElement.hash);
                }
            });

            /**
             * Listens for submits of forms and prevents the default behavior if the form has the fire attribute.
             * Instead, it submits the form to the server using the submitFireForm method.
             * 
             * @param {SubmitEvent} event - The submit event.
             * @return {void}
             */
            document.addEventListener('submit', (event) => {
                const fireElement = event.target.closest('form[fire]');

                if (fireElement && fireElement.action.startsWith(window.location.origin)) {
                    event.preventDefault();
                    this.submitFireForm(fireElement);
                }
            });
        }

        /**
         * Navigates to the specified path and renders the corresponding content.
         * 
         * This method will not execute if the content is currently loading.
         * 
         * @param {string} path - The path to navigate to.
         * @return {void}
         */
        route(path) {
            !this.isLoading && this.renderFireContent(path);
        }

        /**
         * Reloads the current content by rendering it again based on the current path.
         * 
         * This method will not execute if the content is currently loading.
         * 
         * @return {void}
         */
        reload() {
            !this.isLoading && this.renderFireContent(this.currentPath());
        }

        /**
         * Submits the fire form and renders the response content.
         * 
         * If the response contains a "redirect" property, the window will be redirected to the specified URL.
         * If the response contains a "push" property, the specified content will be rendered.
         * If the response contains a "status" property set to "success", the form will be reset.
         * If the response contains a "status" property set to "error", the error message will be displayed next to the form fields.
         * If the response contains a "content" property, the document will be updated with the specified content.
         * 
         * @param {HTMLFormElement} form - The fire form to submit.
         * @return {void}
         */
        submitFireForm(form) {
            // Prevent duplicate form submits.
            if (this.isLoading) return;

            this.request(form.getAttribute('action'), form.getAttribute('method'), new FormData(form))
                .then((resp) => {
                    // If the response contains a "status" property set to "success", reset the form
                    if (resp && resp.status && resp.status == 'success') {
                        form.reset();
                    }

                    // If the response contains a "redirect" property, redirect the window to the specified URL
                    if (resp && resp.redirect) {
                        window.location.href = resp.redirect;
                    }

                    // If the response contains a "push" property, render the specified content
                    else if (resp && resp.push) {
                        this.renderFireContent(resp.push);
                    }

                    // If the response contains a "status" property set to "error", display the error message next to the form fields
                    else if (resp && resp.status && resp.message) {
                        for (const child of form.children) {
                            child.attributes.fire && (child.attributes.fire.nodeValue == resp.status ? (child.innerHTML = resp.message) : child.innerHTML = '');
                        }
                    }

                    // If the response contains a "content" property, update the document with the specified content
                    else if (resp && resp.content) {
                        this.updateDocument(resp);
                    }
                });
        }

        /**
         * Returns the current path (pathname + search + hash) of the window location.
         * @return {string}
         */
        currentPath() {
            return window.location.pathname + window.location.search + window.location.hash;
        }

        /**
         * Renders the content of the specified path.
         * If the response contains a "content" property, the document will be updated with the specified content.
         * If the response contains a "push" property, the window location will be updated to the specified URL.
         * If the response contains a "redirect" property, the window will be redirected to the specified URL.
         * 
         * @param {string} path - The path to render.
         * @param {boolean} uh - Whether to update the route history. Defaults to true.
         * @return {void}
         */
        renderFireContent(path, uh = true) {
            this.request(path)
                .then(data => {
                    if (!this.isError && data) {
                        if (data.content) {
                            // Update the route history if `uh` is true.
                            (uh && this.updateRouteHistory(path));
                            // Update the document with the specified content.
                            this.updateDocument(data);
                        } else if (data.push) {
                            // Update the window location to the specified URL.
                            this.route(data.push);
                        } else if (data.redirect) {
                            // Redirect the window to the specified URL.
                            window.location.href = data.redirect;
                        }
                    }
                });
        }

        /**
         * Updates the route history with the specified path.
         * @param {string} path - The path to update the route history with.
         * @return {void}
         */
        updateRouteHistory(path) {
            window.history.pushState({}, '', path);
        }

        
        /**
         * Updates the document with the specified content.
         * 
         * This method first calls the `beforeRender` action, then sets the document title and the content of the #fireContent element, 
         * and finally calls the `onRender` action.
         * @param {Object} data - The object with the content for the document.
         * @param {string} data.title - The new title for the document.
         * @param {string} data.content - The new content for the #fireContent element.
         * @param {Object<string, string>} data.blocks - The new content for the elements with the fire attribute.
         * @return {void}
         */
        updateDocument(data) {
            this.doAction('beforeRender');
            this.setDocumentTitle(data.title);
            this.setFireContent(data.content);
            this.setFireBlocks(data.blocks);
            this.doAction('onRender');
        }


        /**
         * Sets the document's title to the specified value.
         * 
         * @param {string} title - The new title for the document.
         */
        setDocumentTitle(title) {
            document.title = title;
        }
        /**
         * Sets the content of the #fireContent element to the specified value.
         * This method is used internally to update the page content.
         * It replaces the existing content with the new content and processes scripts separately.
         * @param {string} content - The new content for the #fireContent element.
         * @return {void}
         */
        setFireContent(content) {
            const fireContent = document.getElementById('fireContent');
            const div = document.createElement('div');
            div.innerHTML = content;

            // Replace existing content with new content
            fireContent.replaceChildren(...div.childNodes);

            // Process scripts separately
            div.querySelectorAll('script').forEach(scriptElement => {
                const script = document.createElement('script');

                // Set the type of the script element
                script.type = scriptElement.type || 'text/javascript';

                // Set the source of the script element if it has one
                if (scriptElement.src) {
                    script.src = scriptElement.src;
                } else {
                    // Set the content of the script element if it doesn't have a source
                    script.textContent = scriptElement.textContent;
                }

                // Add the script element to the document head and remove it after it's executed
                document.head.appendChild(script);
                document.head.removeChild(script);
            });
        }
        /**
         * Sets the content of the elements with the fire attribute to the specified values.
         * This method is used internally to update the page content.
         * It takes an object with the fire attribute values as keys and the new content as values.
         * It replaces the existing content with the new content.
         * @param {Object<string, string>} blocks - The object with the new content for the elements with the fire attribute.
         * @return {void}
         */
        setFireBlocks(blocks) {
            Object.entries(blocks).forEach(([key, value]) => {
                const element = document.querySelector(`[fire="${key}"]`);
                if (element) {
                    // Replace the existing content with the new content
                    element.innerHTML = value;
                }
            });
        }

        /**
         * Sends a request to the specified path and returns the JSON response.
         * This method is used internally to fetch content from the server.
         * It handles errors and timeouts and logs them to the console.
         * It also triggers the `beforeLoad` and `afterLoad` actions.
         * @param {string} path - The path of the request.
         * @param {string} [method="get"] - The method of the request.
         * @param {any} [data=null] - The data of the request.
         * @returns {Promise<any>} The JSON response of the request.
         * @throws {Error} If the request fails or times out.
         */
        async request(path, method = 'get', data = null) {
            this.isLoading = true;
            this.isError = false;
            this.doAction('beforeLoad');

            try {
                const resp = await fetch(path, {
                    method: method,
                    headers: {
                        "Accept": "application/json",
                        "Content-Agent": "fire-view"
                    },
                    redirect: "error",
                    body: data,
                    // Abort the request after this.timeout seconds
                    signal: AbortSignal.timeout(1000 * this.timeout),
                });

                // Return the JSON response
                // If the response is invalid JSON, catch the error and log it to the console
                return await resp.json()
                    .catch(error => this.fireError(error))
                    .finally(() => {
                        this.isLoading = false;
                        this.doAction('afterLoad');
                    });
            } catch (error) {
                this.fireError(error);
            }
        }

        /**
         * Handles errors during the request process.
         * 
         * This function sets the `isError` flag to true and the `isLoading` flag to false.
         * It triggers the `afterLoad` and `onError` actions and logs the error to the console.
         * 
         * @param {Error} error - The error object to be handled.
         */
        fireError(error) {
            this.isError = true;
            this.isLoading = false;

            this.doAction('afterLoad');
            this.doAction('onError');

            console.error('Fire Error: ' + error);
        }
    }

    /**
     * Create an instance of FireView on DOMContentLoaded event.
     * The instance is stored in window.fireView.
     */
    document.addEventListener('DOMContentLoaded', () => window.fireView = new FireView);
})();