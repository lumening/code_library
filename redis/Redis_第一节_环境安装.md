# 第一节 Redis安装

### 1.下载地址：

1.Redis官方下载地址: [http://redis.io/download](http://redis.io/download)


### 2. 安装：

```
$ wget http://download.redis.io/releases/redis-3.0.5.tar.gz
$ tar xzf redis-3.0.5.tar.gz
$ cd redis-3.0.5
$ make
$ cd src && make install
```

### 3. 移动文件便于管理

```
cd /usr/local/
mkdir -p /usr/local/redis/etc
mkdir -p /usr/local/redis/bin

cd ~/redis-3.0.5
mv redis.conf /usr/local/redis/etc
cd ~/redis-3.0.5/src

mv mkreleasehdr.sh redis-benchmark redis-check-aof \
redis-check-dump redis-cli redis-server /usr/local/redis/bin
```

### 4.修改配置文件

vim /usr/local/redis/etc/redis.conf
将daemonize no 中no改为yes[yes指后台运行]

### 5.Redis启动

```
#vi /etc/rc.local #设置随机启动。
---指定配置文件路径
./usr/local/redis/bin/redis-server /usr/local/redis/etc/redis.conf

#查看是否启动成功
ps -ef | grep redis
netstat -tunpl | grep 6379 #查看端口是否占用。
```
### 6. Redis客户端连接

```
./usr/local/redis/bin/redis-cli

#指定端口连接
./usr/local/redis/bin/redis-cli -p 6380 save
```

### 7.关闭服务

```
./usr/local/redis/bin/redis-server shutdown #关闭
pkill redis-server #关闭
```

### 8.查看Redis进程

```
netstat -ntlp | grep 6379
```

### 9. 其他

```
若需要直接执行可以这样：
cp redis.conf /etc/ 这个文件时redis启动的配置文件
cp redis-benchmark redis-cli redis-server /usr/bin/
#这个倒是很有用，这样就不用再执行时加上./了，而且可以在任何地方执行
```