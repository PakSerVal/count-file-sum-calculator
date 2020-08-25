<?php

$paths = $argv;
array_shift($paths);

if (count($paths) === 0) { $paths[] = '.'; }
$paths = array_filter($paths, 'is_dir');

if (count($paths) === 0)
{
    line('Указаны неправильные пути для директорий');
    die();
}

function calculateForDir($path)
{
    $directory = new RecursiveDirectoryIterator($path);
    $filter = new RecursiveCallbackFilterIterator($directory, function ($current, $key, $iterator)
    {
        $fileName = $current->getFilename();
        if ($fileName === '.' || $fileName === '..')
        {
            return false;
        }

        if ($current->isFile())
        {
            return $fileName === 'count';
        }

        return true;
    });

    $iterator = new RecursiveIteratorIterator($filter);
    $result = 0;
    $filesCount = 0;
    foreach ($iterator as $item)
    {
        $result += intval(file_get_contents($item->getPathName()));
        $filesCount++;
    }

    line("Найдено файлов 'count': $filesCount");

    return $result;
}

function line($text) { echo $text . PHP_EOL; }

$totalCount = 0;
foreach ($paths as $path)
{
    line("Директория: $path");
    $dirCount = calculateForDir($path);
    line("Сумма чисел для директории: $dirCount");

    $totalCount += $dirCount;
}
line("Общая сумма чисел для всех директорий: $totalCount");