<?php

namespace Pharaonic\Laravel\Jsonable\Resources\Json;

use Illuminate\Http\Resources\Json\JsonResource as IlluminateJsonResource;

class Resource extends IlluminateJsonResource
{
    /**
     * Response message
     *
     * @var string
     */
    public static $message = null;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, ?string $message = null)
    {
        $this->resource = $resource;
        if ($message) static::$message  = $message;
    }

    /**
     * Create a new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return \App\Jsonable\Resources\ResourceCollection
     */
    public static function collection($resource, ?string $message = null)
    {
        return tap(new ResourceCollection($resource, $message ?? static::$message ?? null), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return json()->success($this->resource->toArray(), static::$message ?? null);
    }
}
