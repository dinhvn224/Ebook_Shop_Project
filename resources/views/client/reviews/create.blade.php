@extends('client.layouts.app')
<link rel="stylesheet" href="{{ asset('assets/css/create.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="review-grid-container">
    {{-- Cột bên trái: Đánh giá trước --}}
    <div class="review-left-column">
        <h3 class="page-title">Đánh giá sản phẩm: {{ $bookDetail->book->name }}</h3>

        @forelse($reviews as $review)
            <div class="review-card">
                <div class="review-header">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="review-date">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="review-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $review->rating ? 'rated' : '' }}"></i>
                    @endfor
                </div>
                <div class="review-comment">
                    {{ $review->comment }}
                </div>
            </div>
        @empty
            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
        @endforelse
        <div class="mt-3 d-flex justify-content-center">
            {{ $reviews->withQueryString()->links('pagination::bootstrap-4') }}
        </div>
    </div>

    {{-- Cột bên phải: Form đánh giá --}}
    <div class="review-right-column">
        <h3>Đánh giá của bạn</h3>
        @auth
            @if ($existingReview)
                <p>Bạn đã đánh giá sản phẩm này</p>
                <div class="review-rating mb-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $existingReview->rating ? 'rated' : '' }}"></i>
                    @endfor
                </div>
                <p>Bình luận: "{{ $existingReview->comment }}"</p>
                    <span class="review-date">{{ $review->created_at->format('d/m/Y H:i') }}</span>

            @elseif ($hasPurchased)
                <form action="{{ route('reviews.store') }}" method="POST" class="review-form">
                    @csrf
                    <input type="hidden" name="book_detail_id" value="{{ $bookDetailId }}">
                    <div class="form-group">
                        <label for="rating">Rating (1-5)</label>
                        <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required hidden>
                                <label for="star{{ $i }}" class="star" data-value="{{ $i }}"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="comment">Comment</label>
                        <textarea name="comment" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Submit Review</button>
                </form>
            @else
                <p>Bạn cần mua sản phẩm này trước khi có thể đánh giá.</p>
                <form action="{{ route('reviews.store') }}" method="POST" class="review-form" disabled>
                    @csrf
                    <input type="hidden" name="book_detail_id" value="{{ $bookDetailId }}">
                    <div class="form-group">
                        <label for="rating">Rating (1-5)</label>
                        <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required hidden disabled>
                                <label for="star{{ $i }}" class="star" data-value="{{ $i }}"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="comment">Comment</label>
                        <textarea name="comment" class="form-control" rows="4" required disabled></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" disabled>Submit Review</button>
                </form>
            @endif
        @else
            <p>Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để đánh giá sản phẩm.</p>
        @endauth
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-rating .star');

    stars.forEach(star => {
        star.addEventListener('click', function () {
            const value = parseInt(this.dataset.value);
            stars.forEach((s, i) => s.classList.toggle('selected', i < value));
            const radio = document.getElementById('star' + value);
            if (radio) radio.checked = true;
        });

        star.addEventListener('mouseover', function () {
            const value = parseInt(this.dataset.value);
            stars.forEach((s, i) => s.classList.toggle('hovered', i < value));
        });

        star.addEventListener('mouseout', function () {
            stars.forEach(s => s.classList.remove('hovered'));
        });
    });
});
</script>