<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 1/29/2017
 * Time: 8:10 PM
 */

namespace Modules\Core\Traits\Model;


use Illuminate\Database\Eloquent\Model;

trait AuthorTrait
{
    /**
     * Whether we're currently maintaining author.
     *
     * @param bool
     */
    protected $logAuthor = true;

    public static function bootAuthorTrait()
    {
        static::registerListeners();
    }

    /**
     * Register events we need to listen for.
     *
     * @return void
     */
    public static function registerListeners()
    {
        static::creating(function (Model $model) {
            if (!$model->isLoggingAuthor()) {
                return;
            }

            $model->created_by = auth()->id();
            $model->updated_by = auth()->id();
        });

        static::updating(function (Model $model) {
            if (!$model->isLoggingAuthor()) {
                return;
            }

            $model->updated_by = auth()->id();
        });

        if (static::usingSoftDeletes()) {
            static::deleting(function (Model $model) {
                if (!$model->isLoggingAuthor()) {
                    return;
                }

                $model->deleted_by = auth()->id();
                $model->save();
            });

            static::restoring(function (Model $model) {
                if (!$model->isLoggingAuthor()) {
                    return;
                }

                $model->deleted_by = null;
            });
        }
    }

    /**
     * Has the model loaded the SoftDeletes trait.
     *
     * @return bool
     */
    public static function usingSoftDeletes()
    {
        static $usingSoftDeletes;

        if ($usingSoftDeletes === null) {
            return $usingSoftDeletes = in_array(
                'Illuminate\Database\Eloquent\SoftDeletes', class_uses(get_called_class()), true
            );
        }

        return $usingSoftDeletes;
    }

    /**
     * Get the user that created the model.
     */
    public function creator()
    {
        return $this->belongsTo($this->getUserClass(), 'created_by');
    }

    /**
     * Get the user that edited the model.
     */
    public function editor()
    {
        return $this->belongsTo($this->getUserClass(), 'updated_by');
    }

    /**
     * Get the user that deleted the model.
     */
    public function remover()
    {
        return $this->belongsTo($this->getUserClass(), 'deleted_by');
    }

    /**
     * Check if we're maintaining author on the model.
     *
     * @return bool
     */
    public function isLoggingAuthor(): bool
    {
        return $this->logAuthor;
    }

    /**
     * Stop maintaining author on the model.
     *
     * @return void
     */
    public function disableLoggingAuthor()
    {
        $this->logAuthor = false;
    }

    /**
     * Start maintaining author on the model.
     *
     * @return void
     */
    public function enableLoggingAuthor()
    {
        $this->logAuthor = true;
    }

    /**
     * Get the class being used to provide a User.
     *
     * @return string
     */
    protected function getUserClass(): string
    {
        if (get_class(auth()) === 'Illuminate\Auth\Guard') {
            return auth()->getProvider()->getModel();
        }

        return auth()->guard()->getProvider()->getModel();
    }
}
