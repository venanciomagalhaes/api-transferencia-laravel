<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use stdClass;

class HateoasBuilderHelper
{
    private null|Model|stdClass $resource;

    private string $versionApi;

    private string $basePath;

    private array $links = [];

    public function __construct(null|Model|stdClass $resource, string $versionApi, string $basePath)
    {
        $this->resource = $resource;
        $this->versionApi = $versionApi;
        $this->basePath = $this->formatBasePath($basePath);
    }

    public static function for(?Model $resource, string $versionApi, string $basePath): self
    {
        return new self($resource, $versionApi, $basePath);
    }

    private function formatBasePath(?string $basePath): ?string
    {
        if ($basePath === null) {
            return null;
        }

        return rtrim('/api/' . trim($this->versionApi, '/') . '/' . trim($basePath, '/'), '/') . '/';
    }

    public function self(?string $customBasePath = null): self
    {
        $uri = $this->resource ? ($customBasePath ?? $this->basePath).$this->resource->uuid : ($customBasePath ?? $this->basePath);

        return $this->addLink('self', $uri);
    }

    public function index(?string $customBasePath = null): self
    {
        return $this->addLink('index', $this->formatBasePath($customBasePath) ?? $this->basePath);
    }

    public function create(?string $customBasePath = null): self
    {
        return $this->addLink('create', $this->formatBasePath($customBasePath) ?? $this->basePath, 'POST');
    }

    public function update(?string $customBasePath = null): self
    {
        $uri = $this->resource ? ($this->formatBasePath($customBasePath) ?? $this->basePath).$this->resource->uuid : ($this->formatBasePath($customBasePath) ?? $this->basePath);

        return $this->addLink('update', $uri, 'PUT');
    }

    public function delete(?string $customBasePath = null): self
    {
        $uri = $this->resource ? ($this->formatBasePath($customBasePath) ?? $this->basePath).$this->resource->uuid : ($this->formatBasePath($customBasePath) ?? $this->basePath);

        return $this->addLink('delete', $uri, 'DELETE');
    }

    public function next(?Model $nextResource, ?string $customBasePath = null): self
    {
        return $nextResource ? $this->addLink('next', ($this->formatBasePath($customBasePath) ?? $this->basePath).$nextResource->uuid) : $this;
    }

    public function previous(?Model $previousResource, ?string $customBasePath = null): self
    {
        return $previousResource ? $this->addLink('previous', ($this->formatBasePath($customBasePath) ?? $this->basePath).$previousResource->uuid) : $this;
    }

    private function addLink(string $name, string $href, string $method = 'GET'): self
    {
        $this->links[$name] = [
            'href' => url($href),
            'method' => $method,
        ];

        return $this;
    }

    public function addGenericLink(string $name, string $basePath, string $method = 'GET'): self
    {
        $href = $this->formatBasePath($basePath);

        return $this->addLink($name, $href, $method);
    }

    public function build(): array
    {
        return $this->links;
    }
}
