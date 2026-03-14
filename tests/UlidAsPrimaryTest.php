<?php

use Kedeka\Support\Tests\Fixtures\UlidPrimaryModel;

describe('UlidAsPrimary', function () {
    it('generates ulid as primary key on creating', function () {
        $model = UlidPrimaryModel::create(['name' => 'Test']);

        expect($model->id)
            ->toBeString()
            ->toHaveLength(26);
    });

    it('does not overwrite existing id', function () {
        $model = UlidPrimaryModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'name' => 'Test',
        ]);

        expect($model->id)->toBe('01ARZ3NDEKTSV4RRFFQ69G5FAV');
    });

    it('uses string as key type', function () {
        $model = new UlidPrimaryModel;

        expect($model->getKeyType())->toBe('string');
    });

    it('disables auto incrementing', function () {
        $model = new UlidPrimaryModel;

        expect($model->getIncrementing())->toBeFalse();
    });

    it('uses id as key name', function () {
        $model = new UlidPrimaryModel;

        expect($model->getKeyName())->toBe('id');
    });

    it('can find model by ulid primary key', function () {
        $model = UlidPrimaryModel::create(['name' => 'Findable']);

        $found = UlidPrimaryModel::find($model->id);

        expect($found)->not->toBeNull()
            ->and($found->name)->toBe('Findable');
    });

    it('generates unique ids for different models', function () {
        $model1 = UlidPrimaryModel::create(['name' => 'Test 1']);
        $model2 = UlidPrimaryModel::create(['name' => 'Test 2']);

        expect($model1->id)->not->toBe($model2->id);
    });
});
