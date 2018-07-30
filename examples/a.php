<?php

namespace Concurrent;

var_dump(Fiber::backend());

$scheduler = new TaskScheduler();

$result = $scheduler->run(function () {
    $t = Task::async(function (): int {
        return max(123, Task::await(Deferred::value()));
    });
    
    print_r($t);
    
    try {
        Task::await(Deferred::error(new \Error('Fail!')));
    } catch (\Throwable $e) {
        var_dump($e->getMessage());
    }
    
    var_dump(2 * Task::await($t));
    
    return 777;
});

$timer = new Timer(function (Timer $timer) {
    var_dump('DONE TIMER :)');
    print_r($timer);
    
    $timer->stop();
});
$timer->start(500, true);

var_dump($result);
