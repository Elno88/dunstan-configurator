<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents bubble-hide-mobile">
                <i class="bubble-help btn-sidebar" data-content="hastforsakring-a-ff">?</i>
                <p class="font-heading-mobile mb-md-1">
                    Är {{ $horse_name ?? 'stoet' }} försäkrad idag?
                </p>
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <select class="bubble-select" name="insurance_type">
                    <option value="" selected disabled>Välj försäkring</option>
                    @foreach ($insurance_type as $key => $type)
                        <option value="{{ $type }}" @if(isset($selected_insurance_type) && $selected_insurance_type == $type) selected @endif>{{ $type }}</option>
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
