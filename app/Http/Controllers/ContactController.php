<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submitForm(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $emailBody = "You have received a new message from the contact form:\n\n"
            . "Name: " . $data['name'] . "\n"
            . "Email: " . $data['email'] . "\n"
            . "Message:\n" . $data['message'];

        Mail::raw($emailBody, function ($message) use ($data) {
            $message->to('support@example.com') 
                    ->subject('Customer Support Message') 
                    ->from($data['email'], $data['name']); 
        });

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
