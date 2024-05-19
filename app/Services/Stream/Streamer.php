<?php

namespace App\Services\Stream;

use App\Services\Stream\Domain\Token;
use Generator;
use JsonSerializable;
use Throwable;

class Streamer
{
    private bool $isOpened = false;

    /**
     * @return void
     */
    public function open(): void
    {
        if (connection_aborted()) {
            exit;
        }
        if ($this->isOpened) {
            return;
        }

        $this->isOpened = true;
        ob_end_flush();
    }

    /**
     * @param string $event 
     * @param null|string|array|JsonSerializable $data 
     * @param null|string $id 
     * @return void 
     */
    public function sendEvent(
        string $event,
        null|string|array|JsonSerializable $data = null,
        ?string $id = null,
    ): void {
        echo "event: " . $event . PHP_EOL;

        if (!is_null($data)) {
            echo "data: " . (is_string($data) ? $data : json_encode($data)) . PHP_EOL;
        }

        echo "id: " . ($id ?: time()) . PHP_EOL . PHP_EOL;
        flush();
    }

    public function close(): void
    {
        if (!$this->isOpened) {
            return;
        }
        $this->isOpened = false;
    }

    /**
     * Stream the generator
     *
     * @param Generator $generator 
     * @return void 
     */
    public function stream(Generator $generator): void
    {
        $this->open();

        try {
            foreach ($generator as $item) {
                if ($item instanceof Token) {
                    $tokens[] = $item->value;
                    $this->sendEvent('token', $item);
                    continue;
                }
            }
        } catch (Throwable $th) {
            $this->sendEvent('error', $th->getMessage());
        }
        $this->close();
    }
}
