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
                <select class="bubble-select" name="breed">
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

        <button type="button" class="btn1 btn-next breed-next">Nästa</button>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('select').selectric();
    });
</script>
