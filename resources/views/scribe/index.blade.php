<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>OpsBoard API</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "https://api.ops-board.dev.localhost";
        var useCsrf = Boolean(1);
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.9.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.9.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-client-management" class="tocify-header">
                <li class="tocify-item level-1" data-unique="client-management">
                    <a href="#client-management">Client Management</a>
                </li>
                                    <ul id="tocify-subheader-client-management" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="client-management-GETapi-clients">
                                <a href="#client-management-GETapi-clients">List clients.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="client-management-POSTapi-clients">
                                <a href="#client-management-POSTapi-clients">Create a client.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="client-management-GETapi-clients--id-">
                                <a href="#client-management-GETapi-clients--id-">Show a client.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="client-management-PUTapi-clients--id-">
                                <a href="#client-management-PUTapi-clients--id-">Update a client.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="client-management-DELETEapi-clients--id-">
                                <a href="#client-management-DELETEapi-clients--id-">Delete a client.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-customer-authentication" class="tocify-header">
                <li class="tocify-item level-1" data-unique="customer-authentication">
                    <a href="#customer-authentication">Customer Authentication</a>
                </li>
                                    <ul id="tocify-subheader-customer-authentication" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="customer-authentication-POSTapi-register">
                                <a href="#customer-authentication-POSTapi-register">Register a new customer.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-authentication-POSTapi-login">
                                <a href="#customer-authentication-POSTapi-login">Log a customer in.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-authentication-GETapi-me">
                                <a href="#customer-authentication-GETapi-me">Get the authenticated customer.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-authentication-POSTapi-logout">
                                <a href="#customer-authentication-POSTapi-logout">Log the current customer out.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-project-management" class="tocify-header">
                <li class="tocify-item level-1" data-unique="project-management">
                    <a href="#project-management">Project Management</a>
                </li>
                                    <ul id="tocify-subheader-project-management" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="project-management-GETapi-projects">
                                <a href="#project-management-GETapi-projects">List projects.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-POSTapi-projects">
                                <a href="#project-management-POSTapi-projects">Create a project.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-GETapi-projects--id-">
                                <a href="#project-management-GETapi-projects--id-">Show a project.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-PUTapi-projects--id-">
                                <a href="#project-management-PUTapi-projects--id-">Update a project.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-management-DELETEapi-projects--id-">
                                <a href="#project-management-DELETEapi-projects--id-">Delete a project.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-project-milestones" class="tocify-header">
                <li class="tocify-item level-1" data-unique="project-milestones">
                    <a href="#project-milestones">Project Milestones</a>
                </li>
                                    <ul id="tocify-subheader-project-milestones" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="project-milestones-PATCHapi-projects--project_id--milestones-reorder">
                                <a href="#project-milestones-PATCHapi-projects--project_id--milestones-reorder">Reorder milestones.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-milestones-GETapi-projects--project_id--milestones">
                                <a href="#project-milestones-GETapi-projects--project_id--milestones">List milestones for a project.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-milestones-POSTapi-projects--project_id--milestones">
                                <a href="#project-milestones-POSTapi-projects--project_id--milestones">Create a milestone.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-milestones-GETapi-projects--project_id--milestones--id-">
                                <a href="#project-milestones-GETapi-projects--project_id--milestones--id-">Show a milestone.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-milestones-PUTapi-projects--project_id--milestones--id-">
                                <a href="#project-milestones-PUTapi-projects--project_id--milestones--id-">Update a milestone.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-milestones-DELETEapi-projects--project_id--milestones--id-">
                                <a href="#project-milestones-DELETEapi-projects--project_id--milestones--id-">Delete a milestone.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-project-tasks" class="tocify-header">
                <li class="tocify-item level-1" data-unique="project-tasks">
                    <a href="#project-tasks">Project Tasks</a>
                </li>
                                    <ul id="tocify-subheader-project-tasks" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="project-tasks-PATCHapi-projects--project_id--tasks-reorder">
                                <a href="#project-tasks-PATCHapi-projects--project_id--tasks-reorder">Reorder tasks.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-tasks-GETapi-projects--project_id--tasks">
                                <a href="#project-tasks-GETapi-projects--project_id--tasks">List tasks for a project.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-tasks-POSTapi-projects--project_id--tasks">
                                <a href="#project-tasks-POSTapi-projects--project_id--tasks">Create a task.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-tasks-GETapi-projects--project_id--tasks--id-">
                                <a href="#project-tasks-GETapi-projects--project_id--tasks--id-">Show a task.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-tasks-PUTapi-projects--project_id--tasks--id-">
                                <a href="#project-tasks-PUTapi-projects--project_id--tasks--id-">Update a task.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="project-tasks-DELETEapi-projects--project_id--tasks--id-">
                                <a href="#project-tasks-DELETEapi-projects--project_id--tasks--id-">Delete a task.</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: April 11, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>Public HTTP API powering the OpsBoard application front-end.</p>
<aside>
    <strong>Base URL</strong>: <code>https://api.ops-board.dev.localhost</code>
</aside>
<pre><code>This documentation describes the HTTP endpoints exposed by the OpsBoard
backend to its first-party Next.js client. It is generated directly from
the Laravel route definitions and their form requests, so it always
reflects the code currently deployed.

&lt;aside&gt;Code examples are shown in the dark pane on the right (or inline on
mobile). Switch language with the tabs at the top-right.&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {session-cookie}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<pre><code>OpsBoard authenticates the first-party Next.js SPA via **cookie-based
sessions** (Laravel Sanctum SPA). To call a protected endpoint:

1. `GET /sanctum/csrf-cookie` to obtain an `XSRF-TOKEN` cookie.
2. `POST /api/login` with your credentials and `credentials: 'include'`.
3. Subsequent calls reuse the `laravel_session` cookie automatically.

Third-party and mobile clients may alternatively use a Sanctum bearer
token issued via `Customer::createToken()`.</code></pre>

        <h1 id="client-management">Client Management</h1>

    <p>APIs for managing clients owned by the authenticated customer.
Every endpoint is scoped to the caller's ownership; a customer can
only ever see and mutate their own clients.</p>

                                <h2 id="client-management-GETapi-clients">List clients.</h2>

<p>
</p>

<p>Returns a paginated list of the authenticated customer's clients.
Supports a text <code>search</code> across <code>name</code> and <code>company_name</code>, and a <code>status</code> filter.</p>

<span id="example-requests-GETapi-clients">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.ops-board.dev.localhost/api/clients?search=acme&amp;status=active&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/clients"
);

const params = {
    "search": "acme",
    "status": "active",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-clients">
            <blockquote>
            <p>Example response (200, Paginated list):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;customer_id&quot;: 1,
            &quot;name&quot;: &quot;Grace Hopper&quot;,
            &quot;company_name&quot;: &quot;Hopper Industries&quot;,
            &quot;email&quot;: &quot;grace@hopper.test&quot;,
            &quot;phone&quot;: &quot;+1 555 0123&quot;,
            &quot;status&quot;: &quot;active&quot;,
            &quot;notes&quot;: null,
            &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
            &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;...&quot;,
        &quot;last&quot;: &quot;...&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;per_page&quot;: 15,
        &quot;total&quot;: 1
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Guest):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-clients" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-clients"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-clients"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-clients" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-clients">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-clients" data-method="GET"
      data-path="api/clients"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-clients', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-clients"
                    onclick="tryItOut('GETapi-clients');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-clients"
                    onclick="cancelTryOut('GETapi-clients');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-clients"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/clients</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-clients"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-clients"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-clients"
               value="acme"
               data-component="query">
    <br>
<p>Case-insensitive partial match on <code>name</code> or <code>company_name</code>. Must not be greater than 255 characters. Example: <code>acme</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-clients"
               value="active"
               data-component="query">
    <br>
<p>Filter by lifecycle status. Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>lead</code></li> <li><code>active</code></li> <li><code>inactive</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-clients"
               value="15"
               data-component="query">
    <br>
<p>Number of results per page (1–100). Defaults to 15. Must be at least 1. Must not be greater than 100. Example: <code>15</code></p>
            </div>
                </form>

                    <h2 id="client-management-POSTapi-clients">Create a client.</h2>

<p>
</p>

<p>Creates a new client owned by the authenticated customer. The <code>customer_id</code>
is injected server-side from the session and cannot be set via the payload.</p>

<span id="example-requests-POSTapi-clients">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.ops-board.dev.localhost/api/clients" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Grace Hopper\",
    \"company_name\": \"Hopper Industries\",
    \"email\": \"grace@hopper.test\",
    \"phone\": \"+1 555 0123\",
    \"status\": \"lead\",
    \"notes\": \"Met at the Q2 sales conference.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/clients"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Grace Hopper",
    "company_name": "Hopper Industries",
    "email": "grace@hopper.test",
    "phone": "+1 555 0123",
    "status": "lead",
    "notes": "Met at the Q2 sales conference."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-clients">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;customer_id&quot;: 1,
        &quot;name&quot;: &quot;Grace Hopper&quot;,
        &quot;company_name&quot;: &quot;Hopper Industries&quot;,
        &quot;email&quot;: &quot;grace@hopper.test&quot;,
        &quot;phone&quot;: &quot;+1 555 0123&quot;,
        &quot;status&quot;: &quot;lead&quot;,
        &quot;notes&quot;: null,
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The email field is required.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;The email field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-clients" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-clients"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-clients"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-clients" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-clients">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-clients" data-method="POST"
      data-path="api/clients"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-clients', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-clients"
                    onclick="tryItOut('POSTapi-clients');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-clients"
                    onclick="cancelTryOut('POSTapi-clients');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-clients"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/clients</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-clients"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-clients"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-clients"
               value="Grace Hopper"
               data-component="body">
    <br>
<p>Primary contact or display name for the client. Must not be greater than 255 characters. Example: <code>Grace Hopper</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>company_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="company_name"                data-endpoint="POSTapi-clients"
               value="Hopper Industries"
               data-component="body">
    <br>
<p>Optional company or organization the client belongs to. Must not be greater than 255 characters. Example: <code>Hopper Industries</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-clients"
               value="grace@hopper.test"
               data-component="body">
    <br>
<p>Unique (per customer) contact email for the client. Must be a valid email address. Must not be greater than 255 characters. Example: <code>grace@hopper.test</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-clients"
               value="+1 555 0123"
               data-component="body">
    <br>
<p>Optional phone number. Must not be greater than 50 characters. Example: <code>+1 555 0123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-clients"
               value="lead"
               data-component="body">
    <br>
<p>Lifecycle status. One of <code>lead</code>, <code>active</code>, <code>inactive</code>. Example: <code>lead</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>lead</code></li> <li><code>active</code></li> <li><code>inactive</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="POSTapi-clients"
               value="Met at the Q2 sales conference."
               data-component="body">
    <br>
<p>Optional free-form notes about the client. Must not be greater than 5000 characters. Example: <code>Met at the Q2 sales conference.</code></p>
        </div>
        </form>

                    <h2 id="client-management-GETapi-clients--id-">Show a client.</h2>

<p>
</p>

<p>Returns a single client owned by the authenticated customer.</p>

<span id="example-requests-GETapi-clients--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.ops-board.dev.localhost/api/clients/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/clients/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-clients--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;customer_id&quot;: 1,
        &quot;name&quot;: &quot;Grace Hopper&quot;,
        &quot;company_name&quot;: &quot;Hopper Industries&quot;,
        &quot;email&quot;: &quot;grace@hopper.test&quot;,
        &quot;phone&quot;: &quot;+1 555 0123&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;notes&quot;: null,
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Client not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Client].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-clients--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-clients--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-clients--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-clients--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-clients--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-clients--id-" data-method="GET"
      data-path="api/clients/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-clients--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-clients--id-"
                    onclick="tryItOut('GETapi-clients--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-clients--id-"
                    onclick="cancelTryOut('GETapi-clients--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-clients--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/clients/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-clients--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-clients--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-clients--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the client. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="client-management-PUTapi-clients--id-">Update a client.</h2>

<p>
</p>

<p>Updates a client owned by the authenticated customer. All writable fields
must be provided (PUT semantics); partial updates are also accepted via PATCH.</p>

<span id="example-requests-PUTapi-clients--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "https://api.ops-board.dev.localhost/api/clients/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Grace Hopper\",
    \"company_name\": \"Hopper Industries\",
    \"email\": \"grace@hopper.test\",
    \"phone\": \"+1 555 0123\",
    \"status\": \"active\",
    \"notes\": \"Closed their first contract in Q2.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/clients/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Grace Hopper",
    "company_name": "Hopper Industries",
    "email": "grace@hopper.test",
    "phone": "+1 555 0123",
    "status": "active",
    "notes": "Closed their first contract in Q2."
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-clients--id-">
            <blockquote>
            <p>Example response (200, Updated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;customer_id&quot;: 1,
        &quot;name&quot;: &quot;Grace Hopper&quot;,
        &quot;company_name&quot;: &quot;Hopper Industries&quot;,
        &quot;email&quot;: &quot;grace@hopper.test&quot;,
        &quot;phone&quot;: &quot;+1 555 0123&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;notes&quot;: &quot;Upgraded to enterprise plan.&quot;,
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T10:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-clients--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-clients--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-clients--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-clients--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-clients--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-clients--id-" data-method="PUT"
      data-path="api/clients/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-clients--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-clients--id-"
                    onclick="tryItOut('PUTapi-clients--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-clients--id-"
                    onclick="cancelTryOut('PUTapi-clients--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-clients--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/clients/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/clients/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-clients--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-clients--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-clients--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the client. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-clients--id-"
               value="Grace Hopper"
               data-component="body">
    <br>
<p>Primary contact or display name for the client. Must not be greater than 255 characters. Example: <code>Grace Hopper</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>company_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="company_name"                data-endpoint="PUTapi-clients--id-"
               value="Hopper Industries"
               data-component="body">
    <br>
<p>Optional company or organization the client belongs to. Must not be greater than 255 characters. Example: <code>Hopper Industries</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="PUTapi-clients--id-"
               value="grace@hopper.test"
               data-component="body">
    <br>
<p>Unique (per customer) contact email for the client. Must be a valid email address. Must not be greater than 255 characters. Example: <code>grace@hopper.test</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="PUTapi-clients--id-"
               value="+1 555 0123"
               data-component="body">
    <br>
<p>Optional phone number. Must not be greater than 50 characters. Example: <code>+1 555 0123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-clients--id-"
               value="active"
               data-component="body">
    <br>
<p>Lifecycle status. One of <code>lead</code>, <code>active</code>, <code>inactive</code>. Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>lead</code></li> <li><code>active</code></li> <li><code>inactive</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="PUTapi-clients--id-"
               value="Closed their first contract in Q2."
               data-component="body">
    <br>
<p>Optional free-form notes about the client. Must not be greater than 5000 characters. Example: <code>Closed their first contract in Q2.</code></p>
        </div>
        </form>

                    <h2 id="client-management-DELETEapi-clients--id-">Delete a client.</h2>

<p>
</p>

<p>Permanently deletes a client owned by the authenticated customer.</p>

<span id="example-requests-DELETEapi-clients--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "https://api.ops-board.dev.localhost/api/clients/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/clients/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-clients--id-">
            <blockquote>
            <p>Example response (204, Deleted):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-clients--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-clients--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-clients--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-clients--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-clients--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-clients--id-" data-method="DELETE"
      data-path="api/clients/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-clients--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-clients--id-"
                    onclick="tryItOut('DELETEapi-clients--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-clients--id-"
                    onclick="cancelTryOut('DELETEapi-clients--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-clients--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/clients/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-clients--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-clients--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-clients--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the client. Example: <code>1</code></p>
            </div>
                    </form>

                <h1 id="customer-authentication">Customer Authentication</h1>

    

                                <h2 id="customer-authentication-POSTapi-register">Register a new customer.</h2>

<p>
</p>

<p>Creates a new customer account and immediately starts a session for them
via the <code>customer</code> guard. Subsequent requests from the SPA will be
authenticated via the session cookie set on this response.</p>

<span id="example-requests-POSTapi-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.ops-board.dev.localhost/api/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Ada Lovelace\",
    \"email\": \"ada@example.com\",
    \"password\": \"correct-horse-battery-staple\",
    \"password_confirmation\": \"correct-horse-battery-staple\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Ada Lovelace",
    "email": "ada@example.com",
    "password": "correct-horse-battery-staple",
    "password_confirmation": "correct-horse-battery-staple"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-register">
            <blockquote>
            <p>Example response (201, Account created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Ada Lovelace&quot;,
        &quot;email&quot;: &quot;ada@example.com&quot;,
        &quot;email_verified_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-04-10T12:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The email has already been taken.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;The email has already been taken.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-register" data-method="POST"
      data-path="api/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-register"
                    onclick="tryItOut('POSTapi-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-register"
                    onclick="cancelTryOut('POSTapi-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-register"
               value="Ada Lovelace"
               data-component="body">
    <br>
<p>Full display name of the customer. Must not be greater than 255 characters. Example: <code>Ada Lovelace</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-register"
               value="ada@example.com"
               data-component="body">
    <br>
<p>Unique email address used as the account identifier. Must be a valid email address. Must not be greater than 255 characters. Example: <code>ada@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-register"
               value="correct-horse-battery-staple"
               data-component="body">
    <br>
<p>Plain-text password. Must satisfy the default Laravel password policy. Example: <code>correct-horse-battery-staple</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-register"
               value="correct-horse-battery-staple"
               data-component="body">
    <br>
<p>Must match <code>password</code>. Example: <code>correct-horse-battery-staple</code></p>
        </div>
        </form>

                    <h2 id="customer-authentication-POSTapi-login">Log a customer in.</h2>

<p>
</p>

<p>Authenticates a customer against the <code>customer</code> guard using their
email + password. On success, a session cookie is set and subsequent
requests to protected endpoints will be authenticated via <code>auth:sanctum</code>.</p>

<span id="example-requests-POSTapi-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.ops-board.dev.localhost/api/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"ada@example.com\",
    \"password\": \"correct-horse-battery-staple\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "ada@example.com",
    "password": "correct-horse-battery-staple"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-login">
            <blockquote>
            <p>Example response (200, Authenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Ada Lovelace&quot;,
        &quot;email&quot;: &quot;ada@example.com&quot;,
        &quot;email_verified_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-04-10T12:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Bad credentials):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;These credentials do not match our records.&quot;,
    &quot;errors&quot;: {
        &quot;email&quot;: [
            &quot;These credentials do not match our records.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-login" data-method="POST"
      data-path="api/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-login"
                    onclick="tryItOut('POSTapi-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-login"
                    onclick="cancelTryOut('POSTapi-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-login"
               value="ada@example.com"
               data-component="body">
    <br>
<p>The email the customer registered with. Must be a valid email address. Example: <code>ada@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-login"
               value="correct-horse-battery-staple"
               data-component="body">
    <br>
<p>The customer password. Example: <code>correct-horse-battery-staple</code></p>
        </div>
        </form>

                    <h2 id="customer-authentication-GETapi-me">Get the authenticated customer.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns the currently authenticated customer's profile. Useful for the
SPA to bootstrap its auth state on page load.</p>

<span id="example-requests-GETapi-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.ops-board.dev.localhost/api/me" \
    --header "Authorization: Bearer {session-cookie}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/me"
);

const headers = {
    "Authorization": "Bearer {session-cookie}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-me">
            <blockquote>
            <p>Example response (200, Authenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Ada Lovelace&quot;,
        &quot;email&quot;: &quot;ada@example.com&quot;,
        &quot;email_verified_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-04-10T12:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthenticated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-me" data-method="GET"
      data-path="api/me"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-me"
                    onclick="tryItOut('GETapi-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-me"
                    onclick="cancelTryOut('GETapi-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-me"
               value="Bearer {session-cookie}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {session-cookie}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="customer-authentication-POSTapi-logout">Log the current customer out.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Terminates the session on the server side, invalidates the session
cookie and rotates the CSRF token. Call this from the SPA when the
user signs out.</p>

<span id="example-requests-POSTapi-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.ops-board.dev.localhost/api/logout" \
    --header "Authorization: Bearer {session-cookie}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/logout"
);

const headers = {
    "Authorization": "Bearer {session-cookie}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-logout">
            <blockquote>
            <p>Example response (204, Logged out):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-logout" data-method="POST"
      data-path="api/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-logout"
                    onclick="tryItOut('POSTapi-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-logout"
                    onclick="cancelTryOut('POSTapi-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-logout"
               value="Bearer {session-cookie}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {session-cookie}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="project-management">Project Management</h1>

    <p>APIs for managing projects belonging to clients owned by the authenticated
customer. The full ownership chain is <code>Customer → Client → Project</code>, and
every endpoint enforces it: a customer can only ever see and mutate
projects whose parent client they own.</p>

                                <h2 id="project-management-GETapi-projects">List projects.</h2>

<p>
</p>

<p>Returns a paginated list of projects across all clients owned by the
authenticated customer. Supports text search on <code>name</code> and <code>reference</code>,
filters on <code>client_id</code>, <code>status</code>, <code>priority</code>, <code>health</code>, and sorting on
<code>due_date</code>, <code>updated_at</code>, or <code>created_at</code>.</p>

<span id="example-requests-GETapi-projects">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.ops-board.dev.localhost/api/projects?search=website&amp;client_id=1&amp;status=active&amp;priority=high&amp;health=good&amp;sort=due_date&amp;direction=asc&amp;per_page=15" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects"
);

const params = {
    "search": "website",
    "client_id": "1",
    "status": "active",
    "priority": "high",
    "health": "good",
    "sort": "due_date",
    "direction": "asc",
    "per_page": "15",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-projects">
            <blockquote>
            <p>Example response (200, Paginated list):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;client_id&quot;: 1,
            &quot;name&quot;: &quot;Acme website redesign&quot;,
            &quot;reference&quot;: &quot;PRJ-2026-001&quot;,
            &quot;description&quot;: &quot;Full marketing site redesign.&quot;,
            &quot;status&quot;: &quot;active&quot;,
            &quot;priority&quot;: &quot;high&quot;,
            &quot;health&quot;: &quot;good&quot;,
            &quot;start_date&quot;: &quot;2026-05-01&quot;,
            &quot;due_date&quot;: &quot;2026-09-30&quot;,
            &quot;budget&quot;: &quot;25000.00&quot;,
            &quot;notes&quot;: null,
            &quot;client&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Grace Hopper&quot;,
                &quot;company_name&quot;: &quot;Hopper Industries&quot;
            },
            &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
            &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;...&quot;,
        &quot;last&quot;: &quot;...&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;per_page&quot;: 15,
        &quot;total&quot;: 1
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Guest):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Invalid filters):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The selected status is invalid.&quot;,
    &quot;errors&quot;: {
        &quot;status&quot;: [
            &quot;The selected status is invalid.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-projects" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-projects"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-projects"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-projects" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-projects">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-projects" data-method="GET"
      data-path="api/projects"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-projects', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-projects"
                    onclick="tryItOut('GETapi-projects');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-projects"
                    onclick="cancelTryOut('GETapi-projects');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-projects"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/projects</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-projects"
               value="website"
               data-component="query">
    <br>
<p>Case-insensitive partial match on <code>name</code> or <code>reference</code>. Must not be greater than 255 characters. Example: <code>website</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>client_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="client_id"                data-endpoint="GETapi-projects"
               value="1"
               data-component="query">
    <br>
<p>Restrict results to projects belonging to a specific client owned by the authenticated customer. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-projects"
               value="active"
               data-component="query">
    <br>
<p>Filter by project status. One of <code>draft</code>, <code>planned</code>, <code>active</code>, <code>on_hold</code>, <code>completed</code>, <code>cancelled</code>. Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>draft</code></li> <li><code>planned</code></li> <li><code>active</code></li> <li><code>on_hold</code></li> <li><code>completed</code></li> <li><code>cancelled</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>priority</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="priority"                data-endpoint="GETapi-projects"
               value="high"
               data-component="query">
    <br>
<p>Filter by priority. One of <code>low</code>, <code>medium</code>, <code>high</code>. Example: <code>high</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>low</code></li> <li><code>medium</code></li> <li><code>high</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>health</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="health"                data-endpoint="GETapi-projects"
               value="good"
               data-component="query">
    <br>
<p>Filter by project health. One of <code>good</code>, <code>warning</code>, <code>critical</code>. Example: <code>good</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>good</code></li> <li><code>warning</code></li> <li><code>critical</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-projects"
               value="due_date"
               data-component="query">
    <br>
<p>Sort column. One of <code>due_date</code>, <code>updated_at</code>, <code>created_at</code>. Defaults to <code>id</code>. Example: <code>due_date</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>due_date</code></li> <li><code>updated_at</code></li> <li><code>created_at</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>direction</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="direction"                data-endpoint="GETapi-projects"
               value="asc"
               data-component="query">
    <br>
<p>Sort direction. <code>asc</code> or <code>desc</code>. Defaults to <code>desc</code>. Example: <code>asc</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>asc</code></li> <li><code>desc</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-projects"
               value="15"
               data-component="query">
    <br>
<p>Number of results per page (1–100). Defaults to 15. Must be at least 1. Must not be greater than 100. Example: <code>15</code></p>
            </div>
                </form>

                    <h2 id="project-management-POSTapi-projects">Create a project.</h2>

<p>
</p>

<p>Creates a new project under one of the authenticated customer's clients.
The <code>client_id</code> must reference a client owned by the caller; otherwise
validation fails with 422.</p>

<span id="example-requests-POSTapi-projects">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.ops-board.dev.localhost/api/projects" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"client_id\": 1,
    \"name\": \"Acme website redesign\",
    \"reference\": \"PRJ-2026-001\",
    \"description\": \"Full marketing site redesign with CMS migration.\",
    \"status\": \"planned\",
    \"priority\": \"high\",
    \"health\": \"good\",
    \"start_date\": \"2026-05-01\",
    \"due_date\": \"2026-09-30\",
    \"budget\": 25000,
    \"notes\": \"Kick-off meeting scheduled for the first week.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "client_id": 1,
    "name": "Acme website redesign",
    "reference": "PRJ-2026-001",
    "description": "Full marketing site redesign with CMS migration.",
    "status": "planned",
    "priority": "high",
    "health": "good",
    "start_date": "2026-05-01",
    "due_date": "2026-09-30",
    "budget": 25000,
    "notes": "Kick-off meeting scheduled for the first week."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-projects">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;client_id&quot;: 1,
        &quot;name&quot;: &quot;Acme website redesign&quot;,
        &quot;reference&quot;: &quot;PRJ-2026-001&quot;,
        &quot;description&quot;: &quot;Full marketing site redesign.&quot;,
        &quot;status&quot;: &quot;planned&quot;,
        &quot;priority&quot;: &quot;high&quot;,
        &quot;health&quot;: &quot;good&quot;,
        &quot;start_date&quot;: &quot;2026-05-01&quot;,
        &quot;due_date&quot;: &quot;2026-09-30&quot;,
        &quot;budget&quot;: &quot;25000.00&quot;,
        &quot;notes&quot;: null,
        &quot;client&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Grace Hopper&quot;,
            &quot;company_name&quot;: &quot;Hopper Industries&quot;
        },
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The name field is required.&quot;,
    &quot;errors&quot;: {
        &quot;name&quot;: [
            &quot;The name field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-projects" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-projects"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-projects"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-projects" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-projects">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-projects" data-method="POST"
      data-path="api/projects"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-projects', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-projects"
                    onclick="tryItOut('POSTapi-projects');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-projects"
                    onclick="cancelTryOut('POSTapi-projects');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-projects"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/projects</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-projects"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>client_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="client_id"                data-endpoint="POSTapi-projects"
               value="1"
               data-component="body">
    <br>
<p>ID of the client this project belongs to. Must be owned by the authenticated customer. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-projects"
               value="Acme website redesign"
               data-component="body">
    <br>
<p>Display name of the project. Must not be greater than 255 characters. Example: <code>Acme website redesign</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reference"                data-endpoint="POSTapi-projects"
               value="PRJ-2026-001"
               data-component="body">
    <br>
<p>Optional internal reference code. Must not be greater than 100 characters. Example: <code>PRJ-2026-001</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-projects"
               value="Full marketing site redesign with CMS migration."
               data-component="body">
    <br>
<p>Optional long-form description of the project scope. Must not be greater than 5000 characters. Example: <code>Full marketing site redesign with CMS migration.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-projects"
               value="planned"
               data-component="body">
    <br>
<p>Lifecycle status. One of <code>draft</code>, <code>planned</code>, <code>active</code>, <code>on_hold</code>, <code>completed</code>, <code>cancelled</code>. Example: <code>planned</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>draft</code></li> <li><code>planned</code></li> <li><code>active</code></li> <li><code>on_hold</code></li> <li><code>completed</code></li> <li><code>cancelled</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>priority</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="priority"                data-endpoint="POSTapi-projects"
               value="high"
               data-component="body">
    <br>
<p>Priority level. One of <code>low</code>, <code>medium</code>, <code>high</code>. Example: <code>high</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>low</code></li> <li><code>medium</code></li> <li><code>high</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>health</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="health"                data-endpoint="POSTapi-projects"
               value="good"
               data-component="body">
    <br>
<p>Current health indicator. One of <code>good</code>, <code>warning</code>, <code>critical</code>. Example: <code>good</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>good</code></li> <li><code>warning</code></li> <li><code>critical</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_date"                data-endpoint="POSTapi-projects"
               value="2026-05-01"
               data-component="body">
    <br>
<p>Optional start date (ISO 8601 date). Must be a valid date. Example: <code>2026-05-01</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="POSTapi-projects"
               value="2026-09-30"
               data-component="body">
    <br>
<p>Optional due date (ISO 8601 date). Must be on or after <code>start_date</code> when both are provided. Must be a valid date. Must be a date after or equal to <code>start_date</code>. Example: <code>2026-09-30</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>budget</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="budget"                data-endpoint="POSTapi-projects"
               value="25000"
               data-component="body">
    <br>
<p>Optional budget amount (decimal, ≥ 0). Must be at least 0. Example: <code>25000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="POSTapi-projects"
               value="Kick-off meeting scheduled for the first week."
               data-component="body">
    <br>
<p>Optional free-form notes. Must not be greater than 5000 characters. Example: <code>Kick-off meeting scheduled for the first week.</code></p>
        </div>
        </form>

                    <h2 id="project-management-GETapi-projects--id-">Show a project.</h2>

<p>
</p>

<p>Returns a single project belonging to one of the authenticated
customer's clients, including a minimal client summary.</p>

<span id="example-requests-GETapi-projects--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.ops-board.dev.localhost/api/projects/2" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-projects--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;client_id&quot;: 1,
        &quot;name&quot;: &quot;Acme website redesign&quot;,
        &quot;reference&quot;: &quot;PRJ-2026-001&quot;,
        &quot;description&quot;: &quot;Full marketing site redesign.&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;priority&quot;: &quot;high&quot;,
        &quot;health&quot;: &quot;good&quot;,
        &quot;start_date&quot;: &quot;2026-05-01&quot;,
        &quot;due_date&quot;: &quot;2026-09-30&quot;,
        &quot;budget&quot;: &quot;25000.00&quot;,
        &quot;notes&quot;: null,
        &quot;client&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Grace Hopper&quot;,
            &quot;company_name&quot;: &quot;Hopper Industries&quot;
        },
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Project not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Project].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-projects--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-projects--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-projects--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-projects--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-projects--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-projects--id-" data-method="GET"
      data-path="api/projects/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-projects--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-projects--id-"
                    onclick="tryItOut('GETapi-projects--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-projects--id-"
                    onclick="cancelTryOut('GETapi-projects--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-projects--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/projects/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-projects--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-projects--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-projects--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    </form>

                    <h2 id="project-management-PUTapi-projects--id-">Update a project.</h2>

<p>
</p>

<p>Updates a project belonging to one of the authenticated customer's
clients. Reassigning to a <code>client_id</code> owned by another customer is
blocked at validation time (422), not at the policy layer.</p>

<span id="example-requests-PUTapi-projects--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "https://api.ops-board.dev.localhost/api/projects/2" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"client_id\": 1,
    \"name\": \"Acme website redesign\",
    \"reference\": \"PRJ-2026-001\",
    \"description\": \"Full marketing site redesign with CMS migration.\",
    \"status\": \"active\",
    \"priority\": \"medium\",
    \"health\": \"warning\",
    \"start_date\": \"2026-05-01\",
    \"due_date\": \"2026-09-30\",
    \"budget\": 30000,
    \"notes\": \"Pushed go-live by two weeks.\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "client_id": 1,
    "name": "Acme website redesign",
    "reference": "PRJ-2026-001",
    "description": "Full marketing site redesign with CMS migration.",
    "status": "active",
    "priority": "medium",
    "health": "warning",
    "start_date": "2026-05-01",
    "due_date": "2026-09-30",
    "budget": 30000,
    "notes": "Pushed go-live by two weeks."
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-projects--id-">
            <blockquote>
            <p>Example response (200, Updated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;client_id&quot;: 1,
        &quot;name&quot;: &quot;Acme website redesign&quot;,
        &quot;reference&quot;: &quot;PRJ-2026-001&quot;,
        &quot;description&quot;: &quot;Full marketing site redesign.&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;priority&quot;: &quot;high&quot;,
        &quot;health&quot;: &quot;warning&quot;,
        &quot;start_date&quot;: &quot;2026-05-01&quot;,
        &quot;due_date&quot;: &quot;2026-10-15&quot;,
        &quot;budget&quot;: &quot;30000.00&quot;,
        &quot;notes&quot;: &quot;Pushed go-live by two weeks.&quot;,
        &quot;client&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Grace Hopper&quot;,
            &quot;company_name&quot;: &quot;Hopper Industries&quot;
        },
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T10:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Foreign client_id):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The selected client id is invalid.&quot;,
    &quot;errors&quot;: {
        &quot;client_id&quot;: [
            &quot;The selected client id is invalid.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-projects--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-projects--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-projects--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-projects--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-projects--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-projects--id-" data-method="PUT"
      data-path="api/projects/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-projects--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-projects--id-"
                    onclick="tryItOut('PUTapi-projects--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-projects--id-"
                    onclick="cancelTryOut('PUTapi-projects--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-projects--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/projects/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/projects/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-projects--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-projects--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-projects--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>client_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="client_id"                data-endpoint="PUTapi-projects--id-"
               value="1"
               data-component="body">
    <br>
<p>ID of the client this project belongs to. Must be owned by the authenticated customer. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-projects--id-"
               value="Acme website redesign"
               data-component="body">
    <br>
<p>Display name of the project. Must not be greater than 255 characters. Example: <code>Acme website redesign</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="reference"                data-endpoint="PUTapi-projects--id-"
               value="PRJ-2026-001"
               data-component="body">
    <br>
<p>Optional internal reference code. Must not be greater than 100 characters. Example: <code>PRJ-2026-001</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-projects--id-"
               value="Full marketing site redesign with CMS migration."
               data-component="body">
    <br>
<p>Optional long-form description of the project scope. Must not be greater than 5000 characters. Example: <code>Full marketing site redesign with CMS migration.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-projects--id-"
               value="active"
               data-component="body">
    <br>
<p>Lifecycle status. Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>draft</code></li> <li><code>planned</code></li> <li><code>active</code></li> <li><code>on_hold</code></li> <li><code>completed</code></li> <li><code>cancelled</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>priority</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="priority"                data-endpoint="PUTapi-projects--id-"
               value="medium"
               data-component="body">
    <br>
<p>Priority level. Example: <code>medium</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>low</code></li> <li><code>medium</code></li> <li><code>high</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>health</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="health"                data-endpoint="PUTapi-projects--id-"
               value="warning"
               data-component="body">
    <br>
<p>Current health indicator. Example: <code>warning</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>good</code></li> <li><code>warning</code></li> <li><code>critical</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>start_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="start_date"                data-endpoint="PUTapi-projects--id-"
               value="2026-05-01"
               data-component="body">
    <br>
<p>Optional start date (ISO 8601 date). Must be a valid date. Example: <code>2026-05-01</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="PUTapi-projects--id-"
               value="2026-09-30"
               data-component="body">
    <br>
<p>Optional due date (ISO 8601 date). Must be on or after <code>start_date</code> when both are provided. Must be a valid date. Must be a date after or equal to <code>start_date</code>. Example: <code>2026-09-30</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>budget</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="budget"                data-endpoint="PUTapi-projects--id-"
               value="30000"
               data-component="body">
    <br>
<p>Optional budget amount (decimal, ≥ 0). Must be at least 0. Example: <code>30000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>notes</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="notes"                data-endpoint="PUTapi-projects--id-"
               value="Pushed go-live by two weeks."
               data-component="body">
    <br>
<p>Optional free-form notes. Must not be greater than 5000 characters. Example: <code>Pushed go-live by two weeks.</code></p>
        </div>
        </form>

                    <h2 id="project-management-DELETEapi-projects--id-">Delete a project.</h2>

<p>
</p>

<p>Permanently deletes a project belonging to one of the authenticated
customer's clients.</p>

<span id="example-requests-DELETEapi-projects--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "https://api.ops-board.dev.localhost/api/projects/2" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-projects--id-">
            <blockquote>
            <p>Example response (204, Deleted):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-projects--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-projects--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-projects--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-projects--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-projects--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-projects--id-" data-method="DELETE"
      data-path="api/projects/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-projects--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-projects--id-"
                    onclick="tryItOut('DELETEapi-projects--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-projects--id-"
                    onclick="cancelTryOut('DELETEapi-projects--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-projects--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/projects/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-projects--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-projects--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-projects--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    </form>

                <h1 id="project-milestones">Project Milestones</h1>

    <p>APIs for managing milestones inside a project. The full ownership chain
<code>Customer → Client → Project → ProjectMilestone</code> is enforced on every
endpoint: a customer can only ever see and mutate milestones whose parent
project they own through one of their clients.</p>
<p>Routes are nested under <code>/api/projects/{project}/milestones</code> so that
Laravel's scoped route binding rejects URLs whose milestone does not belong
to the project in the path with a 404, preventing existence leaks across
customers.</p>

                                <h2 id="project-milestones-PATCHapi-projects--project_id--milestones-reorder">Reorder milestones.</h2>

<p>
</p>

<p>Sets the order of milestones in a project. The provided list must contain
<strong>exactly</strong> the IDs of the project's current milestones (no missing, no
extras), in the desired final order. Positions are reassigned <code>1..N</code>
inside a transaction.</p>

<span id="example-requests-PATCHapi-projects--project_id--milestones-reorder">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "https://api.ops-board.dev.localhost/api/projects/2/milestones/reorder" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"milestone_ids\": [
        16
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/milestones/reorder"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "milestone_ids": [
        16
    ]
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-projects--project_id--milestones-reorder">
            <blockquote>
            <p>Example response (200, Reordered):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 4,
            &quot;position&quot;: 1,
            &quot;title&quot;: &quot;Discovery&quot;,
            &quot;status&quot;: &quot;done&quot;,
            &quot;project_id&quot;: 1,
            &quot;description&quot;: null,
            &quot;due_date&quot;: null,
            &quot;completed_at&quot;: &quot;2026-05-14T16:30:00+00:00&quot;,
            &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
            &quot;updated_at&quot;: &quot;2026-05-14T16:30:00+00:00&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;position&quot;: 2,
            &quot;title&quot;: &quot;Design ready&quot;,
            &quot;status&quot;: &quot;in_progress&quot;,
            &quot;project_id&quot;: 1,
            &quot;description&quot;: null,
            &quot;due_date&quot;: null,
            &quot;completed_at&quot;: null,
            &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
            &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Project not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Incomplete list):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The milestone_ids list must contain exactly 4 entries (one per existing milestone).&quot;,
    &quot;errors&quot;: {
        &quot;milestone_ids&quot;: [
            &quot;The milestone_ids list must contain exactly 4 entries (one per existing milestone).&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PATCHapi-projects--project_id--milestones-reorder" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-projects--project_id--milestones-reorder"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-projects--project_id--milestones-reorder"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-projects--project_id--milestones-reorder" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-projects--project_id--milestones-reorder">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-projects--project_id--milestones-reorder" data-method="PATCH"
      data-path="api/projects/{project_id}/milestones/reorder"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-projects--project_id--milestones-reorder', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-projects--project_id--milestones-reorder"
                    onclick="tryItOut('PATCHapi-projects--project_id--milestones-reorder');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-projects--project_id--milestones-reorder"
                    onclick="cancelTryOut('PATCHapi-projects--project_id--milestones-reorder');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-projects--project_id--milestones-reorder"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/projects/{project_id}/milestones/reorder</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-projects--project_id--milestones-reorder"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-projects--project_id--milestones-reorder"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="PATCHapi-projects--project_id--milestones-reorder"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="PATCHapi-projects--project_id--milestones-reorder"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>milestone_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="milestone_ids[0]"                data-endpoint="PATCHapi-projects--project_id--milestones-reorder"
               data-component="body">
        <input type="number" style="display: none"
               name="milestone_ids[1]"                data-endpoint="PATCHapi-projects--project_id--milestones-reorder"
               data-component="body">
    <br>

        </div>
        </form>

                    <h2 id="project-milestones-GETapi-projects--project_id--milestones">List milestones for a project.</h2>

<p>
</p>

<p>Returns the full ordered roadmap of a project. Pagination is intentionally
omitted: roadmaps are small (typically 5–30 items) and consumers want the
whole list at once. Results are ordered by <code>position</code>.</p>

<span id="example-requests-GETapi-projects--project_id--milestones">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.ops-board.dev.localhost/api/projects/2/milestones" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/milestones"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-projects--project_id--milestones">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;project_id&quot;: 1,
            &quot;title&quot;: &quot;Discovery&quot;,
            &quot;description&quot;: &quot;Stakeholder interviews and scoping.&quot;,
            &quot;status&quot;: &quot;done&quot;,
            &quot;position&quot;: 1,
            &quot;due_date&quot;: &quot;2026-05-15&quot;,
            &quot;completed_at&quot;: &quot;2026-05-14T16:30:00+00:00&quot;,
            &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
            &quot;updated_at&quot;: &quot;2026-05-14T16:30:00+00:00&quot;
        },
        {
            &quot;id&quot;: 2,
            &quot;project_id&quot;: 1,
            &quot;title&quot;: &quot;Design ready&quot;,
            &quot;description&quot;: null,
            &quot;status&quot;: &quot;in_progress&quot;,
            &quot;position&quot;: 2,
            &quot;due_date&quot;: &quot;2026-06-20&quot;,
            &quot;completed_at&quot;: null,
            &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
            &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Guest):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Project not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Project not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Project].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-projects--project_id--milestones" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-projects--project_id--milestones"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-projects--project_id--milestones"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-projects--project_id--milestones" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-projects--project_id--milestones">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-projects--project_id--milestones" data-method="GET"
      data-path="api/projects/{project_id}/milestones"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-projects--project_id--milestones', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-projects--project_id--milestones"
                    onclick="tryItOut('GETapi-projects--project_id--milestones');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-projects--project_id--milestones"
                    onclick="cancelTryOut('GETapi-projects--project_id--milestones');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-projects--project_id--milestones"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/projects/{project_id}/milestones</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-projects--project_id--milestones"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-projects--project_id--milestones"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="GETapi-projects--project_id--milestones"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="GETapi-projects--project_id--milestones"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="project-milestones-POSTapi-projects--project_id--milestones">Create a milestone.</h2>

<p>
</p>

<p>Adds a new milestone to a project owned by the authenticated customer.
The <code>position</code> is auto-assigned (max+1 within the project) and is not
settable from the payload. Setting <code>status</code> to <code>done</code> immediately stamps
<code>completed_at</code>.</p>

<span id="example-requests-POSTapi-projects--project_id--milestones">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.ops-board.dev.localhost/api/projects/2/milestones" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"title\": \"Design ready\",
    \"description\": \"All wireframes signed off by stakeholders.\",
    \"status\": \"pending\",
    \"due_date\": \"2026-06-15\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/milestones"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "title": "Design ready",
    "description": "All wireframes signed off by stakeholders.",
    "status": "pending",
    "due_date": "2026-06-15"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-projects--project_id--milestones">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 3,
        &quot;project_id&quot;: 1,
        &quot;title&quot;: &quot;Client UAT&quot;,
        &quot;description&quot;: null,
        &quot;status&quot;: &quot;pending&quot;,
        &quot;position&quot;: 3,
        &quot;due_date&quot;: &quot;2026-08-01&quot;,
        &quot;completed_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Project not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The title field is required.&quot;,
    &quot;errors&quot;: {
        &quot;title&quot;: [
            &quot;The title field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-projects--project_id--milestones" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-projects--project_id--milestones"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-projects--project_id--milestones"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-projects--project_id--milestones" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-projects--project_id--milestones">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-projects--project_id--milestones" data-method="POST"
      data-path="api/projects/{project_id}/milestones"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-projects--project_id--milestones', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-projects--project_id--milestones"
                    onclick="tryItOut('POSTapi-projects--project_id--milestones');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-projects--project_id--milestones"
                    onclick="cancelTryOut('POSTapi-projects--project_id--milestones');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-projects--project_id--milestones"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/projects/{project_id}/milestones</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-projects--project_id--milestones"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-projects--project_id--milestones"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="POSTapi-projects--project_id--milestones"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="POSTapi-projects--project_id--milestones"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-projects--project_id--milestones"
               value="Design ready"
               data-component="body">
    <br>
<p>Short label of the milestone (e.g. "Design ready"). Must not be greater than 255 characters. Example: <code>Design ready</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-projects--project_id--milestones"
               value="All wireframes signed off by stakeholders."
               data-component="body">
    <br>
<p>Optional long-form details about the milestone. Must not be greater than 5000 characters. Example: <code>All wireframes signed off by stakeholders.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-projects--project_id--milestones"
               value="pending"
               data-component="body">
    <br>
<p>Milestone status. One of <code>pending</code>, <code>in_progress</code>, <code>done</code>. Example: <code>pending</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>pending</code></li> <li><code>in_progress</code></li> <li><code>done</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="POSTapi-projects--project_id--milestones"
               value="2026-06-15"
               data-component="body">
    <br>
<p>Optional target date (ISO 8601 date). Must be a valid date. Example: <code>2026-06-15</code></p>
        </div>
        </form>

                    <h2 id="project-milestones-GETapi-projects--project_id--milestones--id-">Show a milestone.</h2>

<p>
</p>

<p>Returns a single milestone. The route binding is scoped, so the URL
<code>/api/projects/{project}/milestones/{milestone}</code> returns 404 if the
milestone does not belong to the project in the path.</p>

<span id="example-requests-GETapi-projects--project_id--milestones--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.ops-board.dev.localhost/api/projects/2/milestones/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/milestones/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-projects--project_id--milestones--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;project_id&quot;: 1,
        &quot;title&quot;: &quot;Design ready&quot;,
        &quot;description&quot;: null,
        &quot;status&quot;: &quot;in_progress&quot;,
        &quot;position&quot;: 2,
        &quot;due_date&quot;: &quot;2026-06-20&quot;,
        &quot;completed_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Milestone not found in this project):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\ProjectMilestone].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-projects--project_id--milestones--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-projects--project_id--milestones--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-projects--project_id--milestones--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-projects--project_id--milestones--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-projects--project_id--milestones--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-projects--project_id--milestones--id-" data-method="GET"
      data-path="api/projects/{project_id}/milestones/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-projects--project_id--milestones--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-projects--project_id--milestones--id-"
                    onclick="tryItOut('GETapi-projects--project_id--milestones--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-projects--project_id--milestones--id-"
                    onclick="cancelTryOut('GETapi-projects--project_id--milestones--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-projects--project_id--milestones--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/projects/{project_id}/milestones/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-projects--project_id--milestones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-projects--project_id--milestones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="GETapi-projects--project_id--milestones--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-projects--project_id--milestones--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the milestone. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="GETapi-projects--project_id--milestones--id-"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>milestone</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="milestone"                data-endpoint="GETapi-projects--project_id--milestones--id-"
               value="2"
               data-component="url">
    <br>
<p>The milestone ID. Example: <code>2</code></p>
            </div>
                    </form>

                    <h2 id="project-milestones-PUTapi-projects--project_id--milestones--id-">Update a milestone.</h2>

<p>
</p>

<p>Updates a milestone owned by the authenticated customer. Transitioning
<code>status</code> to <code>done</code> stamps <code>completed_at</code>; transitioning away from <code>done</code>
clears it. The model handles this automatically.</p>

<span id="example-requests-PUTapi-projects--project_id--milestones--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "https://api.ops-board.dev.localhost/api/projects/2/milestones/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"title\": \"Design ready\",
    \"description\": \"All wireframes signed off by stakeholders.\",
    \"status\": \"in_progress\",
    \"due_date\": \"2026-06-15\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/milestones/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "title": "Design ready",
    "description": "All wireframes signed off by stakeholders.",
    "status": "in_progress",
    "due_date": "2026-06-15"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-projects--project_id--milestones--id-">
            <blockquote>
            <p>Example response (200, Updated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;project_id&quot;: 1,
        &quot;title&quot;: &quot;Design ready&quot;,
        &quot;description&quot;: &quot;Sign-off from all stakeholders.&quot;,
        &quot;status&quot;: &quot;done&quot;,
        &quot;position&quot;: 2,
        &quot;due_date&quot;: &quot;2026-06-20&quot;,
        &quot;completed_at&quot;: &quot;2026-06-19T17:00:00+00:00&quot;,
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-06-19T17:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-projects--project_id--milestones--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-projects--project_id--milestones--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-projects--project_id--milestones--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-projects--project_id--milestones--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-projects--project_id--milestones--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-projects--project_id--milestones--id-" data-method="PUT"
      data-path="api/projects/{project_id}/milestones/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-projects--project_id--milestones--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-projects--project_id--milestones--id-"
                    onclick="tryItOut('PUTapi-projects--project_id--milestones--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-projects--project_id--milestones--id-"
                    onclick="cancelTryOut('PUTapi-projects--project_id--milestones--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-projects--project_id--milestones--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/projects/{project_id}/milestones/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/projects/{project_id}/milestones/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the milestone. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>milestone</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="milestone"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="2"
               data-component="url">
    <br>
<p>The milestone ID. Example: <code>2</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="Design ready"
               data-component="body">
    <br>
<p>Short label of the milestone. Must not be greater than 255 characters. Example: <code>Design ready</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="All wireframes signed off by stakeholders."
               data-component="body">
    <br>
<p>Optional long-form details. Must not be greater than 5000 characters. Example: <code>All wireframes signed off by stakeholders.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="in_progress"
               data-component="body">
    <br>
<p>Milestone status. One of <code>pending</code>, <code>in_progress</code>, <code>done</code>. Setting it to <code>done</code> automatically stamps <code>completed_at</code>; moving back to another status clears it. Example: <code>in_progress</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>pending</code></li> <li><code>in_progress</code></li> <li><code>done</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="PUTapi-projects--project_id--milestones--id-"
               value="2026-06-15"
               data-component="body">
    <br>
<p>Optional target date (ISO 8601 date). Must be a valid date. Example: <code>2026-06-15</code></p>
        </div>
        </form>

                    <h2 id="project-milestones-DELETEapi-projects--project_id--milestones--id-">Delete a milestone.</h2>

<p>
</p>

<p>Permanently deletes a milestone from a project owned by the authenticated
customer. Existing positions are not compacted; the next reorder or
create call handles ordering correctly.</p>

<span id="example-requests-DELETEapi-projects--project_id--milestones--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "https://api.ops-board.dev.localhost/api/projects/2/milestones/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/milestones/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-projects--project_id--milestones--id-">
            <blockquote>
            <p>Example response (204, Deleted):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-projects--project_id--milestones--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-projects--project_id--milestones--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-projects--project_id--milestones--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-projects--project_id--milestones--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-projects--project_id--milestones--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-projects--project_id--milestones--id-" data-method="DELETE"
      data-path="api/projects/{project_id}/milestones/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-projects--project_id--milestones--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-projects--project_id--milestones--id-"
                    onclick="tryItOut('DELETEapi-projects--project_id--milestones--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-projects--project_id--milestones--id-"
                    onclick="cancelTryOut('DELETEapi-projects--project_id--milestones--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-projects--project_id--milestones--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/projects/{project_id}/milestones/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-projects--project_id--milestones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-projects--project_id--milestones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="DELETEapi-projects--project_id--milestones--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-projects--project_id--milestones--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the milestone. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="DELETEapi-projects--project_id--milestones--id-"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>milestone</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="milestone"                data-endpoint="DELETEapi-projects--project_id--milestones--id-"
               value="2"
               data-component="url">
    <br>
<p>The milestone ID. Example: <code>2</code></p>
            </div>
                    </form>

                <h1 id="project-tasks">Project Tasks</h1>

    <p>APIs for managing tasks inside a project. The full ownership chain
<code>Customer → Client → Project → Task</code> is enforced on every endpoint:
a customer can only ever see and mutate tasks whose parent project they
own through one of their clients. A task may optionally be linked to a
milestone — that milestone must always belong to the <strong>same</strong> project.</p>
<p>Routes are nested under <code>/api/projects/{project}/tasks</code> so that Laravel's
scoped route binding rejects URLs whose task does not belong to the
project in the path with a 404, preventing existence leaks across
customers.</p>

                                <h2 id="project-tasks-PATCHapi-projects--project_id--tasks-reorder">Reorder tasks.</h2>

<p>
</p>

<p>Sets the order of tasks in a project. The provided list must contain
<strong>exactly</strong> the IDs of the project's current tasks (no missing, no
extras), in the desired final order. Positions are reassigned <code>1..N</code>
inside a transaction.</p>

<span id="example-requests-PATCHapi-projects--project_id--tasks-reorder">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "https://api.ops-board.dev.localhost/api/projects/2/tasks/reorder" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"task_ids\": [
        16
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/tasks/reorder"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "task_ids": [
        16
    ]
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-projects--project_id--tasks-reorder">
            <blockquote>
            <p>Example response (200, Reordered):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 4,
            &quot;project_id&quot;: 1,
            &quot;project_milestone_id&quot;: null,
            &quot;title&quot;: &quot;Brief&quot;,
            &quot;description&quot;: null,
            &quot;status&quot;: &quot;todo&quot;,
            &quot;priority&quot;: &quot;medium&quot;,
            &quot;position&quot;: 1,
            &quot;due_date&quot;: null,
            &quot;completed_at&quot;: null,
            &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
            &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Project not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Incomplete list):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The task_ids list must contain exactly 4 entries (one per existing task).&quot;,
    &quot;errors&quot;: {
        &quot;task_ids&quot;: [
            &quot;The task_ids list must contain exactly 4 entries (one per existing task).&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PATCHapi-projects--project_id--tasks-reorder" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-projects--project_id--tasks-reorder"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-projects--project_id--tasks-reorder"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-projects--project_id--tasks-reorder" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-projects--project_id--tasks-reorder">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-projects--project_id--tasks-reorder" data-method="PATCH"
      data-path="api/projects/{project_id}/tasks/reorder"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-projects--project_id--tasks-reorder', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-projects--project_id--tasks-reorder"
                    onclick="tryItOut('PATCHapi-projects--project_id--tasks-reorder');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-projects--project_id--tasks-reorder"
                    onclick="cancelTryOut('PATCHapi-projects--project_id--tasks-reorder');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-projects--project_id--tasks-reorder"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/projects/{project_id}/tasks/reorder</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-projects--project_id--tasks-reorder"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-projects--project_id--tasks-reorder"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="PATCHapi-projects--project_id--tasks-reorder"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="PATCHapi-projects--project_id--tasks-reorder"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>task_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="task_ids[0]"                data-endpoint="PATCHapi-projects--project_id--tasks-reorder"
               data-component="body">
        <input type="number" style="display: none"
               name="task_ids[1]"                data-endpoint="PATCHapi-projects--project_id--tasks-reorder"
               data-component="body">
    <br>

        </div>
        </form>

                    <h2 id="project-tasks-GETapi-projects--project_id--tasks">List tasks for a project.</h2>

<p>
</p>

<p>Returns the full ordered list of tasks for a project. Pagination is
intentionally omitted: a project board is small and consumers want the
whole list at once. Results are ordered by <code>position</code>. Supports filtering
by <code>status</code>, <code>priority</code>, <code>project_milestone_id</code>, and a free-text search
on <code>title</code>.</p>

<span id="example-requests-GETapi-projects--project_id--tasks">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.ops-board.dev.localhost/api/projects/2/tasks?status=in_progress&amp;priority=high&amp;project_milestone_id=4&amp;search=deploy" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"project_milestone_id\": 16,
    \"search\": \"n\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/tasks"
);

const params = {
    "status": "in_progress",
    "priority": "high",
    "project_milestone_id": "4",
    "search": "deploy",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "project_milestone_id": 16,
    "search": "n"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-projects--project_id--tasks">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;project_id&quot;: 1,
            &quot;project_milestone_id&quot;: 2,
            &quot;title&quot;: &quot;Write the technical brief&quot;,
            &quot;description&quot;: &quot;Cover the API contract and the rollout plan.&quot;,
            &quot;status&quot;: &quot;in_progress&quot;,
            &quot;priority&quot;: &quot;high&quot;,
            &quot;position&quot;: 1,
            &quot;due_date&quot;: &quot;2026-05-15&quot;,
            &quot;completed_at&quot;: null,
            &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
            &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Guest):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Project not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Project not found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Project].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-projects--project_id--tasks" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-projects--project_id--tasks"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-projects--project_id--tasks"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-projects--project_id--tasks" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-projects--project_id--tasks">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-projects--project_id--tasks" data-method="GET"
      data-path="api/projects/{project_id}/tasks"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-projects--project_id--tasks', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-projects--project_id--tasks"
                    onclick="tryItOut('GETapi-projects--project_id--tasks');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-projects--project_id--tasks"
                    onclick="cancelTryOut('GETapi-projects--project_id--tasks');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-projects--project_id--tasks"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/projects/{project_id}/tasks</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-projects--project_id--tasks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-projects--project_id--tasks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="GETapi-projects--project_id--tasks"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="GETapi-projects--project_id--tasks"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-projects--project_id--tasks"
               value="in_progress"
               data-component="query">
    <br>
<p>Filter by status (<code>todo</code>, <code>in_progress</code>, <code>done</code>). Example: <code>in_progress</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>priority</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="priority"                data-endpoint="GETapi-projects--project_id--tasks"
               value="high"
               data-component="query">
    <br>
<p>Filter by priority (<code>low</code>, <code>medium</code>, <code>high</code>). Example: <code>high</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_milestone_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_milestone_id"                data-endpoint="GETapi-projects--project_id--tasks"
               value="4"
               data-component="query">
    <br>
<p>Filter by milestone. Use <code>0</code> (or omit) to skip. Example: <code>4</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-projects--project_id--tasks"
               value="deploy"
               data-component="query">
    <br>
<p>Free-text search on the title. Example: <code>deploy</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="GETapi-projects--project_id--tasks"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>priority</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="priority"                data-endpoint="GETapi-projects--project_id--tasks"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>project_milestone_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_milestone_id"                data-endpoint="GETapi-projects--project_id--tasks"
               value="16"
               data-component="body">
    <br>
<p>Example: <code>16</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-projects--project_id--tasks"
               value="n"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>n</code></p>
        </div>
        </form>

                    <h2 id="project-tasks-POSTapi-projects--project_id--tasks">Create a task.</h2>

<p>
</p>

<p>Adds a new task to a project owned by the authenticated customer. The
<code>position</code> is auto-assigned (max+1 within the project) and is not
settable from the payload. Setting <code>status</code> to <code>done</code> immediately stamps
<code>completed_at</code>. If <code>project_milestone_id</code> is provided, that milestone
must belong to the <strong>same</strong> project.</p>

<span id="example-requests-POSTapi-projects--project_id--tasks">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://api.ops-board.dev.localhost/api/projects/2/tasks" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"title\": \"Write the technical brief\",
    \"description\": \"Cover the API contract, the data flow and the rollout plan.\",
    \"status\": \"todo\",
    \"priority\": \"medium\",
    \"due_date\": \"2026-06-15\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/tasks"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "title": "Write the technical brief",
    "description": "Cover the API contract, the data flow and the rollout plan.",
    "status": "todo",
    "priority": "medium",
    "due_date": "2026-06-15"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-projects--project_id--tasks">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 3,
        &quot;project_id&quot;: 1,
        &quot;project_milestone_id&quot;: null,
        &quot;title&quot;: &quot;Deploy v1 to production&quot;,
        &quot;description&quot;: null,
        &quot;status&quot;: &quot;todo&quot;,
        &quot;priority&quot;: &quot;medium&quot;,
        &quot;position&quot;: 3,
        &quot;due_date&quot;: &quot;2026-08-01&quot;,
        &quot;completed_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Project not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The title field is required.&quot;,
    &quot;errors&quot;: {
        &quot;title&quot;: [
            &quot;The title field is required.&quot;
        ]
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-projects--project_id--tasks" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-projects--project_id--tasks"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-projects--project_id--tasks"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-projects--project_id--tasks" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-projects--project_id--tasks">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-projects--project_id--tasks" data-method="POST"
      data-path="api/projects/{project_id}/tasks"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-projects--project_id--tasks', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-projects--project_id--tasks"
                    onclick="tryItOut('POSTapi-projects--project_id--tasks');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-projects--project_id--tasks"
                    onclick="cancelTryOut('POSTapi-projects--project_id--tasks');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-projects--project_id--tasks"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/projects/{project_id}/tasks</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-projects--project_id--tasks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-projects--project_id--tasks"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="POSTapi-projects--project_id--tasks"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="POSTapi-projects--project_id--tasks"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="POSTapi-projects--project_id--tasks"
               value="Write the technical brief"
               data-component="body">
    <br>
<p>Short label of the task (e.g. "Write the technical brief"). Must not be greater than 255 characters. Example: <code>Write the technical brief</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-projects--project_id--tasks"
               value="Cover the API contract, the data flow and the rollout plan."
               data-component="body">
    <br>
<p>Optional long-form details about the task. Must not be greater than 5000 characters. Example: <code>Cover the API contract, the data flow and the rollout plan.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-projects--project_id--tasks"
               value="todo"
               data-component="body">
    <br>
<p>Task status. One of <code>todo</code>, <code>in_progress</code>, <code>done</code>. Setting it to <code>done</code> automatically stamps <code>completed_at</code>. Example: <code>todo</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>todo</code></li> <li><code>in_progress</code></li> <li><code>done</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>priority</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="priority"                data-endpoint="POSTapi-projects--project_id--tasks"
               value="medium"
               data-component="body">
    <br>
<p>Task priority. One of <code>low</code>, <code>medium</code>, <code>high</code>. Example: <code>medium</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>low</code></li> <li><code>medium</code></li> <li><code>high</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="POSTapi-projects--project_id--tasks"
               value="2026-06-15"
               data-component="body">
    <br>
<p>Optional target date (ISO 8601 date). Must be a valid date. Example: <code>2026-06-15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>project_milestone_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_milestone_id"                data-endpoint="POSTapi-projects--project_id--tasks"
               value=""
               data-component="body">
    <br>
<p>Optional milestone of the <strong>same</strong> project this task belongs to.</p>
        </div>
        </form>

                    <h2 id="project-tasks-GETapi-projects--project_id--tasks--id-">Show a task.</h2>

<p>
</p>

<p>Returns a single task. The route binding is scoped, so the URL
<code>/api/projects/{project}/tasks/{task}</code> returns 404 if the task does
not belong to the project in the path.</p>

<span id="example-requests-GETapi-projects--project_id--tasks--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api.ops-board.dev.localhost/api/projects/2/tasks/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/tasks/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-projects--project_id--tasks--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;project_id&quot;: 1,
        &quot;project_milestone_id&quot;: null,
        &quot;title&quot;: &quot;Validate hero copy&quot;,
        &quot;description&quot;: null,
        &quot;status&quot;: &quot;todo&quot;,
        &quot;priority&quot;: &quot;low&quot;,
        &quot;position&quot;: 2,
        &quot;due_date&quot;: null,
        &quot;completed_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Task not found in this project):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Task].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-projects--project_id--tasks--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-projects--project_id--tasks--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-projects--project_id--tasks--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-projects--project_id--tasks--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-projects--project_id--tasks--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-projects--project_id--tasks--id-" data-method="GET"
      data-path="api/projects/{project_id}/tasks/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-projects--project_id--tasks--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-projects--project_id--tasks--id-"
                    onclick="tryItOut('GETapi-projects--project_id--tasks--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-projects--project_id--tasks--id-"
                    onclick="cancelTryOut('GETapi-projects--project_id--tasks--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-projects--project_id--tasks--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/projects/{project_id}/tasks/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-projects--project_id--tasks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-projects--project_id--tasks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="GETapi-projects--project_id--tasks--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-projects--project_id--tasks--id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the task. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="GETapi-projects--project_id--tasks--id-"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>task</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="task"                data-endpoint="GETapi-projects--project_id--tasks--id-"
               value="2"
               data-component="url">
    <br>
<p>The task ID. Example: <code>2</code></p>
            </div>
                    </form>

                    <h2 id="project-tasks-PUTapi-projects--project_id--tasks--id-">Update a task.</h2>

<p>
</p>

<p>Updates a task owned by the authenticated customer. Transitioning
<code>status</code> to <code>done</code> stamps <code>completed_at</code>; transitioning away from <code>done</code>
clears it. The model handles this automatically. Send
<code>project_milestone_id: null</code> to detach the task from its milestone.</p>

<span id="example-requests-PUTapi-projects--project_id--tasks--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "https://api.ops-board.dev.localhost/api/projects/2/tasks/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"title\": \"Write the technical brief\",
    \"description\": \"Cover the API contract, the data flow and the rollout plan.\",
    \"status\": \"in_progress\",
    \"priority\": \"high\",
    \"due_date\": \"2026-06-15\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/tasks/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "title": "Write the technical brief",
    "description": "Cover the API contract, the data flow and the rollout plan.",
    "status": "in_progress",
    "priority": "high",
    "due_date": "2026-06-15"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-projects--project_id--tasks--id-">
            <blockquote>
            <p>Example response (200, Updated):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;project_id&quot;: 1,
        &quot;project_milestone_id&quot;: 4,
        &quot;title&quot;: &quot;Validate hero copy&quot;,
        &quot;description&quot;: &quot;Sign-off from copywriter and PM.&quot;,
        &quot;status&quot;: &quot;done&quot;,
        &quot;priority&quot;: &quot;low&quot;,
        &quot;position&quot;: 2,
        &quot;due_date&quot;: null,
        &quot;completed_at&quot;: &quot;2026-06-19T17:00:00+00:00&quot;,
        &quot;created_at&quot;: &quot;2026-04-11T09:00:00+00:00&quot;,
        &quot;updated_at&quot;: &quot;2026-06-19T17:00:00+00:00&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-projects--project_id--tasks--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-projects--project_id--tasks--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-projects--project_id--tasks--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-projects--project_id--tasks--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-projects--project_id--tasks--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-projects--project_id--tasks--id-" data-method="PUT"
      data-path="api/projects/{project_id}/tasks/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-projects--project_id--tasks--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-projects--project_id--tasks--id-"
                    onclick="tryItOut('PUTapi-projects--project_id--tasks--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-projects--project_id--tasks--id-"
                    onclick="cancelTryOut('PUTapi-projects--project_id--tasks--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-projects--project_id--tasks--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/projects/{project_id}/tasks/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/projects/{project_id}/tasks/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the task. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>task</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="task"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="2"
               data-component="url">
    <br>
<p>The task ID. Example: <code>2</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>title</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="title"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="Write the technical brief"
               data-component="body">
    <br>
<p>Short label of the task. Must not be greater than 255 characters. Example: <code>Write the technical brief</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="Cover the API contract, the data flow and the rollout plan."
               data-component="body">
    <br>
<p>Optional long-form details. Must not be greater than 5000 characters. Example: <code>Cover the API contract, the data flow and the rollout plan.</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="in_progress"
               data-component="body">
    <br>
<p>Task status. One of <code>todo</code>, <code>in_progress</code>, <code>done</code>. Setting it to <code>done</code> automatically stamps <code>completed_at</code>; moving back to another status clears it. Example: <code>in_progress</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>todo</code></li> <li><code>in_progress</code></li> <li><code>done</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>priority</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="priority"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="high"
               data-component="body">
    <br>
<p>Task priority. One of <code>low</code>, <code>medium</code>, <code>high</code>. Example: <code>high</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>low</code></li> <li><code>medium</code></li> <li><code>high</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value="2026-06-15"
               data-component="body">
    <br>
<p>Optional target date (ISO 8601 date). Must be a valid date. Example: <code>2026-06-15</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>project_milestone_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_milestone_id"                data-endpoint="PUTapi-projects--project_id--tasks--id-"
               value=""
               data-component="body">
    <br>
<p>Milestone of the <strong>same</strong> project. Send <code>null</code> to detach.</p>
        </div>
        </form>

                    <h2 id="project-tasks-DELETEapi-projects--project_id--tasks--id-">Delete a task.</h2>

<p>
</p>

<p>Permanently deletes a task from a project owned by the authenticated
customer. Existing positions are not compacted; the next reorder or
create call handles ordering correctly.</p>

<span id="example-requests-DELETEapi-projects--project_id--tasks--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "https://api.ops-board.dev.localhost/api/projects/2/tasks/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api.ops-board.dev.localhost/api/projects/2/tasks/16"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-projects--project_id--tasks--id-">
            <blockquote>
            <p>Example response (204, Deleted):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (403, Not owned by caller):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;This action is unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-projects--project_id--tasks--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-projects--project_id--tasks--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-projects--project_id--tasks--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-projects--project_id--tasks--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-projects--project_id--tasks--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-projects--project_id--tasks--id-" data-method="DELETE"
      data-path="api/projects/{project_id}/tasks/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-projects--project_id--tasks--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-projects--project_id--tasks--id-"
                    onclick="tryItOut('DELETEapi-projects--project_id--tasks--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-projects--project_id--tasks--id-"
                    onclick="cancelTryOut('DELETEapi-projects--project_id--tasks--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-projects--project_id--tasks--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/projects/{project_id}/tasks/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-projects--project_id--tasks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-projects--project_id--tasks--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project_id"                data-endpoint="DELETEapi-projects--project_id--tasks--id-"
               value="2"
               data-component="url">
    <br>
<p>The ID of the project. Example: <code>2</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-projects--project_id--tasks--id-"
               value="16"
               data-component="url">
    <br>
<p>The ID of the task. Example: <code>16</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>project</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="project"                data-endpoint="DELETEapi-projects--project_id--tasks--id-"
               value="1"
               data-component="url">
    <br>
<p>The project ID. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>task</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="task"                data-endpoint="DELETEapi-projects--project_id--tasks--id-"
               value="2"
               data-component="url">
    <br>
<p>The task ID. Example: <code>2</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
