<div class="frame frame-results">
    <div class="frame-contents">

        <div class="frame-resultat-header">
            <div id="resultat-forsakring-price">Ditt pris <span class="price">{{ $price['utpris_formaterad'] ?? '' }}</span></div>
            <div id="resultat-forsakring-type">{{ $horse_usage_label ?? 'Försäkring' }}</div>
            <div id="resultat-horse-name">{{ $horse_name ?? 'Hästnamn' }}</div>
        </div>

        <div class="frame-resultat" data-horseusage="{{ $horse_usage ?? '0' }}">

            <div class="frame-resultat-inputs">
                <h2>Välj och anpassa dina försäkringar</h2>

                @if((isset($defaults['livforsakring']) && $defaults['livforsakring'] == 38) || (isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 38))
                    <div style="display: none;">
                        @endif
                        <h4>Veterinärvårdsförsäkring <i class="bubble-help btn-sidebar" data-content="veterinarvardsforsakring">?</i></h4>
                        <p>Ersätter kostnader för undersökningar och behandlingar om din häst blir sjuk eller skadad.</p>
                        <ul class="resultat-slide-select options-{{count($available['veterinarvardsforsakring'])}}">
                            @foreach($available['veterinarvardsforsakring'] as $key => $insurance)
                                <li class="@if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == $insurance){{ 'selected' }}@endif"><input id="vvf-{{$insurance}}" type="radio" name="veterinarvardsforsakring" value="{{ $insurance }}" @if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == $insurance)
                                        {{ 'checked' }}
                                        @endif ><label for="vvf-{{ $insurance }}">{{ $insurances[$insurance]['name'] }}</label></li>
                            @endforeach
                            <div class="marker"></div>
                        </ul>

                        <div class="resultat-select-caption @if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 4){{ 'active' }}@endif" data-content="vvf-4" data-type="vet">

                            <div class="bullet-lists">

                                <p class="resultat-select-caption-title">Premium Veterinärvård</p>

                                <ul class="bullet-list active" data-type="grund">
                                    <li>Skydd för dolda fel</li>
                                    <li>Ingen karenstid*</li>
                                    <li>Frakturer</li>
                                    <li>Sårskador</li>
                                    <li>Kolik & bukingrepp</li>
                                </ul>

                                <ul class="bullet-list active" data-type="special">
                                    <li>Läkemedel</li>
                                    <li>Fång</li>
                                    <li>Ersättning CT, MR, Scint</li>
                                    <li>Hjärtsjukdomar</li>
                                    <li>Hovskador</li>
                                    <li>Kvarka</li>
                                </ul>

                                <ul class="bullet-list active" data-type="premium">
                                    <li>Rehab</li>
                                    <li>Tandvård</li>
                                    <li>Hälta</li>
                                    <li>EOTRH</li>
                                    <li>Botulism</li>
                                    <li>Luftrörsjukdomar</li>
                                </ul>

                                <p class="klausul">Observera att detta endast är ett urval av vad våra olika försäkringar täcker. För fullständig information, läs villkoren som du hittar längst ner på sidan.</p>

                            </div>

                            <div class="compare-table-wrapper">

                                <table class="compare-table compare-table-premium">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Grund</th>
                                        <th>Special</th>
                                        <th>Premium</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>Skydd för dolda fel</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Ingen karenstid</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Frakturer</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Sårskador</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Kolik & bukingrepp</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Läkemedel</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Fång</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Ersättning CT, MR, Scint</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Hjärtsjukdomar</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Hovskador</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Kvarka</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Rehab</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Tandvård</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Hälta</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>EOTRH</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Botulism</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Luftrörsjukdomar</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="compare-table-more">
                                    <span>Visa mer</span>
                                </div>

                                <p class="klausul">Observera att detta endast är ett urval av vad våra olika försäkringar täcker. För fullständig information, läs villkoren som du hittar längst ner på sidan.</p>

                            </div>

                        </div>

                        <div class="resultat-select-caption @if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 6){{ 'active' }}@endif" data-content="vvf-6" data-type="vet">

                            <div class="bullet-lists">

                                <p class="resultat-select-caption-title">Special Veterinärvård</p>

                                <ul class="bullet-list active" data-type="grund">
                                    <li>Skydd för dolda fel</li>
                                    <li>Ingen karenstid*</li>
                                    <li>Frakturer</li>
                                    <li>Sårskador</li>
                                    <li>Kolik & bukingrepp</li>
                                </ul>

                                <ul class="bullet-list active" data-type="special">
                                    <li>Läkemedel</li>
                                    <li>Fång</li>
                                    <li>Ersättning CT, MR, Scint</li>
                                    <li>Hjärtsjukdomar</li>
                                    <li>Hovskador</li>
                                    <li>Kvarka</li>
                                </ul>

                                <ul class="bullet-list" data-type="premium">
                                    <li>Rehab</li>
                                    <li>Tandvård</li>
                                    <li>Hälta</li>
                                    <li>EOTRH</li>
                                    <li>Botulism</li>
                                    <li>Luftrörsjukdomar</li>
                                </ul>

                                <p class="klausul">Observera att detta endast är ett urval av vad våra olika försäkringar täcker. För fullständig information, läs villkoren som du hittar längst ner på sidan.</p>

                            </div>

                            <div class="compare-table-wrapper">

                                <table class="compare-table compare-table-special">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Grund</th>
                                        <th>Special</th>
                                        <th>Premium</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>Skydd för dolda fel</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Ingen karenstid</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Frakturer</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Sårskador</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Kolik & bukingrepp</th>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Läkemedel</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Fång</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Ersättning CT, MR, Scint</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Hjärtsjukdomar</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Hovskador</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Kvarka</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Rehab</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Tandvård</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Hälta</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>EOTRH</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Botulism</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Luftrörsjukdomar</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="compare-table-more">
                                    <span>Visa mer</span>
                                </div>

                                <p class="klausul">Observera att detta endast är ett urval av vad våra olika försäkringar täcker. För fullständig information, läs villkoren som du hittar längst ner på sidan.</p>

                            </div>

                        </div>

                        <!-- Avel / Breeding
                <div class="resultat-select-caption @if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 7)
                            {{ 'active' }}
                        @endif" data-content="vvf-7" data-type="vet">

	                <div class="bullet-lists">

	                	<p class="resultat-select-caption-title">Special Veterinärvård</p>

		                <ul class="bullet-list active">
		                	<li>Skydd för dolda fel</li>
		                	<li>Ingen fast självrisk</li>
		                	<li>Ingen karenstid*</li>
		                	<li>Frakturer</li>
		                	<li>Sårskador</li>
		                	<li>Kolik & bukingrepp</li>
		                </ul>

		                <ul class="bullet-list active">
		                	<li>Läkemedel</li>
		                	<li>Fång</li>
		                	<li>Ersättning CT, MR, Scint</li>
		                	<li>Hjärtsjukdomar</li>
		                	<li>Hovskador</li>
		                	<li>Kvarka</li>
		                </ul>

		                <ul class="bullet-list">
		                	<li>Rehab</li>
		                	<li>Tandvård</li>
		                	<li>Hälta</li>
		                	<li>EOTRH</li>
		                	<li>Botulism</li>
		                	<li>Luftrörsjukdomar</li>
		                </ul>

		            </div>

		            <div class="compare-table-wrapper">

						<table class="compare-table compare-table-special">
							<thead>
								<tr>
									<th></th>
									<th>Grund</th>
									<th>Special</th>
									<th>Premium</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th>Skydd för dolda fel</th>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Ingen fast självrisk</th>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Ingen karenstid</th>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Frakturer</th>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Sårskador</th>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Kolik & bukingrepp</th>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Läkemedel</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Fång</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Ersättning CT, MR, Scint</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Hjärtsjukdomar</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Hovskador</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr>
									<th>Kvarka</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr class="tr-disabled">
									<th>Rehab</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr class="tr-disabled">
									<th>Tandvård</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr class="tr-disabled">
									<th>Hälta</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr class="tr-disabled">
									<th>EOTRH</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr class="tr-disabled">
									<th>Botulism</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
								<tr class="tr-disabled">
									<th>Luftrörsjukdomar</th>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-nope"></i></td>
									<td><i class="icon icon-check"></i></td>
								</tr>
							</tbody>
						</table>

						<div class="compare-table-more">
							<span>Visa mer</span>
						</div>

						<p><small>Observera att detta endast är ett urval av vad våra olika försäkringar täcker. För fullständig information, läs villkoren som du hittar längst ner på sidan.</small></p>

					</div>

                </div>
                -->

                        <div class="resultat-select-caption @if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 8){{ 'active' }}@endif" data-content="vvf-8" data-type="vet">

                            <div class="bullet-lists">

                                <p class="resultat-select-caption-title">Grund Veterinärvård</p>

                                <ul class="bullet-list active" data-type="grund">
                                    <li>Skydd för dolda fel</li>
                                    <li>Ingen karenstid*</li>
                                    <li>Frakturer</li>
                                    <li>Sårskador</li>
                                    <li>Kolik & bukingrepp</li>
                                </ul>

                                <ul class="bullet-list" data-type="special">
                                    <li>Läkemedel</li>
                                    <li>Fång</li>
                                    <li>Ersättning CT, MR, Scint</li>
                                    <li>Hjärtsjukdomar</li>
                                    <li>Hovskador</li>
                                    <li>Kvarka</li>
                                </ul>

                                <ul class="bullet-list" data-type="premium">
                                    <li>Rehab</li>
                                    <li>Tandvård</li>
                                    <li>Hälta</li>
                                    <li>EOTRH</li>
                                    <li>Botulism</li>
                                    <li>Luftrörsjukdomar</li>
                                </ul>

                                <p class="klausul">Observera att detta endast är ett urval av vad våra olika försäkringar täcker. För fullständig information, läs villkoren som du hittar längst ner på sidan.</p>

                            </div>

                            <div class="compare-table-wrapper">

                                <table class="compare-table compare-table-grund">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Grund</th>
                                        <th>Special</th>
                                        <th>Premium</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>Skydd för dolda fel</th>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Ingen karenstid</th>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Frakturer</th>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Sårskador</th>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr>
                                        <th>Kolik & bukingrepp</th>
                                        <td><i class="icon icon-check active"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Läkemedel</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Fång</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Ersättning CT, MR, Scint</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Hjärtsjukdomar</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Hovskador</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Kvarka</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Rehab</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Tandvård</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Hälta</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>EOTRH</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Botulism</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    <tr class="tr-disabled">
                                        <th>Luftrörsjukdomar</th>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-nope"></i></td>
                                        <td><i class="icon icon-check"></i></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="compare-table-more">
                                    <span>Visa mer</span>
                                </div>

                                <p class="klausul">Observera att detta endast är ett urval av vad våra olika försäkringar täcker. För fullständig information, läs villkoren som du hittar längst ner på sidan.</p>

                            </div>

                        </div>

                        <div class="resultat-select-caption @if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 38){{ 'active' }}@endif" data-content="vvf-38" data-type="vet">
                            <p><strong>Foster & Föl</strong> är för dig som vill teckna en omfattande försäkring för ditt tilltänkta föl samt veterinärvård och liv för fölet. Försäkringen kan ge ersättning vid resorption och kastning samt om fölet behöver undersökas, behandlas och vårdas av veterinär vid de flesta skador och sjukdomar de första 30 levnadsdagarna eller blivit enskilt försäkrad.</p>
                        </div>

                        @if((isset($defaults['livforsakring']) && $defaults['livforsakring'] == 38) || (isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 38))
                    </div>
                @endif
                <div class="frame-resultat-template">
                    {!! $resultat_template ?? '' !!}
                </div>
                <br/>
                <div class="resultat-bottom-wrapper">
                    <div class="startdatum-wrapper">
                        <h3>Välj startdatum</h3>
                        <input id="" class="datepicker" type="text" value="{{ $startdatum ?? '' }}" placeholder="åååå-mm-dd" name="startdatum">
                    </div>
                    <div class="stable-options">
                        <h4>Är din häst uppstallad på egen gård?</h4>
                        <ul>
                            <li>
                                <input id="stable-1a" type="radio" name="stable" value="Nej" checked>
                                <label for="stable-1a">Nej</label>
                                <div class="check"></div>
                            </li>
                            <li>
                                <input id="stable-0" type="radio" name="stable" value="Ja">
                                <label for="stable-0">Ja</label>
                                <div class="check"></div>
                            </li>
                        </ul>
                    </div>
                    <div class="swb-medlem-options">
                        <h4>Är din häst registrerad i Swedish Warmblood Association (SWB)?</h4>
                        <ul>
                            <li>
                                <input id="swbmedlem-0" type="radio" name="swbmedlem" value="Nej" checked>
                                <label for="swbmedlem-0">Nej</label>
                                <div class="check"></div>
                            </li>
                            <li>
                                <input id="swbmedlem-1a" type="radio" name="swbmedlem" value="Ja">
                                <label for="swbmedlem-1a">Ja</label>
                                <div class="check"></div>
                            </li>
                            <li>
                                <input id="swbmedlem-1b" type="radio" name="swbmedlem" value="Ja - Unghäst">
                                <label for="swbmedlem-1b">Ja, och min häst är 3 år eller yngre.</label>
                                <div class="check"></div>
                            </li>
                        </ul>
                    </div>

                        <div style="display: none;">


                            <div class="uppsagning-options">

                                <h4>Vill du ha hjälp med att säga upp din nuvarande försäkring när den löper ut?</h4>

                                <ul>
                                    <li>
                                        <input id="uppsagning-1" type="radio" name="uppsagning" value="0" checked>
                                        <label for="uppsagning-1">Nej, jag tar hand om det själv</label>
                                        <div class="check"></div>
                                    </li>
                                    <li>
                                        <input id="uppsagning-0" type="radio" name="uppsagning" value="1">
                                        <label for="uppsagning-0">Ja, det låter bra!</label>
                                        <div class="check"></div>
                                    </li>
                                </ul>

                                <div class="resultat-select-caption uppsagning-caption">
                                    <p style="font-weight:500;">Hur fungerar det?</p>
                                    <p>Du får en trygg övergång med Skydd för dolda fel där vi överser att allt går rätt till. Du kommer att bli kontaktad av oss inom några dagar. Vi låter dig digitalt signera en uppsägningsfullmakt som vi sedan skickar in precis innan din nya försäkring börjar gälla. Visst är det skönt när det är enkelt?</p>
                                </div>

                            </div>

                        </div><br/>
                 
                </div>

              <button type="button" class="btn1 btn-next btn-full-width resultat-next">Gå vidare</button>

            </div>
        </div>
    </div>
</div>

<div id="resultat-price-mobile">
    Ditt pris <span class="resultat-price"><span class="price">{{ $price['utpris_formaterad'] ?? '' }}</span></span>
</div>

<div id="resultat-widget" class="price-wrapper">
    {!! $price['html'] ?? '' !!}
</div>

@include('steps.horseinsurance.resultat.footer')
@include('steps.horseinsurance.resultat.popup')

@include('steps.horseinsurance.resultat.pris_scripts')

<script type="text/javascript">
    function update_price() {
        var $form = $('#main-form');
        var data = $form.serialize();

        $.post('/step/resultat/get_price', data, function (data) {
            $('.price-wrapper').html(data.html);
            $('.price').html(data.utpris_formaterad);
            $('.resultat-points-int').html(data.points);
            $('.forsakring-enabled-wrapper').html(data.html_boxes);
        }, 'json');
    }

    function formatNumber(x, seperator) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, seperator);
        return parts.join(".");
    }

    $(document).ready(function () {

        $('.datepicker').dateDropdowns({
            defaultDateFormat: 'dd-mm-yyyy',
            yearLabel: 'ÅR',
            monthLabel: 'MÅNAD',
            dayLabel: 'DAG',
            maxYear: '{{ today()->addYears(1)->format('Y') }}',
            minYear: '{{ today()->format('Y') }}',
            daySuffixes: false
        });

        $('select').selectric();

        $('.frame-resultat-inputs').on('click', 'input[name=veterinarvardsforsakring]', function () {

            var $form = $('#main-form');
            var data = $form.serialize();
            var selected = $(this).attr('id');

            $.post('/step/resultat/reload_template', data, function (data) {
                $('.frame-resultat-template').html(data.html);
                $('.price-wrapper').html(data.price.html);
                $('.price').html(data.price.utpris_formaterad);
                $('.resultat-points-int').html(data.price.points);
            }, 'json');

            $('.resultat-select-caption[data-type=vet]').removeClass('active');
            $('.resultat-select-caption[data-type=vet][data-content=' + selected + ']').addClass('active');
        });

        // Update price
        $('.frame-resultat-template').on('change', 'input', function () {
            // update price here
            let name = $(this).attr('name');
            if (name === 'forsakring_enabled[vet]' || name === 'forsakring_enabled[liv]') {
                let value = $(this).val();
                let checked = $(this).prop('checked');
                let forsakring_enabled_length = $('.forsakring-enabled-wrapper').find('input[name^=forsakring_enabled]:checked').length;
                if (forsakring_enabled_length === 0) {
                    $('.forsakring-enabled-wrapper').find('input[name^=forsakring_enabled]').each(function () {
                        let value2 = $(this).val();
                        if (value !== value2) {
                            $(this).prop('checked', true);
                            $(this).removeClass('selected').addClass('selected');
                            return false;
                        }
                    });
                }

                // remove or add selected
                if (checked) {
                    $(this).removeClass('selected').addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            }
            update_price();
        });

        // Update price if checkbox is checked/unchecked dummy
        $('.price-wrapper').on('change', 'input', function (e) {
            let name = $(this).attr('name');
            let checked = $(this).prop('checked');
            let value = $(this).val();
            let forsakring_enabled_length = $('.price-wrapper').find('input[name^=forsakring_enabled]:checked').length;
            if (forsakring_enabled_length === 0) {
                $('.price-wrapper').find('input[name^=forsakring_enabled]').each(function () {
                    let value2 = $(this).val();
                    if (value !== value2) {
                        $(this).prop('checked', true);
                        return false;
                    }
                });
            }
            if (name === 'forsakring_enabled_dummy[liv]') {
                $('.forsakring-enabled-wrapper').find('input[name=forsakring_enabled\\\[liv\\\]]').prop('checked', checked).trigger('change');
            } else if (name === 'forsakring_enabled_dummy[vet]') {
                $('.forsakring-enabled-wrapper').find('input[name=forsakring_enabled\\\[vet\\\]]').prop('checked', checked).trigger('change');
            }

            // remove or add selected
            if (checked) {
                $(this).removeClass('selected').addClass('selected');
            } else {
                $(this).removeClass('selected');
            }

        });

        $("input[name='uppsagning']").on("change", function () {
            $(".uppsagning-caption").slideToggle(200);
        });

        $(".compare-table-more").on("click", function () {
            $(".compare-table-more").fadeOut(300);
            $(".compare-table-more").parent().addClass("show");
        });

        $('input[name="startdatum"]').on('change', function () {
            update_price();
        });

        $('input[name="swbmedlem"]').on('change', function () {
            update_price();
            console.log('change');
        });

        // Bugfix
        setTimeout(function () {
            $('input[name=veterinarvardsforsakring]:checked').trigger('click');
        }, 500);

        @foreach ($available['veterinarvardsforsakring'] as $key)
            $('.app-sidebar-veterinarvardsforsakring div[data-insurance="{{ $key }}"]').show();
        @endforeach

        @foreach ($available['livforsakring']['all'] as $key)
            $('.app-sidebar-livforsakring div[data-insurance="{{ $key }}"]').show();
        @endforeach
    });

</script>
