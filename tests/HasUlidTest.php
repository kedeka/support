<?php

use Kedeka\Support\Tests\Fixtures\UlidModel;

describe('HasUlid', function () {
    it('generates ulid on creating', function () {
        $model = UlidModel::create(['name' => 'Test']);

        expect($model->ulid)
            ->toBeString()
            ->toHaveLength(26);
    });

    it('does not overwrite existing ulid', function () {
        $model = UlidModel::create([
            'name' => 'Test',
            'ulid' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
        ]);

        expect($model->ulid)->toBe('01ARZ3NDEKTSV4RRFFQ69G5FAV');
    });

    it('generates unique ulids for different models', function () {
        $model1 = UlidModel::create(['name' => 'Test 1']);
        $model2 = UlidModel::create(['name' => 'Test 2']);

        expect($model1->ulid)->not->toBe($model2->ulid);
    });

    it('persists ulid to database', function () {
        $model = UlidModel::create(['name' => 'Test']);

        $found = UlidModel::where('ulid', $model->ulid)->first();

        expect($found)->not->toBeNull()
            ->and($found->name)->toBe('Test');
    });
});
