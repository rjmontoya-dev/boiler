<?php

namespace Boiler\Traits\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use ReflectionClass;

trait HandleRedirect
{

     /**
     * Redirect to the given route with a success message.
     *
     * @param string $route
     * @param string $message
     * @param array $params
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleRedirect(string $route, string $message = 'Success', array $params = []): RedirectResponse
    {
        return redirect()->route($route, $params)->with('success', $message);
    }

    /**
     * Build success message.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param string $action
     */
    protected function buildMessage(Model $item, string $action): string
    {
        $model = $this->extractModelName($item);
        $name = $this->extractName($item);

        return "{$model} - {$name} has been {$action}.";
    }

    /**
     * Extract the model class name.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     *
     * @return string
     */
    protected function extractModelName(Model $item): string
    {
        return match (true) {
            $this->hasMethod($item, 'renderModelName') => $item->renderModelName(),
            default => class_basename($item)
        };
    }

    /**
     * Extract the model resource name.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     *
     * @return string
     */
    protected function extractName(Model $item): string
    {
        return match (true) {
            $this->hasMethod($item, 'representation') => $item->representation(),
            $item->name != null => $item->name,
            $item->title != null => $item->title,
            default => 'Resource #' . $item->id
        };
    }

    /**
     * Check if the model has the given method.
     *
     * @param \Illuminate\Database\Eloquent\Model $item
     * @param string $method
     *
     * @return boolean
     */
    protected function hasMethod(Model $item, string $method): bool
    {
        return (new ReflectionClass($item))->hasMethod($method);
    }
}
