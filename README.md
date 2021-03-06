# Проект тестового интернет магазина
Проект интернет магазина, реализованного на фреймворке Symfony

## Настройка и первый запуск
1. Забрать проект
2. Поднять на сервере виртуальный хост
3. В каталоге нового виртуального хоста разместить содержимое данного репозитория
4. Создать у себя файл `.env.local`, в котором нужно указать настройки подключения к БД: имя БД (**db_name**), имя пользователя (**dbusername**) и пароль (**password**) (доп. информацию см. [здесь](#настройка-бд) ) \
  `DATABASE_URL="mysql://dbusername:password@127.0.0.1:3306/db_name?serverVersion=8.0&charset=utf8mb4"`\
  после этого можно выполнить следующие команды:\
  `./bin/console doctrine:schema:update --force` или `./bin/console doctrine:migrations:migrate`\
также для почтового сервера надо добавить `MAILER_DSN=smtp://username:password@smtp.example.com:465` со своими значениями
5. Зайти через консоль в каталог виртуального хоста и выполнить следующие команды:
  - `composer install`
  - `npm install file-loader@^6.0.0 --save-dev`
  - `npm run build`
  либо запустить скрипт [postinstall.sh](config/postinstall/postinstall.sh)
6. Загрузить тестовые данные, выполнив скрипт [import-data-to-db.sh](config/postinstall/import-data-to-db.sh)  
  Образец можно взять файле **.env** (находится в корне этого каталога)
7. Создать пользователя с правами администратора `bin/console fos:user:create --super-admin
   `
8. Для загрузки изображений проверить наличие и создать при необходимости каталог `public/upload/media`. Также проверить/назначить полные права для всех `chmod 777 -R public/upload/media`
9. Добавить категории товаров и сами товары и привязать [галерею](#загрузка-и-показ-изображений) к товару

## Настройка виртуального хоста в nginx
### Настройка конфигурации хоста
Примерный файл конфигурации можно найти в [config/nginx/local.nginx.example](config/nginx/local.nginx.example)
Его можно переименовать, например, в **local.collaboration-im.conf**, а также проверить и поменять под себя следующие строки:
```
    server_name c-im.my;
    root /www/collaboration-im/public;
```
где для **server_name** указать своё имя, а для **root** - правильный путь к `collaboration-im`\
Также может понадобится изменить `fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;`, где необходимо указать правильный путь к файлу сокета

После понижения версии symfony до 4.4 надо указать следующие параметры
```
    fastcgi_buffers 16 16k;
    fastcgi_buffer_size 32k;
```
иначе, будет выдаваться ошибка при заходе в SonataAdmin

### Подключение конфига и запуск

Для подключения виртуального хоста достаточно сделать символическую ссылку на файл с конфигурацией в **/etc/nginx/sites_enabled** или **/etc/nginx/sites-enabled**\
Также nginx может быть настроен на чтение файлов ***.conf** из **/etc/nginx/conf.d/**\
Более точно можно узнать в **/etc/nginx/nginx.conf**\
Сама ссылка делается при помощи такой команды:\
`ln -s /path/to/conf/file /etc/nginx/sites-enabled/`, либо\
`ln -s /path/to/conf/file /etc/nginx/sites-enabled/newfilename`, чтобы ссылка имела другое имя

### Настройка файла hosts
Чтобы получить доступ к сайту в браузере, надо добавить название сервера, указанное в **server_name**, добавить в `hosts`. Для Linux - `/etc/hosts`, Windows - `c:\windows\system32\drivers\etc\hosts`
```
127.0.0.1   c-im.my
```

## Настройка БД

Настраивать можно, как при помощи [phpmyadmin](https://www.phpmyadmin.net/), так и непосредственно через mysql. Последовательность действий следующая:

### 1. Создать БД
```
CREATE DATABASE db_name;
```
### 2. Добавить пользователя
```
CREATE USER 'dbusername'@'127.0.0.1' IDENTIFIED BY 'password';
```
### 3. Дать этому пользователю административные права для БД
```
GRANT ALL PRIVILEGES ON db_name.* TO 'dbusername'@'127.0.0.1';
```
### 4. В **.env.local** указать подключение к БД
  ```
  DATABASE_URL="mysql://dbusername:@127.0.0.1:3306/db_name?serverVersion=8.0&charset=utf8mb4"
  ```
### 5. Создать таблицы
   Если используются настройки из [миграций](migrations)
  ```
  ./bin/console make:migration
  ./bin/console doctrine:migrations:migrate
  ```
  Если берутся настойки из [аннотаций в Entity](src/Entity)
  ```
  ./bin/console doctrine:schema:update --force
  ```
## Работа с изображениями

### Загрузка и показ изображений
Для подключения изображений к товару:
1. Загрузите изображения через **/admin/app/sonatamediamedia/list**
2. Создайте галерею **/admin/app/sonatamediagallery/list**
3. Для каждого изображения в галерее - активируйте нужные для показа 

### Регенерация размеров изображений
Размеры изображений можно найти в **config/packages/sonata_media.yaml**
```
 bin/console sonata:media:sync-thumbnails sonata.media.provider.image default
```
