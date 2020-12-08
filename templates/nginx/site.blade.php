server {
    @if($ssl)
    listen                  443 ssl http2;
    listen                  [::]:443 ssl http2;
    @else
    listen                  80;
    listen                  [::]:80;
    @endif
    server_name             {{ $hostname }};
    set $base               {{ $base }};
    root                    {{ $root }};

    # SSL
    @if($ssl)
    ssl_certificate           /etc/letsencrypt/live/{{ $hostname }}/fullchain.pem;
    ssl_certificate_key       /etc/letsencrypt/live/{{ $hostname }}/privkey.pem;
    ssl_trusted_certificate   /etc/letsencrypt/live/{{ $hostname }}/chain.pem;
    @endif

    # security
    include                  globals/security.conf;

    # logs
    error_log                $base/logs/error.log warn;
    access_log               $base/logs/access.log;

    # index.php
    index                   index.php;

    # charset
    charset                 utf-8;

    # index.php fallback
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # additional config
    include                 globals/compression.conf;
    include                 globals/general.conf;

    # handle .php
    location ~ \.php$ {
        include globals/php7.4-fastcgi.conf;
    }
}

@if($ssl)
# subdomains redirect
server {
    listen                  443 ssl http2;
    listen                  [::]:443 ssl http2;
    server_name             *.{{ $hostname }};

    # SSL
    ssl_certificate          /etc/letsencrypt/live/{{ $hostname }}/fullchain.pem;
    ssl_certificate_key      /etc/letsencrypt/live/{{ $hostname }}/privkey.pem;
    ssl_trusted_certificate  /etc/letsencrypt/live/{{ $hostname }}/chain.pem;

    return                  301 https://{{ $hostname }}$request_uri;
}
@endif

@if(!$ssl)
# HTTP redirect
server {
    listen      80;
    listen      [::]:80;
    server_name .{{ $hostname }};
    include     globals/letsencrypt.conf;

    location / {
        return 301 https://{{ $hostname }}$request_uri;
    }
}
@endif
