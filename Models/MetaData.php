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

    public function setMetaDataValue($key, $value): self
    {

        $metaData = $this->getMetaData();
        $metaData[$key] = $value;
        $this->setAttribute('meta_data', $metaData);

        return $this;
    }
}