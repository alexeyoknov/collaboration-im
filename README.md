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