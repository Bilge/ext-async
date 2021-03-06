--TEST--
Process provides STDERR as readable pipe.
--SKIPIF--
<?php
if (!extension_loaded('task')) echo 'Test requires the task extension to be loaded';
?>
--FILE--
<?php

namespace Concurrent\Process;

$builder = new ProcessBuilder(PHP_BINARY);
$builder->configureStderr(ProcessBuilder::STDIO_PIPE);

$process = $builder->start(__DIR__ . '/502.inc');

var_dump('START');

$stderr = $process->getStderr();

var_dump($stderr instanceof \Concurrent\Stream\ReadableStream);

try {
    while (null !== ($chunk = $stderr->read())) {
        var_dump($chunk);
    }
} finally {
    $stderr->close();
}

var_dump($process->awaitExit());
var_dump('FINISHED');

--EXPECT--
string(5) "START"
bool(true)
string(5) "Hello"
string(8) "World :)"
int(1)
string(8) "FINISHED"
