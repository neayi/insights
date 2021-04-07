# Interaction management

APIs for interaction on pages

## Get the number of interactions for the page given




> Example request:

```bash
curl -X GET \
    -G "https://insights.vcap.me/api/page/1/counts?wiki_session_id=abc" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://insights.vcap.me/api/page/1/counts"
);

let params = {
    "wiki_session_id": "abc",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "follow": 1,
    "done": 0,
    "applause": 1
}
```
<div id="execution-results-GETapi-page--pageId--counts" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-page--pageId--counts"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-page--pageId--counts"></code></pre>
</div>
<div id="execution-error-GETapi-page--pageId--counts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-page--pageId--counts"></code></pre>
</div>
<form id="form-GETapi-page--pageId--counts" data-method="GET" data-path="api/page/{pageId}/counts" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-page--pageId--counts', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-page--pageId--counts" onclick="tryItOut('GETapi-page--pageId--counts');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-page--pageId--counts" onclick="cancelTryOut('GETapi-page--pageId--counts');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-page--pageId--counts" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/page/{pageId}/counts</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>pageId</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="pageId" data-endpoint="GETapi-page--pageId--counts" data-component="url" required  hidden>
<br>
The wiki page id</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>wiki_session_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="wiki_session_id" data-endpoint="GETapi-page--pageId--counts" data-component="query" required  hidden>
<br>
The wiki session id</p>
</form>


## Get the state of interaction for the user authenticated on the page given




> Example request:

```bash
curl -X GET \
    -G "https://insights.vcap.me/api/user/page/1?wiki_session_id=abc" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "https://insights.vcap.me/api/user/page/1"
);

let params = {
    "wiki_session_id": "abc",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{
    "state": {
        "done": false,
        "follow": false,
        "applause": true,
        "value": [],
        "page_id": 1
    },
    "counts": {
        "follow": 1,
        "done": 0,
        "applause": 1
    }
}
```
<div id="execution-results-GETapi-user-page--pageId-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-user-page--pageId-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user-page--pageId-"></code></pre>
</div>
<div id="execution-error-GETapi-user-page--pageId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user-page--pageId-"></code></pre>
</div>
<form id="form-GETapi-user-page--pageId-" data-method="GET" data-path="api/user/page/{pageId}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-user-page--pageId-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-user-page--pageId-" onclick="tryItOut('GETapi-user-page--pageId-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-user-page--pageId-" onclick="cancelTryOut('GETapi-user-page--pageId-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-user-page--pageId-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/user/page/{pageId}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>pageId</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="pageId" data-endpoint="GETapi-user-page--pageId-" data-component="url" required  hidden>
<br>
The wiki page id</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>wiki_session_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="wiki_session_id" data-endpoint="GETapi-user-page--pageId-" data-component="query" required  hidden>
<br>
The wiki session id</p>
</form>


## Add a interaction (follow, unfollow, done, undone, applause, unapplause) of the user authenticated to the page given




> Example request:

```bash
curl -X POST \
    "https://insights.vcap.me/api/page/1?wiki_session_id=abc" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"interactions":["rerum","modi"]}'

```

```javascript
const url = new URL(
    "https://insights.vcap.me/api/page/1"
);

let params = {
    "wiki_session_id": "abc",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "interactions": [
        "rerum",
        "modi"
    ]
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


<div id="execution-results-POSTapi-page--pageId-" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-page--pageId-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-page--pageId-"></code></pre>
</div>
<div id="execution-error-POSTapi-page--pageId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-page--pageId-"></code></pre>
</div>
<form id="form-POSTapi-page--pageId-" data-method="POST" data-path="api/page/{pageId}" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-page--pageId-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-page--pageId-" onclick="tryItOut('POSTapi-page--pageId-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-page--pageId-" onclick="cancelTryOut('POSTapi-page--pageId-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-page--pageId-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/page/{pageId}</code></b>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>pageId</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="pageId" data-endpoint="POSTapi-page--pageId-" data-component="url" required  hidden>
<br>
The wiki page id</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>wiki_session_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="wiki_session_id" data-endpoint="POSTapi-page--pageId-" data-component="query" required  hidden>
<br>
The wiki session id</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>interactions</code></b>&nbsp;&nbsp;<small>string[]</small>  &nbsp;
<input type="text" name="interactions.0" data-endpoint="POSTapi-page--pageId-" data-component="body" required  hidden>
<input type="text" name="interactions.1" data-endpoint="POSTapi-page--pageId-" data-component="body" hidden>
<br>
The user's interactions on the page.</p>

</form>



