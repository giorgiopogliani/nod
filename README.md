# Nod
A simple cli to manage servers, server keys and hosts. You will have a database in `~/.config/nod/` and store all the config for your servers and hosts. So you don't need to remeber anything or keep track of differnent env files.

## Roadmap

- [x] create servers 
- [x] connect to a server with simple command
- [x] create new hosts with customizable nignx configuration
- [x] create mysql database command
- [ ] configure common stuff like php
- [ ] configure common stuff like users
- [ ] download backups
- [ ] multiple keys for a server (?)
- [ ] multiple users for a server (?)

## Create server
```
nod server:make --name myserver --ip 127.0.0.1
```

## Setup server
Setup your SSH credentials: username and private key. 
```
nod server:setup
```

## Create a host
```
nod host:make --server myserver --name "example.com" --base "/home/myserver/example.com" --root "/home/myserver/example.com/public"
```

## Install the configuration
You can pass `--ssl` to guess ssl certificate location when using letsencrypt. Or pass a `--sslpath` to specify the directory.
```
nod host:install 1 # id of the created host
```
## Start an SSH shell 
Setup your credentials
```
nod server:shell ServerName
```

------

## License

Nod is an open-source software licensed under the MIT license.
