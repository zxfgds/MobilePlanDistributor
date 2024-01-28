#### 1.安装支持库 创建配置文件

```
composer install 
composer dump-autoload
cp .env.example .env
```

#### 2.创建数据库表 ,首先编辑根目录 phinx.php 中数据库配置文件

```apacheconf
php vendor/bin/phinx migrate
php vendor/bin/phinx seed:run
php webman db:tool import
```

#### 3.启动
```apacheconf
php webman start 
```

```apacheconf

APP_NAME=HAOKA
APP_URL=http://localhost
APP_SECRET=GOOGLEBAIDUYANDEXDUCKDUCKGO

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database
DB_USERNAME=database
DB_PASSWORD=password
DB_PREFIX=
DB_CHARSET=utf8mb4
DB_STRICK=true

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=
REDIS_DATABASE=0
REDIS_CACHE_DATABASE=1
REDIS_PREFIX=HAOKA_SYSTEM_

ADMIN_PREFIX=admin
```

### 4. ElasticSearch

```
wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo gpg --dearmor -o /usr/share/keyrings/elasticsearch-keyring.gpg
sudo apt-get install apt-transport-https
echo "deb [signed-by=/usr/share/keyrings/elasticsearch-keyring.gpg] https://artifacts.elastic.co/packages/7.x/apt stable main" | sudo tee /etc/apt/sources.list.d/elastic-7.x.list
sudo apt-get update && sudo apt-get install elasticsearch

systemctl daemon-reload
systemctl enable elasticsearch.service
systemctl start elasticsearch.service
```

### 5.队列配置
```apacheconf
config/plugin/webman/redis-queue/redis.php
```