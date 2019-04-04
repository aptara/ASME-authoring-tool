<?php

Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('chapter-list'));
});

Breadcrumbs::register('revision-list', function ($breadcrumbs, $chapter) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Revisions', route('revision-list', ['chapterid' => $chapter->id]));
});

Breadcrumbs::register('edit-chapter', function ($breadcrumbs, $chapter) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push($chapter->name, route('edit-chapter', ['chapter' => $chapter->id]));
});

Breadcrumbs::register('chapters-compare', function ($breadcrumbs, $chapter, $revision) {
    $breadcrumbs->parent('revision-list', $chapter);
    $breadcrumbs->push($chapter->name,
        route('review-changes', ['chapterid' => $chapter->id, 'revisionid' => $revision->id]));
});

Breadcrumbs::register('view-revision', function ($breadcrumbs, $chapter, $revision) {
    $breadcrumbs->parent('revision-list', $chapter);
    $breadcrumbs->push($chapter->name,
        route('view-revision', ['revisionid' => $revision->id]));
});
