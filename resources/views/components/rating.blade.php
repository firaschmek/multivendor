{{--
RATING DISPLAY COMPONENT
Usage: @include('components.rating', ['rating' => $product->average_rating, 'count' => $product->reviews_count])

Affiche les étoiles et le nombre de reviews
À utiliser dans les listes de produits, cartes produits, etc.
--}}

<div class="flex items-center gap-2">
    <div class="flex gap-1">
        @for($i = 1; $i <= 5; $i++)
        @if($i <= floor($rating))
        <i class="fas fa-star text-yellow-400 text-sm"></i>
        @elseif($i - 0.5 <= $rating)
        <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
        @else
        <i class="far fa-star text-gray-300 text-sm"></i>
        @endif
        @endfor
    </div>

    @if(isset($count) && $count > 0)
    <span class="text-sm text-gray-600">({{ $count }})</span>
    @endif

    @if(isset($showNumber) && $showNumber && $rating > 0)
    <span class="text-sm font-medium text-gray-700">{{ number_format($rating, 1) }}</span>
    @endif
</div>
