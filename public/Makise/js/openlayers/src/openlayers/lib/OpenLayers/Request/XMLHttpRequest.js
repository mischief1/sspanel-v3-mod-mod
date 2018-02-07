// XMLHttpRequest.js Copyright (C) 2010 Sergey Ilinsky (http://www.ilinsky.com)
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

/**
 * @requires OpenLayers/Request.js
 */

(function () {

    // Save reference to earlier defined object implementation (if any)
    var oXMLHttpRequest    = window.XMLHttpRequest;

    // Define on browser type
    var bGecko    = !!window.controllers,
        bIE        = window.document.all && !window.opera,
        bIE7    = bIE && window.navigator.userAgent.match(/MSIE ([\.0-9]+)/) && RegExp.$1 == 7;

    // Constructor
    function cXMLHttpRequest() {
        this._object    = oXMLHttpRequest && !bIE7 ? new oXMLHttpRequest : new window.ActiveXObject("Microsoft.XMLHTTP");
        this._listeners    = [];
    };

    // BUGFIX: Firefox with Firebug installed would break pages if not executed
    if (bGecko && oXMLHttpRequest.wrapped)
        cXMLHttpRequest.wrapped    = oXMLHttpRequest.wrapped;

    // Constants
    cXMLHttpRequest.UNSENT                = 0;
    cXMLHttpRequest.OPENED                = 1;
    cXMLHttpRequest.HEADERS_RECEIVED    = 2;
    cXMLHttpRequest.LOADING                = 3;
    cXMLHttpRequest.DONE                = 4;

    // Public Properties
    cXMLHttpRequest.prototype.readyState    = cXMLHttpRequest.UNSENT;
    cXMLHttpRequest.prototype.responseText    = '';
    cXMLHttpRequest.prototype.responseXML    = null;
    cXMLHttpRequest.prototype.status        = 0;
    cXMLHttpRequest.prototype.statusText    = '';

    // Instance-level Events Handlers
    cXMLHttpRequest.prototype.onreadystatechange    = null;

    // Class-level Events Handlers
    cXMLHttpRequest.onreadystatechange    = null;
    cXMLHttpRequest.onopen                = null;
    cXMLHttpRequest.onsend                = null;
    cXMLHttpRequest.onabort                = null;

    // Public Methods
    cXMLHttpRequest.prototype.open    = function(sMethod, sUrl, bAsync, sUser, sPassword) {
        // Delete headers, required when object is reused
        delete this._headers;

        // When bAsync parameter value is omitted, use true as default
        if (arguments.length < 3)
            bAsync    = true;

        // Save async parameter for fixing Gecko bug with missing readystatechange in synchronous requests
        this._async        = bAsync;

        // Set the onreadystatechange handler
        var oRequest    = this,
            nState        = this.readyState,
            fOnUnload;

        // BUGFIX: IE - memory leak on page unload (inter-page leak)
        if (bIE && bAsync) {
            fOnUnload = function() {
                if (nState != cXMLHttpRequest.DONE) {
                    fCleanTransport(oRequest);
                    // Safe to abort here since onreadystatechange handler removed
                    oRequest.abort();
                }
            };
                window.attachEvent("onunload", fOnUnload);
        }

        // Add method sniffer
        if (cXMLHttpRequest.onopen)
            cXMLHttpRequest.onopen.apply(this, arguments);

        if (arguments.length > 4)
            this._object.open(sMethod, sUrl, bAsync, sUser, sPassword);
        else
        if (arguments.length > 3)
            this._object.open(sMethod, sUrl, bAsync, sUser);
        else
            this._object.open(sMethod, sUrl, bAsync);

        if (!bGecko && !bIE) {
            this.readyState    = cXMLHttpRequest.OPENED;
            fReadyStateChange(this);
        }

        this._object.onreadystatechange    = function() {
            if (bGecko && !bAsync)
                return;

            // Synchronize state
            oRequest.readyState        = oRequest._object.readyState;

            //
            fSynchronizeValues(oRequest);

            // BUGFIX: Firefox fires unnecessary DONE when aborting
            if (oRequest._aborted) {
                // Reset readyState to UNSENT
                oRequest.readyState    = cXMLHttpRequest.UNSENT;

                // Return now
                return;
            }

            if (oRequest.readyState == cXMLHttpRequest.DONE) {
                //
                fCleanTransport(oRequest);
// Uncomment this block if you need a fix for IE cache
/*
                // BUGFIX: IE - cache issue
                if (!oRequest._object.getResponseHeader("Date")) {
                    // Save object to cache
                    oRequest._cached    = oRequest._object;

                    // Instantiate a new transport object
                    cXMLHttpRequest.call(oRequest);

                    // Re-send request
                    if (sUser) {
                         if (sPassword)
                    oRequest._object.open(sMethod, sUrl, bAsync, sUser, sPassword);
                        else
                            oRequest._object.open(sMethod, sUrl, bAsync, sUser);
                    }
                    else
                        oRequest._object.open(sMethod, sUrl, bAsync);
                    oRequest._object.setRequestHeader("If-Modified-Since", oRequest._cached.getResponseHeader("Last-Modified") || new window.Date(0));
                    // Copy headers set
                    if (oRequest._headers)
                        for (var sHeader in oRequest._headers)
                            if (typeof oRequest._headers[sHeader] == "string")    // Some frameworks prototype objects with functions
                                oRequest._object.setRequestHeader(sHeader, oRequest._headers[sHeader]);

                    oRequest._object.onreadystatechange    = function() {
                        // Synchronize state
                        oRequest.readyState        = oRequest._object.readyState;

                        if (oRequest._aborted) {
                            //
                            oRequest.readyState    = cXMLHttpRequest.UNSENT;

                            // Return
                            return;
                        }

                        if (oRequest.readyState == cXMLHttpRequest.DONE) {
                            // Clean Object
                            fCleanTransport(oRequest);

                            // get cached request
                            if (oRequest.status == 304)
                                oRequest._object    = oRequest._cached;

                            //
                            delete oRequest._cached;

                            //
                            fSynchronizeValues(oRequest);

                            //
                            fReadyStateChange(oRequest);

                            // BUGFIX: IE - memory leak in interrupted
                            if (bIE && bAsync)
                                window.detachEvent("onunload", fOnUnload);
                        }
                    };
                    oRequest._object.send(null);

                    // Return now - wait until re-sent request is finished
                    return;
                };
*/
                // BUGFIX: IE - memory leak in interrupted
                if (bIE && bAsync)
                    window.detachEvent("onunload", fOnUnload);
            }

            // BUGFIX: Some browsers (Internet Explorer, Gecko) fire OPEN readystate twice
            if (nState != oRequest.readyState)
                fReadyStateChange(oRequest);

            nState    = oRequest.readyState;
        }
    };
    cXMLHttpRequest.prototype.send    = function(vData) {
        // Add method sniffer
        if (cXMLHttpRequest.onsend)
            cXMLHttpRequest.onsend.apply(this, arguments);

        // BUGFIX: Safari - fails sending documents created/modified dynamically, so an explicit serialization required
        // BUGFIX: IE - rewrites any custom mime-type to "text/xml" in case an XMLNode is sent
        // BUGFIX: Gecko - fails sending Element (this is up to the implementation either to standard)
  