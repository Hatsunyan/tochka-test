# tochka-test
Для простоты теста, предположим что данные обновляются при смене часа.  
Чтобы сервис работал без перебоев при генерации новых данных я использовал 2 состояния.  
Одно является текущим и отдает данные, второе заполняется данными.  
Смена состояний происходит при смене часа.  
Генерацию данных можно запускать за определенное время которое требуется на генерацию (например за 5 минут до смены часа).  
Хранилище данных можно реализовать любое которое реализует интерфейс IStorage
,для примера я реализовал два варианта на файлах и mysql. Можно добавить memcached или Redis или любую бд.  
Для управления данными есть 3 метода доступные по http 

```js
/cron/update - генерирует новые данные (Запускаем за несколько минут до смены часа)
/cron/clear - очищает старое состояние (Запускаем после смены часа)
/cron/init   - создает хранилища (нужно запустить в самом начале)
```

