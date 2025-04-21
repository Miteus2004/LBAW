
@extends('layouts.app')
@section('content')
    <div class="container">
    <div class="text-center my-4">
            <h1 class="text-primary">Contact Us</h1>
        </div>
        <section class="faq-helper godown">
            <p class="titleabout godown100"><strong>Address:</strong> R. Dr. Roberto Frias, 4200-465 Porto</p>
            <p class="titleabout"><strong>Email:</strong> geral@feupshare.com </p>
            <p class="titleabout"><strong>Phone:</strong> +351 (225) 081 400</p>
        </section>
        <section>
            <h2 class="contentmiddle">Contact Form</h2>
            <form action="{{ route('contact.form') }}" method="post">
                @csrf
                <label for="name"><strong>Name:</strong></label>
                <input type="text" id="name" name="name" required class="form-control larger-input"><br><br>
            
                <label for="email"><strong>Email:</strong></label>
                <input type="email" id="email" name="email" required class="form-control larger-input"><br><br>
            
                <label for="message"><strong>Message:</strong></label>
                <textarea id="message" name="message" rows="6" required class="form-control larger-input"></textarea><br><br>
            
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane mr-1"></i> Submit
                </button>
            </form>
        </section>
    </div>
@endsection