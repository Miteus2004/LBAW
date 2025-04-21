@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Frequently Asked Questions</h4>
                </div>
                <div class="card-body">
                    @foreach ($faqs as $faq)
                        <div class="faq-item mb-4">
                            <h5 class="faq-question">{{ $faq->question }}</h5>
                            <p class="faq-answer">{{ $faq->answer }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection