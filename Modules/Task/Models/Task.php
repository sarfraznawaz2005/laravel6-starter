<?php

namespace Modules\Task\Models;

use Modules\Core\Models\CoreModel;
use Modules\Core\Traits\Model\AuthorTrait;
use Modules\Core\Traits\Model\CleanHTMLTrait;
use Modules\Core\Traits\Model\HashUrlTrait;
use Modules\User\Models\User;
use QCod\ImageUp\HasImageUploads;
use Rinvex\Cacheable\CacheableEloquent;

class Task extends CoreModel
{
    // maintains created_by, updated_by and deleted_by
    use AuthorTrait;

    // automatic fake model id
    use HashUrlTrait;

    // cache queries on the model
    use CacheableEloquent;

    // to upload images
    use HasImageUploads;

    // to strip html tags
    use CleanHTMLTrait;
    protected $clean = ['description'];

    protected $fillable = [
        'user_id',
        'description',
        'file',
        'completed',
    ];

    // Array of uploadable images. These fields need to be existent in your database table
    // https://github.com/qcod/laravel-imageup
    protected static $imageFields = [
        'file' => [
            'path' => 'task',
            'rules' => 'image|max:2000',
            'width' => 250,
            'height' => 150,
        ]
    ];

    ###################################################################
    # RELATIONSHIPS START
    ###################################################################

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    ###################################################################
    # RELATIONSHIPS END
    ###################################################################

    ###################################################################
    # SCOPES START
    ###################################################################

    public function scopeCompleted($query)
    {
        return $query->where('completed', 1);
    }

    public function scopeUncompleted($query)
    {
        return $query->where('completed', 1);
    }

    ###################################################################
    # SCOPES END
    ###################################################################

    ###################################################################
    # ACCESSROS START
    ###################################################################

    //

    ###################################################################
    # ACCESSROS END
    ###################################################################

    ###################################################################
    # MUTATORS START
    ###################################################################

    //

    ###################################################################
    # MUTATORS END
    ###################################################################

    ###################################################################
    # GENERAL FUNCTIONS START
    ###################################################################

    //

    ###################################################################
    # GENERAL FUNCTIONS END
    ###################################################################
}
