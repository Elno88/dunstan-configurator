<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents bubble-hide-mobile">
                <i class="bubble-help btn-sidebar" data-content="hastforsakring-a-ff-1">?</i>
                <p class="font-heading-mobile mb-md-1">
                    Nu behöver vi veta lite mer om själva betäckningen!
                </p>
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <input type="text" value="{{ $stallion_name ?? '' }}" name="stallion_name" placeholder="Skriv in hingstens namn">
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <input type="text" value="{{ $seminstation ?? '' }}" name="seminstation" placeholder="Ange namn på seminstation">
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <select class="bubble-select" name="stallion_covering_type">
                    <option value="" selected disabled>Välj typ av betäckning</option>
                    @foreach($stallion_covering_type as $key => $type)
                        <option value="{{ $type }}" @if(isset($selected_stallion_covering_type) && $selected_stallion_covering_type == $type) selected @endif>{{ $type }}</option>
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
