<?php

use Kedeka\Support\Tests\Fixtures\SluggableModel;
use Kedeka\Support\Tests\Fixtures\SluggableWithCustomColumnModel;

describe('HasSlug', function () {
    it('generates slug from title on saving', function () {
        $model = SluggableModel::create(['title' => 'Hello World']);

        expect($model->slug)->toBe('hello-world');
    });

    it('generates slug from name when title is null', function () {
        $model = SluggableModel::create(['name' => 'My Name']);

        expect($model->slug)->toBe('my-name');
    });

    it('does not overwrite existing slug', function () {
        $model = SluggableModel::create([
            'title' => 'Hello World',
            'slug' => 'custom-slug',
        ]);

        expect($model->slug)->toBe('custom-slug');
    });

    it('uses custom sluggable column when defined', function () {
        $model = SluggableWithCustomColumnModel::create(['name' => 'Custom Column']);

        expect($model->slug)->toBe('custom-column');
    });

    it('handles special characters in slug', function () {
        $model = SluggableModel::create(['title' => 'Hello & World! @#$']);

        expect($model->slug)->toBe('hello-world-at');
    });
});
