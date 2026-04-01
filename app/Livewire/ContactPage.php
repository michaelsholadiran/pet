<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ContactPage extends Component
{
    public string $name = '';

    public string $email = '';

    public string $message = '';

    #[Layout('layouts.app')]
    public function submit(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $to = config('puppiary.contact_email', 'hello@puppiary.com');
        $body = "From: {$validated['name']} <{$validated['email']}>\n\n".$validated['message'];

        try {
            Mail::raw($body, function ($mail) use ($validated, $to): void {
                $mail->to($to)
                    ->replyTo($validated['email'], $validated['name'])
                    ->subject('Puppiary contact: '.$validated['name']);
            });
        } catch (\Throwable $e) {
            Log::warning('Contact form mail failed', [
                'error' => $e->getMessage(),
                'payload' => $validated,
            ]);
        }

        session()->flash('contact_sent', true);
        $this->reset('name', 'email', 'message');
    }

    public function render()
    {
        return view('livewire.contact-page');
    }
}
