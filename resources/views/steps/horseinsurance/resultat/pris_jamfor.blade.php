@if(isset($compare_insurance) && !empty($compare_insurance))
<div class="resultat-widget-list">
    <div class="resultat-widget-body">
        <div class="jamfor-header">
            Ditt pris hos {{ $compare_insurance['insuranceCompany'] ?? 'Försäkringsbolaget' }}:<br/>
            <span class="jamfor-price">
                @if(isset($compare_insurance['premiumAmountYearRounded']))
                    {{ number_format($compare_insurance['premiumAmountYearRounded']/12, 0, ',', ' ') }}
                @else
                    -
                @endif
                kr / mån
            </span>
        </div>
        <p class="jamfor-show-more">Vad innebär detta?</p>
        <div class="jamfor-more-text">
            <em>Att jämföra försäkringar är inte alltid enkelt, innehåll och omfattning kan variera. För att se exakt innehåll, läs villkoren längre ner på sidan.</em>
        </div>
    </div>
</div>
@endif
