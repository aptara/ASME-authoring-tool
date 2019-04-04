<?php
namespace App\Providers;

use App\Modules\Chapter\Repositories\ChapterRepository;
use App\Modules\Chapter\Repositories\EloquentChapter;
use App\Modules\Lock\Repositories\EloquentLock;
use App\Modules\Lock\Repositories\LockRepository;
use App\Modules\Revision\Repositories\EloquentRevision;
use App\Modules\Revision\Repositories\RevisionRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ChapterRepository::class, EloquentChapter::class);
        $this->app->singleton(RevisionRepository::class, EloquentRevision::class);
        $this->app->singleton(LockRepository::class, EloquentLock::class);
    }
}
