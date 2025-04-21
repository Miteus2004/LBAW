@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="text-center my-4">
            <h1 class="text-primary">About Us</h1>
        </div>

        <div class="my-4">
            <p class="fs-5">
                The main objective of <strong>FEUPshare</strong> is to create a collaborative environment where students 
                and teachers can share and discuss doubts and challenges that arise throughout their academic journey.
            </p>
            <p class="fs-5">
                The <strong>FEUPshare system</strong> is being developed by a group of students as a product that allows users 
                to manage projects.
            </p>
        </div>

        <section>
            <h2 class="text-center"><strong>Our Team:</strong></h2>
            <div class="d-flex flex-wrap justify-content-center mt-4">
                <div class="team-member text-center mx-3" style="flex: 0 1 22%;">
                    <img src="/img/pombo.jpg" alt="image of one of the developpers" class="img-fluid mb-2" width="300" height="250">
                    <p>Rodrigo Pombo</p>
                </div>
                <div class="team-member text-center mx-3" style="flex: 0 1 22%;">
                    <img src="/img/pedro.jpg" alt="image of one of the developpers" class="img-fluid mb-2" width="300" height="250">
                    <p>Pedro Vieira</p>
                </div>
                <div class="team-member text-center mx-3" style="flex: 0 1 22%;">
                    <img src="/img/rodrigo.jpg" alt="image of one of the developpers" class="img-fluid mb-2" width="300" height="250">
                    <p>Rodrigo Martins</p>
                </div>
                <div class="team-member text-center mx-3" style="flex: 0 1 22%;">
                    <img src="/img/miguel.jpg" alt="image of one of the developpers" class="img-fluid mb-2" width="300" height="250">
                    <p>Miguel Mateus</p>
                </div>
            </div>
        </section>
    </div>
@endsection
