# Nod
A simple cli to manage servers and server keys.

## Roadmap

- [x] create servers 
- [x] connect to a server with simple command
- [ ] multiple keys for a server (?)
- [ ] multiple users for a server (?)
- [ ] configure new projects/website with default nignx configuration
- [ ] configure common stuff like users, php, mysql
- [ ] download backups

## Create server
```
nod server:make --name user --ip 127.0.0.1
```

## Setup server
Setup your credentials
```
nod server:setup
```

## Start an SSH shell 
Setup your credentials
```
nod server:shell ServerName
```

------

## License

Nod is an open-source software licensed under the MIT license.
