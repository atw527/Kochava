# Server Config

The three components are separated out in case they need to be on separate boxes, but they can all be on as little as one.

## Ingest Server

```bash
[root]$ apt-get install apache2 php libapache2-mod-php php-redis
```

The config is available in the repository, but some settings will vary depending on the desired hostname, etc.

I my example, I used "kochava.agstesting.com" and configured DNS in my separate DNS environment.  Change the domain to something else that's available to you.

The current application config requires SSL, so install Let's Encrypt or use your standard SSL provider.  Don't forget to alter the hostname.

```bash
[root]$ add-apt-repository ppa:certbot/certbot
[root]$ apt-get install python-certbot-apache
[root]$ certbot --apache -d kochava.agstesting.com
```

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

Don't forget to restart the service after closing the file.

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
