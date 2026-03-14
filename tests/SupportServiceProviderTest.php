<?php

use Illuminate\Database\Schema\Blueprint;

describe('SupportServiceProvider', function () {
    it('registers ulidAlias blueprint macro', function () {
        expect(Blueprint::hasMacro('ulidAlias'))->toBeTrue();
    });

    it('registers ulidPrimary blueprint macro', function () {
        expect(Blueprint::hasMacro('ulidPrimary'))->toBeTrue();
    });

    it('registers viteCssOnly blade directive', function () {
        $directives = app('blade.compiler')->getCustomDirectives();

        expect($directives)->toHaveKey('viteCssOnly');
    });
});
