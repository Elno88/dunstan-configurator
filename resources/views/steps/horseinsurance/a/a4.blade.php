<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="hastforsakring-a-4">?</i>
                <p>Vad heter @if(isset($horse_usage) && $horse_usage == 2){{ 'stoet' }}@else{{ 'hästen' }}@endif, förresten?</p>
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <input type="text" value="{{ $name ?? '' }}" name="namn" placeholder="Hästens namn">
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <select class="bubble-select" name="farg">
                    <option disabled selected>Välj färg</option>
                    @foreach ($colors as $color)
                        @if ($farg === $color)
                            <option value="{{ $color }}" selected>{{ $color }}</option>
                        @else
                            <option value="{{ $color }}">{{ $color }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <button type="button" class="btn1 btn-next">Nästa</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('select').selectric();
    });
</script>
