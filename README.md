# Server Software

The three components are separated out in case they need to be on separate boxes, but they can all be on as little as one.

## Ingest Server

```bash
[root]$ apt-get install apache2 php libapache2-mod-php php-redis
```

(TODO apache2 config)

## Redis (mem cache) Server

```bash
[root]$ apt-get install redis-server
```

The config file is not included in the repo - mainly to avoid the password storage, but also there is only one thing to change (the port).

```bash
[root]$ vim /etc/redis/redis.conf
```

  * Change the port (as directed in the requirements).  I used 31098.
  * Remove/change the bind directive if running on a separate server from ingest/delivery.
  * Uncomment 'requirepass' and add a long password.

Save/close the file.

```bash
[root]$ service redis-server start
```

## Delivery Server

```bash
[root]$ apt-get install golang
```

Go's redis connector is available through Github, must be ran under the user running the Go application.

```bash
[user]$ go get get -u github.com/go-redis/redis
```

Update some paths for golang to run:

```bash
[user]$ vim ~/.bashrc
```

Add this to the end:

```
export GOPATH=$HOME/go
export PATH=$PATH:$GOROOT/bin:$GOPATH/bin
```

# TODOs

  * Rethink how the job queue works - it's currently configured with the pub/sub method.  If there is a request submitted while the delivery server is processing a request, it could be missed.
  * Add logging to the delivery script
  * Daemonize Go script to autostart on reboots
  * Add timestamps to all requests for benchmarking
