<?php

/*
 * This file is part of Laravel Eloquent Flag.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Tests\Flag\Unit\Scopes\Inverse;

use Cog\Tests\Flag\Stubs\Models\Inverse\EntityWithEndedFlag;
use Cog\Tests\Flag\Stubs\Models\Inverse\EntityWithEndedFlagUnapplied;
use Cog\Tests\Flag\TestCase;

/**
 * Class EndedFlagScopeTest.
 *
 * @package Cog\Tests\Flag\Unit\Scopes\Inverse
 */
class EndedFlagScopeTest extends TestCase
{
    /** @test */
    public function it_can_get_only_not_ended()
    {
        factory(EntityWithEndedFlag::class, 2)->create([
            'is_ended' => true,
        ]);
        factory(EntityWithEndedFlag::class, 3)->create([
            'is_ended' => false,
        ]);

        $entities = EntityWithEndedFlag::all();

        $this->assertCount(3, $entities);
    }

    /** @test */
    public function it_can_get_without_ended()
    {
        factory(EntityWithEndedFlag::class, 2)->create([
            'is_ended' => true,
        ]);
        factory(EntityWithEndedFlag::class, 3)->create([
            'is_ended' => false,
        ]);

        $entities = EntityWithEndedFlag::withoutEnded()->get();

        $this->assertCount(3, $entities);
    }

    /** @test */
    public function it_can_get_with_ended()
    {
        factory(EntityWithEndedFlag::class, 2)->create([
            'is_ended' => true,
        ]);
        factory(EntityWithEndedFlag::class, 3)->create([
            'is_ended' => false,
        ]);

        $entities = EntityWithEndedFlag::withEnded()->get();

        $this->assertCount(5, $entities);
    }

    /** @test */
    public function it_can_get_only_ended()
    {
        factory(EntityWithEndedFlag::class, 2)->create([
            'is_ended' => true,
        ]);
        factory(EntityWithEndedFlag::class, 3)->create([
            'is_ended' => false,
        ]);

        $entities = EntityWithEndedFlag::onlyEnded()->get();

        $this->assertCount(2, $entities);
    }

    /** @test */
    public function it_can_unend_model()
    {
        $model = factory(EntityWithEndedFlag::class)->create([
            'is_ended' => true,
        ]);

        EntityWithEndedFlag::where('id', $model->id)->unend();

        $model = EntityWithEndedFlag::where('id', $model->id)->first();

        $this->assertFalse($model->is_ended);
    }

    /** @test */
    public function it_can_end_model()
    {
        $model = factory(EntityWithEndedFlag::class)->create([
            'is_ended' => false,
        ]);

        EntityWithEndedFlag::where('id', $model->id)->end();

        $model = EntityWithEndedFlag::withEnded()->where('id', $model->id)->first();

        $this->assertTrue($model->is_ended);
    }

    /** @test */
    public function it_can_skip_apply()
    {
        factory(EntityWithEndedFlag::class, 3)->create([
            'is_ended' => true,
        ]);
        factory(EntityWithEndedFlag::class, 2)->create([
            'is_ended' => false,
        ]);

        $entities = EntityWithEndedFlagUnapplied::all();

        $this->assertCount(5, $entities);
    }
}
