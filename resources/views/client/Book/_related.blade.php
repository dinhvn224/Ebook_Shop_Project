@if($relatedBooks->count())
<div class="container mt-5">
    <h3 class="mb-4" style="color: var(--primary-color); font-weight: 700;">Sách liên quan</h3>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
        @foreach($relatedBooks as $related)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    @php
                        $img = $related->images->where('is_main', 1)->first()->url ?? $related->images->first()->url ?? null;
                    @endphp
                    <a href="{{ route('client.books.show', $related->id) }}">
                        @if($img)
                            <img src="{{ asset('storage/' . $img) }}" class="card-img-top" alt="{{ $related->name }}" style="height: 180px; object-fit: cover; border-radius: 8px 8px 0 0;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 180px; border-radius: 8px 8px 0 0; color: #aaa;">
                                <i class="fas fa-book fa-2x"></i>
                            </div>
                        @endif
                    </a>
                    <div class="card-body">
                        <a href="{{ route('client.books.show', $related->id) }}" class="text-decoration-none" style="color: var(--text-primary); font-weight: 600;">
                            {{ $related->name }}
                        </a>
                        <div class="mt-2">
                            @php
                                $detail = $related->details->first();
                            @endphp
                            @if($detail)
                                @if($detail->promotion_price && $detail->promotion_price < $detail->price)
                                    <span style="color: var(--danger-color); font-weight: 700;">{{ number_format($detail->promotion_price) }}₫</span>
                                    <span style="text-decoration: line-through; color: #888; font-size: 0.9em;">{{ number_format($detail->price) }}₫</span>
                                @else
                                    <span style="color: var(--danger-color); font-weight: 700;">{{ number_format($detail->price) }}₫</span>
                                @endif
                            @else
                                <span class="text-muted">Liên hệ</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
