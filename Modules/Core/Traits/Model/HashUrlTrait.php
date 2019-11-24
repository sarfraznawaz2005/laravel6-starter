<?php

namespace Modules\Core\Traits\Model;

use Balping\HashSlug\HasHashSlug;

trait HashUrlTrait
{
    use HasHashSlug;

    public function resolveRouteBinding($slug)
    {
        $id = self::decodeSlug($slug);

        if (request()->expectsJson()) {
            $id = $slug;
        }

        return parent::where($this->getKeyName(), $id)->first();
    }
}
