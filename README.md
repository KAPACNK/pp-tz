# Установка приложения и инструкции к API

### 1. Разворачиваем докер :
```sh
docker-compose up -d
```
### 2. Переходим в директорию src
```sh
cd src
```

### 3. Устанавливаем laravel
```sh
composer update --ignore-platform-reqs
```

### 4. Копируем содержимое .env.example в .env

### 5. Даем разрешение на запись/чтение логов 
```sh
sudo chmod -R 777 storage
```

### 6. Генерируем ключ для приложения 
```sh
php artisan key:generate
```

### 7. Делаем миграцию 
```sh
docker exec -it php_ php artisan migrate --force
```



___
# Примеры запросов
### Создать пользователя
Запрос:
```
curl -d "nickname=api_nickname__pppptest&currency=rub" -X POST http://localhost:8080/api/users
```

### Создать транзакцию
Запрос:
```
curl -d "user_id=1&type=income&date=2020-07-01 01:01:20&&amount=5.6" -X POST http://localhost:8080/api/transactions
```

### Получить список транзакций для 1 пользователя(обратный порядок сортировки)
Запрос:
```
curl -XGET http://localhost:8080/api/transactions/1/1/desc
```


### Получить сгрупированный список транзакций
Запрос:
```
curl -XGET http://localhost:8080/api/transactions-group/1
```
