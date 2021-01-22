server {

@if($ssl)
    listen                  443 ssl http2;
    listen                  [::]:443 ssl http2;
@else
    listen                  80;
    listen                  [::]:80;
@endif

    server_name             {{ implode(", ", $hostname) }}, {{ implode(", ", array_map(fn ($item) => "www.$item", $hostname)) }};
    # SSL

@if($ssl)
    @if($sslpath)
    ssl_certificate           {{ $sslpath }}/fullchain.pem;
    ssl_certificate_key       {{ $sslpath }}/privkey.pem;
    ssl_trusted_certificate   {{ $sslpath }}/chain.pem;
    @else
    ssl_certificate           /etc/letsencrypt/live/{{ $hostname }}/fullchain.pem;
    ssl_certificate_key       /etc/letsencrypt/live/{{ $hostname }}/privkey.pem;
    ssl_trusted_certificate   /etc/letsencrypt/live/{{ $hostname }}/chain.pem;
    @endif
@endif

    # security
    include                  globals/security.conf;

    # additional config
    include                 globals/compression.conf;
    include                 globals/general.conf;

    return 301 https://{{ $redirect }}$request_uri;
}

@if($ssl)
# http to https redirect
server {
    listen      80;
    listen      [::]:80;
    server_name {{ $hostname }}, www.{{ $hostname }};

    return 301 https://{{ $hostname }}$request_uri;
}
@endif
