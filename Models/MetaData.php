<?php

namespace Modules\Laralite\Models;

trait MetaData
{
    public function getMetaData(): array
    {
        $metaData = $this->getAttribute('meta_data') ?: [];
        if ($metaData) {
            $metaData = is_string($metaData) ? json_decode($metaData) : $metaData;
        }

        return $metaData;
    }
}