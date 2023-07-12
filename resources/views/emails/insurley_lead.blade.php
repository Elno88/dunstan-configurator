@component('mail::message')
# Leads via Insurley

Bifogat med detta mail finns en Excel-fil med nya leads från
<a href="https://forsakra.dunstan.se">forsakra.dunstan.se</a>.

@if ($custom)
Den bifogade filen innehåller alla leads från ***{{ $date }}***.
@else
Den bifogade filen innehåller alla leads från det senaste dygnet (eller senaste utskicket).
@endif
@endcomponent
