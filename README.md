## Advanced Logging Tool (php)

#### Возможности
* Несколько видов уровня лога
* Вывод в лог название файла, номер процесса, дату и время
* Относительное и абсолютное время
* Остановка и возобновление добавления логов в файл
* Печать лога
* Кастомный файл для логов или использование системного лога
* Логирование группой (скрывает дату и время, название файла и процесса)

#### Установка
* Скачать последний релиз [от сюда](https://github.com/daler445/advanced-logging-php/releases/latest "Последний релиз")
* Разместить в доступную папку
* Включить файл .phar `require_once("PHPDebug.phar");`

#### Как использовать:
```php
<?php
require_once('PHPDebug.phar');

$debug = new Debug();

// путь к файлу лога
$debug->setFilePath('from_build_static.log');

// пишем в лог
$debug->log('Initialize');

// вывод в файл
$debug->outputLog();
```

#### TODO
* Поддержка несколько стандартов логирования
* Несколько способов установки

#### Релизы
- Версия 1.0 [2020-02-20] 

#### Changelog
* Версия 1.0
    * Запуск