## Yii geo track

Демо проект на Yii2 реализующий API для разделения массива по определённому правилу.
Реализация задания описанного в документе `Тестовое для PHP.pdf`

### Установка и запуск

Подготовлена кофигурация docker-compose. Для запуска приложения - в корневом каталоге проекта выполните:
```
docker-compose up
```
При удачном запуске web-итерфейс будет доступен локально на порту 80, а API доступно по адресу `localhost/split`.

### Тестирование

Для запуска автоматических тестов запустите контейнер и выполните комманду:
```
docker exec -it array-split_web_1 vendor/bin/codecept run
```
***Примечание:***
Тесты используют "продакшн" БД и ожидают в ней определённые данные, которые заносятся миграциями. Еспи тесты не проходятся попробуйте сбросить БД и накатить миграции заново командой:
```
docker exec -it array-split_web_1 yii migrate/fresh --interactive 0
```

### API

Единственный эндпоинт доступен по адресу `localhost/split`. Ожидаются POST запросы содержащие access-token в url и json-закодированные параметры N и array в теле. Например:
```
curl -i -H "Accept:application/json" -H "Content-Type:application/json" -XPOST "localhost/split?access-token=00000000000000000000000000000000" -d '{"N": 1, "array": [1,1,1,0,0,0]}'
```
### Консольная команда

Консольная команда имеет следующий формат:
```
yii split [(-u|-userId)=<userId>] (-n|-N)=<number_N> <element1>[,<element2>...]
```
Например:
```
docker exec -u$UID:$UID -it array-split_web_1 yii split -u=1 -n=1 1,1,1,0,0,0
```
### Примечания

Использован базовый шаблон [Yii2](https://www.yiiframework.com/) и следующие проекты:
1. https://github.com/vishnubob/wait-for-it
