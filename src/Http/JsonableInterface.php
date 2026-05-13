<?php

namespace Velt\Http;

interface JsonableInterface
{
    public function toJson(): mixed;
}
