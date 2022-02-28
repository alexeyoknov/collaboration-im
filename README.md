# Проект тестового интернет магазина
Проект интернет магазина, реализованного на фреймворке Symfony

## Настройка и перый запуск
- Забрать проект
- Поднять на сервере виртуальный хост
- В каталоге нового виртуального хоста разместить содержимое данного репозитория
- Зайти через консоль в каталог виртуального хоста и выполнить следующие команды:
  - `composer install`
  - `bin/console doctrine:schema:update --force`
  - `bin/console doctrine:migrations:migrate`
  - `npm install`
  - `npm run build`
  либо запустить скрипт [postinstall.sh](https://github.com/alexeyoknov/collaboration-im/blob/main/config/postinstall/postinstall.sh)
- Создать у себя файл `.env.local`, в котором нужно указать настройки подключения к БД.
  Образец можно взять файле **.env** (находится в корне этого каталога)

## Настройка виртуального хоста в nginx
### Настройка конфигурации хоста
Примерный файл конфигурации можно найти в [/config/nginx/](https://github.com/alexeyoknov/collaboration-im/blob/main/config/nginx/)
В нем надо проверить и поменять под себя следующие строки:
`
    server_name c-im.my;
    root /www/collaboration-im/public;
`
где для **server_name** указать своё имя, а для **root** - правильный путь к `collaboration-im`
Также может понадобиться изменить `fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;`, где необходимо указать правильный путь к файлу сокета

### Подключение конфига и запуск

Для подключения виртуального хоста достаточно сделать символическую ссылку на файл с конфигурацией в **/etc/nginx/sites_enabled**
Также nginx может быть настроен на чтение файлов *.conf из /etc/nginx/conf.d/
Сама ссылка делается при помощи такой команды:
`ln -s /path/to/conf/file /etc/nginx/sites_enabled/`
либо `ln -s /path/to/conf/file /etc/nginx/sites_enabled/newfilename`, чтобы ссылка имела другое имя

### Настройка файла hosts
Чтобы получить доступ к сайту в браузере, надо добавить название сервера, указанное в **server_name**, добавить в `hosts`. Для Linux - `/etc/hosts`, Windows - `c:\windows\system32\drivers\etc\hosts` 