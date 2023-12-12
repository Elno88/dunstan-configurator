<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="hastforsakring-a-5">?</i>
                <p><span style="font-weight:600;">Vilken ras är {{ $horse_name ?? 'hästen' }}?</span><br/>
                Förresten, du vet väl om att du får <span style="text-decoration:underline;" class="btn-sidebar" data-content="trygghetsgaranti">Trygghetsgaranti</span> när du blir ny kund hos oss?</p>
            </div>
        </div>

        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <select class="bubble-select" name="breed" id="select-breed">
                    <option value="" selected disabled>Välj ras</option>
                    @foreach($breed_priority as $priority)
                        @if(isset($breeds[$priority]))
                            <option value="{{ $breeds[$priority] }}" @if(isset($selected_breed) && $selected_breed == $breeds[$priority]) selected @endif>{{ $breeds[$priority] }}</option>
                        @endif
                    @endforeach
                    @foreach($breeds as $key => $breed)
                        @if(in_array($key, $breed_priority))
                            @continue
                        @endif
                        <option value="{{ $breed }}" @if(isset($selected_breed) && $selected_breed == $breed) selected @endif>{{ $breed }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bubble bubble-type-d center" id="select-trotting" style="display:none;z-index:auto;">
            <div class="bubble-contents bubble-trotting-breed">
                <div class="trotting-breed-label">
                    Tävlar din häst aktivt inom trav/galopp?
                </div>
                <div class="trotting-breed-content">
                    <div>
                        <label class="container">
                            <input class="yesno-check" type="radio" name="trotting" value="1" {{ (isset($selected_trotting) && $selected_trotting == 1) ? 'checked' : null }} />
                            <span class="check"></span>
                            Ja
                        </label>
                    </div>
                    <div style="margin:0 16px;"></div>
                    <div>
                        <label class="container">
                            <input class="yesno-check" type="radio" name="trotting" value="0" {{ (isset($selected_trotting) && $selected_trotting == 0) ? 'checked' : null }} />
                            <span class="check"></span>
                            Nej
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="btn1 btn-next breed-next">Nästa</button>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#select-breed').selectric({
            onInit: function(element, object) {
                let value = $(element).val();

                let breeds = @json($trotting_breeds ?? []);

                if ($.inArray(value, breeds) >= 0) {
                    $('#select-trotting').show();

                    if ($('input[name=trotting]:checked').length <= 0) {
                        $('.breed-next').attr('disabled', 1);
                    } else {
                        $('.breed-next').removeAttr('disabled');
                    }
                } else {
                    $('#select-trotting').hide();
                    $('.breed-next').removeAttr('disabled');
                }
            },
            onChange: function(element, object) {
                let value = $(element).val();

                let breeds = @json($trotting_breeds ?? []);

                if ($.inArray(value, breeds) >= 0) {
                    $('#select-trotting').show();
                    if ($('input[name=trotting]:checked').length <= 0) {
                        $('.breed-next').attr('disabled', 1);
                    } else {
                        $('.breed-next').removeAttr('disabled');
                    }
                } else {
                    $('#select-trotting').hide();
                    $('.breed-next').removeAttr('disabled');
                }
            },
        });

        $(document).on('change', '#select-breed', function () {
            let value = $('#select-breed').val();

            let breeds = @json($trotting_breeds ?? []);

            if ($.inArray(value, breeds) >= 0) {
                $('#select-trotting').show();
                if ($('input[name=trotting]:checked').length <= 0) {
                    $('.breed-next').attr('disabled', 1);
                } else {
                    $('.breed-next').removeAttr('disabled');
                }
            } else {
                $('#select-trotting').hide();
                $('.breed-next').removeAttr('disabled');
            }
        });

        $(document).on('click', 'input[name=trotting]', function () {
            $('.breed-next').removeAttr('disabled');
        });
    });
</script>
