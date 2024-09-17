<?php

namespace Boiler\Objects;

class CrudResources extends BaseObject
{

    /**
     * @see \Illuminate\Http\Resources\Json\JsonResource
     *
     * @var string|null
     */
    public ?string $index;

    /**
     * @see \Illuminate\Http\Resources\Json\JsonResource
     *
     * @var string|null
     */
    public ?string $show;
}
