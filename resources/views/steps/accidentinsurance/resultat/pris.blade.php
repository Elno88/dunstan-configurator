<div id="resultat-widget-data" data-price="{{ $price ?? '' }}">
    <div class="resultat-widget-contents">
        <div class="inner">
            <div class="resultat-widget-list">
                <div class="body-price body-price-liv selected">
                    <p class="resultat-widget-list-label">Försäkring:</p>
                    <h4 class="resultat-widget-list-value first">{{ $insurance }}</h4>
                    <p class="resultat-widget-list-label">Gäller för:</p>
                    <h4 class="resultat-widget-list-value">{{ $ssn }}</h4>
                </div>
            </div>
            <header class="resultat-widget-header">
                <div class="inner">
                    <p style="margin-bottom:6px;">Ditt pris<p>
                    <p class="resultat-price">{{ number_format($price, 0, '.', ' ') }} {{ $suffix }}</p>
                </div>
            </header>
        </div>
    </div>
</div>

