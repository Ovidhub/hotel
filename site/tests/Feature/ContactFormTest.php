<?php

use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
    Mail::fake();
});

// ── GET /contact ──────────────────────────────────────────────────────────────

test('contact page returns 200', function () {
    $this->get(route('contact'))
         ->assertOk();
});

// ── POST /contact — valid submission ──────────────────────────────────────────

test('valid contact form submission persists message and redirects with flash', function () {
    $response = $this->post(route('contact.store'), [
        'name'    => 'Ada Obi',
        'email'   => 'ada@example.com',
        'phone'   => '+234 800 000 1111',
        'subject' => 'Room Enquiry',
        'message' => 'Hello, I would like to enquire about your executive suite.',
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('messages', [
        'name'  => 'Ada Obi',
        'email' => 'ada@example.com',
    ]);
});

test('valid submission without optional fields still persists message', function () {
    $response = $this->post(route('contact.store'), [
        'name'    => 'Emeka Nwosu',
        'email'   => 'emeka@example.com',
        'message' => 'Just a quick question about parking.',
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('messages', [
        'name'    => 'Emeka Nwosu',
        'email'   => 'emeka@example.com',
        'phone'   => null,
        'subject' => null,
    ]);
});

// ── POST /contact — validation failures ───────────────────────────────────────

test('missing email causes validation error and no message is saved', function () {
    $response = $this->post(route('contact.store'), [
        'name'    => 'Test User',
        'email'   => '',
        'message' => 'Some message here.',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('messages', 0);
});

test('missing name causes validation error', function () {
    $response = $this->post(route('contact.store'), [
        'name'    => '',
        'email'   => 'someone@example.com',
        'message' => 'Some message.',
    ]);

    $response->assertSessionHasErrors('name');
    $this->assertDatabaseCount('messages', 0);
});

test('missing message body causes validation error', function () {
    $response = $this->post(route('contact.store'), [
        'name'    => 'Test User',
        'email'   => 'test@example.com',
        'message' => '',
    ]);

    $response->assertSessionHasErrors('message');
    $this->assertDatabaseCount('messages', 0);
});

test('invalid email format causes validation error', function () {
    $response = $this->post(route('contact.store'), [
        'name'    => 'Test User',
        'email'   => 'not-an-email',
        'message' => 'Some message.',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('messages', 0);
});

// ── read_at must not be mass-assigned ─────────────────────────────────────────

test('read_at cannot be set via form submission', function () {
    $this->post(route('contact.store'), [
        'name'    => 'Hacker',
        'email'   => 'hacker@example.com',
        'message' => 'Try to set read_at.',
        'read_at' => now()->toDateTimeString(),
    ]);

    $this->assertDatabaseHas('messages', [
        'email'   => 'hacker@example.com',
        'read_at' => null,
    ]);
});
