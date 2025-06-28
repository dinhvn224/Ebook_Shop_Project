@extends('client.layouts.app')
<link rel="stylesheet" href="{{ asset('assets/css/create.css') }}">

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h4>Submit a Review for {{ $bookDetail->book->name }}</h4>
        </div>
    </div>
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reviews.store') }}" method="POST" class="review-form">
                @csrf
                <input type="hidden" name="book_detail_id" value="{{ $bookDetailId }}">
                <div class="form-group">
                    <label for="rating">Your Rating</label>
                    <div class="star-rating" id="starRating">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                            <label for="star{{ $i }}" class="star"><i class="fas fa-star"></i></label>
                        @endfor
                    </div>
                </div>

                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea name="comment" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.star-rating .star');
        const radios = document.querySelectorAll('.star-rating input[type="radio"]');

        stars.forEach((star, index) => {
            star.addEventListener('click', function () {
                const value = parseInt(this.getAttribute('for').replace('star', ''));

                // Gán checked vào radio tương ứng
                radios.forEach(radio => {
                    radio.checked = radio.value == value;
                });

                // Đổi màu sao
                stars.forEach((s, i) => {
                    if (i < value) {
                        s.classList.add('selected');
                    } else {
                        s.classList.remove('selected');
                    }
                });
            });

            // Hover effect
            star.addEventListener('mouseover', function () {
                const value = parseInt(this.getAttribute('for').replace('star', ''));
                stars.forEach((s, i) => {
                    if (i < value) {
                        s.classList.add('hovered');
                    } else {
                        s.classList.remove('hovered');
                    }
                });
            });

            star.addEventListener('mouseout', function () {
                stars.forEach(s => s.classList.remove('hovered'));
            });
        });
    });
</script>
