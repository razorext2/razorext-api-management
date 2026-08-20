<?php

/** Goal: Feature tests for AI Chatbot, Caller: PHPUnit, Deps: ChatConversation, ChatMessage, User */

use App\Livewire\Chatbot\Chatbot;
use App\Models\Chatbot\ChatConversation;
use App\Models\Chatbot\ChatMessage;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create(['is_active' => true]);
});

it('can access chatbot page', function () {
    $this->actingAs($this->user)
        ->get(route('chatbot.index'))
        ->assertSuccessful()
        ->assertSee('Razor AI');
});

it('redirects guests to login', function () {
    $this->get(route('chatbot.index'))
        ->assertRedirect(route('login'));
});

it('can create a new conversation', function () {
    Livewire::actingAs($this->user)
        ->test(Chatbot::class)
        ->call('createConversation')
        ->assertHasNoErrors();

    expect(ChatConversation::where('user_id', $this->user->id)->count())->toBe(1);
});

it('can select a conversation', function () {
    $conversation = ChatConversation::create([
        'user_id' => $this->user->id,
        'title' => 'Test Conversation',
    ]);

    Livewire::actingAs($this->user)
        ->test(Chatbot::class)
        ->call('selectConversation', $conversation->id)
        ->assertSet('activeConversationId', $conversation->id);
});

it('can delete a conversation', function () {
    $conversation = ChatConversation::create([
        'user_id' => $this->user->id,
        'title' => 'To Delete',
    ]);

    Livewire::actingAs($this->user)
        ->test(Chatbot::class)
        ->call('deleteConversation', $conversation->id);

    expect(ChatConversation::withTrashed()->find($conversation->id)->deleted_at)->not->toBeNull();
});

it('cannot access another user conversation', function () {
    $other = User::factory()->create();
    $conversation = ChatConversation::create([
        'user_id' => $other->id,
        'title' => 'Other User Chat',
    ]);

    Livewire::actingAs($this->user)
        ->test(Chatbot::class)
        ->call('selectConversation', $conversation->id)
        ->assertStatus(404);
});

it('cannot delete another user conversation', function () {
    $other = User::factory()->create();
    $conversation = ChatConversation::create([
        'user_id' => $other->id,
        'title' => 'Other User Chat',
    ]);

    Livewire::actingAs($this->user)
        ->test(Chatbot::class)
        ->call('deleteConversation', $conversation->id)
        ->assertStatus(404);
});

it('loads messages for active conversation', function () {
    $conversation = ChatConversation::create([
        'user_id' => $this->user->id,
        'title' => 'Test',
    ]);

    ChatMessage::create([
        'conversation_id' => $conversation->id,
        'role' => 'user',
        'content' => 'Hello!',
    ]);

    ChatMessage::create([
        'conversation_id' => $conversation->id,
        'role' => 'model',
        'content' => 'Hi there!',
    ]);

    $component = Livewire::actingAs($this->user)
        ->test(Chatbot::class)
        ->call('selectConversation', $conversation->id);

    expect($component->get('activeConversationId'))->toBe($conversation->id);
});

it('does not send empty messages', function () {
    $conversation = ChatConversation::create([
        'user_id' => $this->user->id,
        'title' => 'Test',
    ]);

    Livewire::actingAs($this->user)
        ->test(Chatbot::class)
        ->set('activeConversationId', $conversation->id)
        ->set('newMessage', '')
        ->call('sendMessage');

    expect(ChatMessage::where('conversation_id', $conversation->id)->count())->toBe(0);
});
