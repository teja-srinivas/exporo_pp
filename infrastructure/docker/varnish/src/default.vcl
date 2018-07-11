vcl 4.0;

sub vcl_recv {
    if (req.url ~ "/admin/login$") {
        return(synth(200, "Nope"));
    }

    if (req.method == "BAN") {
            ban("req.http.host == " + req.http.host +
                  " && req.url == " + req.url);

            return(synth(200, "Ban added"));
    }

    unset req.http.Cookie;
}

sub vcl_backend_response {
    unset beresp.http.set-cookie;
    unset beresp.http.Cache-Control;
    unset beresp.http.cookie;
    set beresp.ttl = 48h;
    unset beresp.http.X-Powered-By;
    unset beresp.http.X-Streams-Distribution;
}
