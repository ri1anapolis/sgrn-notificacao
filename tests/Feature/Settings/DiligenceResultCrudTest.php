<?php

use App\Enums\UserRole;
use App\Models\DiligenceResult;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;
use function Pest\Laravel\post;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => UserRole::Admin]);
    $this->employee = User::factory()->create(['role' => UserRole::Employee]);
});

describe('index', function () {
    it('allows admin to view diligence results', function () {
        DiligenceResult::factory()->count(3)->create();

        actingAs($this->admin);
        $response = get(route('diligence-results.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('settings/DiligenceResults'));
    });

    it('forbids employee from viewing diligence results', function () {
        actingAs($this->employee);
        $response = get(route('diligence-results.index'));

        $response->assertForbidden();
    });
});

describe('store', function () {
    it('allows admin to create custom diligence result', function () {
        actingAs($this->admin);

        $response = post(route('diligence-results.store'), [
            'group' => 'Custom Group',
            'code' => 'custom_option',
            'description' => 'A custom diligence option.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('diligence_results', [
            'code' => 'custom_option',
            'group' => 'Custom Group',
            'is_custom' => true,
            'active' => true,
        ]);
    });

    it('validates required fields', function () {
        actingAs($this->admin);

        $response = post(route('diligence-results.store'), []);

        $response->assertSessionHasErrors(['group', 'code', 'description']);
    });

    it('validates unique code', function () {
        $uniqueCode = 'test_unique_code_'.time();
        DiligenceResult::create([
            'group' => 'Test Group',
            'code' => $uniqueCode,
            'description' => 'Test description',
            'order' => 0,
            'active' => true,
            'is_custom' => false,
        ]);

        actingAs($this->admin);

        $response = post(route('diligence-results.store'), [
            'group' => 'Test Group',
            'code' => $uniqueCode,
            'description' => 'Test description.',
        ]);

        $response->assertSessionHasErrors(['code']);
    });
});

describe('update', function () {
    it('allows admin to update description', function () {
        $result = DiligenceResult::factory()->create([
            'description' => 'Old description',
        ]);

        actingAs($this->admin);

        $response = patch(route('diligence-results.update', $result), [
            'description' => 'New description',
        ]);

        $response->assertRedirect();

        $result->refresh();
        expect($result->description)->toBe('New description');
    });
});

describe('toggleActive', function () {
    it('allows admin to toggle active status', function () {
        $result = DiligenceResult::factory()->create(['active' => true]);

        actingAs($this->admin);

        $response = patch(route('diligence-results.toggle', $result));
        $response->assertRedirect();

        $result->refresh();
        expect($result->active)->toBe(false);

        patch(route('diligence-results.toggle', $result));
        $result->refresh();
        expect($result->active)->toBe(true);
    });
});
